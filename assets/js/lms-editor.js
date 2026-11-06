/**
 * LMS course editor — lessons, questions, and the three AI helpers.
 *
 * The AI never writes to the database. Every helper drops its draft into the
 * form the author is already looking at, and it is saved by the same validated
 * endpoint a hand-typed lesson goes through. That is what keeps a bad generation
 * a nuisance rather than a problem.
 */
const LMSEditor = (() => {
    const API = window.API_BASE;
    const COURSE_ID = window.COURSE_ID;

    let course = window.COURSE;
    let lessons = [];
    let currentId = null;      // the lesson being edited
    let editor = null;         // TinyMCE
    let editorReady = false;   // TinyMCE has fired its `init` and can be written to
    let pendingBody = null;    // content that arrived before it could
    let articles = null;       // knowledge articles, loaded on first use
    let dragId = null;
    /* What "unsaved changes" is measured against — see snapshotBaseline().
       Taken FROM the editor, not from the API response, because TinyMCE
       re-serialises whatever it is given. */
    let baselineTitle = '';
    let baselineBody = '';

    /** Escapes text for safe use in HTML. */
    const esc = (s) => { const d = document.createElement('div'); d.textContent = s ?? ''; return d.innerHTML.replace(/"/g, '&quot;').replace(/'/g, '&#39;'); };

    // ---------- boot ----------

    /**
     * Starts the course editor - the TinyMCE editor, the lesson list and the pass-mark chip - and wires
     * the course, question and AI forms.
     */
    function init() {
        initTinyMCE();
        loadLessons();
        renderPassMark();

        document.getElementById('courseForm').addEventListener('submit', saveCourse);
        document.getElementById('questionForm').addEventListener('submit', saveQuestion);
        document.getElementById('outlineForm').addEventListener('submit', generateOutline);
        document.getElementById('lessonAiForm').addEventListener('submit', generateLesson);
    }

    /**
     * Creates the TinyMCE editor for the lesson body, themed light or dark, with the full-screen button
     * first on a phone.
     */
    function initTinyMCE() {
        const isDark = (document.documentElement.getAttribute('data-theme-mode') || 'light') === 'dark';
        // The same gate every mobile behaviour in the product uses. Read once
        // at init; an editor is not rebuilt on resize.
        const isPhone = window.matchMedia('(max-width: 768px)').matches;
        tinymce.init({
            // Plugins and skins load without the page's ?v=, so a browser could pair a new
            // editor with old cached plugins after an update. TinyMCE's own version stamps them.
            cache_suffix: '?v=' + tinymce.majorVersion + '.' + tinymce.minorVersion,
            selector: '#lessonBody',
            license_key: 'gpl',
            height: 420,
            menubar: false,
            skin: isDark ? 'oxide-dark' : 'oxide',
            content_css: isDark ? 'dark' : 'default',
            plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'table', 'code', 'fullscreen', 'searchreplace', 'wordcount', 'codesample'],
            // The full-screen control was already here, at the END of the
            // toolbar — and on a phone that is the same as not having it.
            // Measured on the identical config in the contracts round: at
            // 360px TinyMCE's own overflow collapses the bar to `undo redo …`
            // and buries the button inside the "…" popup, which is the worst
            // place for the one control that rescues a small editor.
            // First on a phone, unchanged on a desktop.
            toolbar: isPhone
                ? 'fullscreen | undo redo | blocks | bold italic | bullist numlist | link image table | codesample code | removeformat'
                : 'undo redo | blocks | bold italic | bullist numlist | link image table | codesample code | removeformat | fullscreen',
            toolbar_mode: isPhone ? 'sliding' : 'floating',
            content_style: 'body { font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; line-height: 1.7; } @media (pointer: coarse) { body { font-size: 16px; } }',
            // 🔴 `setup` fires when the editor STARTS initialising, not when it
            // is ready to be written to — and init() kicks off initTinyMCE()
            // and loadLessons() side by side, so the two race.
            //
            // Lose the race and `editor` is still null when selectLesson runs,
            // its `if (editor)` is false, and THE LESSON BODY IS SILENTLY
            // DROPPED: the editor comes up empty and a refresh "fixes" it by
            // running the race again, usually winning the second time. That is
            // exactly the shape of a bug that gets reported as "sometimes".
            //
            // Waiting on `init` and holding the content until then removes the
            // race in both directions rather than making it less likely.
            setup: (ed) => {
                editor = ed;
                ed.on('init', () => {
                    editorReady = true;
                    if (pendingBody !== null) {
                        ed.setContent(pendingBody);
                        pendingBody = null;
                        snapshotBaseline();
                    }
                });
            }
        });
    }

    /* Write to the editor, or remember it until the editor exists. Every
       caller goes through here so none of them has to know whether TinyMCE
       has finished starting up. */
    function setEditorBody(html) {
        if (editor && editorReady) { editor.setContent(html); pendingBody = null; snapshotBaseline(); }
        else { pendingBody = html; }
    }

    /* 🔴 WHAT "UNSAVED CHANGES" IS MEASURED AGAINST.
       It used to be the lesson as the API returned it, compared with
       `editor.getContent()`. Those two are not the same kind of string:
       TinyMCE RE-SERIALISES what you give it, and by default
       (`entity_encoding: 'named'`) it writes non-ASCII characters back as
       named entities. So a body stored as

           example — a Customers table

       comes back out of the editor as

           example &mdash; a Customers table

       and the two never match. The lesson is "modified" from the instant it
       loads, and every attempt to move to another one asks whether you want
       to discard changes you never made.

       ⭐ It went unnoticed because content TYPED into the editor is saved in
       the editor's own encoding, so it compares equal on the way back in.
       Only text that reaches the database WITHOUT passing through TinyMCE is
       affected — which is exactly what an AI draft is, and AI writing uses
       real em dashes and curly quotes as a matter of course. Ed hit it on the
       first course he had drafted.

       The fix is to stop comparing two different representations. The
       baseline is taken FROM THE EDITOR, immediately after the content lands
       in it, so whatever normalising TinyMCE does has already happened on
       both sides of the comparison. That is also why it is captured here and
       in the `init` handler above rather than at the call site: the write can
       be deferred, and the baseline has to follow the content, not the
       request to set it. */
    function snapshotBaseline() {
        baselineTitle = document.getElementById('lessonTitle').value;
        baselineBody  = editor ? editor.getContent() : '';
    }

    // ---------- lessons ----------

    /**
     * Loads the course's lessons (with their questions) from lessons.php, redraws the list and opens the
     * first lesson when none is selected yet.
     */
    async function loadLessons() {
        const r = await fetch(`${API}lessons.php?course_id=${COURSE_ID}`);
        const d = await r.json();
        if (!d.success) { showToast(d.error, 'error'); return; }
        lessons = d.data || [];
        renderLessonList();

        // Land the author somewhere useful rather than on an empty pane.
        if (lessons.length && currentId === null) selectLesson(lessons[0].id);
        else if (currentId !== null) renderQuestions();
    }

    /**
     * Draws the numbered lesson list with question counts and delete buttons, and wires click-to-open and
     * drag-to-reorder on each row.
     */
    function renderLessonList() {
        const el = document.getElementById('lessonList');
        if (!lessons.length) {
            el.innerHTML = `<div class="lms-empty">${esc(window.t('lms.editor.no_lessons'))}</div>`;
            return;
        }
        el.innerHTML = lessons.map((l, i) => `
            <div class="lms-lesson-item ${l.id === currentId ? 'active' : ''}" draggable="true" data-id="${l.id}">
                <span class="lms-lesson-num">${i + 1}</span>
                <span class="lms-lesson-name">${esc(l.title)}</span>
                ${l.questions && l.questions.length ? `<span class="lms-lesson-q">${l.questions.length}Q</span>` : ''}
                <button class="lms-lesson-del table-action-btn delete" data-del="${l.id}" title="${esc(window.t('lms.editor.delete_lesson_title'))}">×</button>
            </div>
        `).join('');

        el.querySelectorAll('.lms-lesson-del').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();          // don't also select the lesson we're deleting
                deleteLesson(parseInt(btn.dataset.del, 10));
            });
        });

        el.querySelectorAll('.lms-lesson-item').forEach(item => {
            const id = parseInt(item.dataset.id, 10);
            item.addEventListener('click', () => selectLesson(id));
            item.addEventListener('dragstart', () => { dragId = id; item.classList.add('dragging'); });
            item.addEventListener('dragend', () => item.classList.remove('dragging'));
            item.addEventListener('dragover', (e) => e.preventDefault());
            item.addEventListener('drop', (e) => { e.preventDefault(); dropOn(id); });
        });
    }

    /**
     * Moves the dragged lesson to the position of the lesson it was dropped on and saves the new order
     * through lessons.php (REORDER).
     */
    async function dropOn(targetId) {
        if (dragId === null || dragId === targetId) return;
        const from = lessons.findIndex(l => l.id === dragId);
        const to = lessons.findIndex(l => l.id === targetId);
        if (from < 0 || to < 0) return;

        lessons.splice(to, 0, lessons.splice(from, 1)[0]);
        renderLessonList();
        dragId = null;

        await post('lessons.php', { _method: 'REORDER', course_id: COURSE_ID, ids: lessons.map(l => l.id) });
    }

    /** Opens a lesson in the editor pane, asking first when the current lesson has unsaved changes. */
    function selectLesson(id) {
        // Don't silently discard an edit in progress.
        if (currentId !== null && currentId !== id && isDirty()) {
            if (!confirm(window.t('lms.editor.unsaved'))) return;
        }
        currentId = id;
        const l = lessons.find(x => x.id === id);
        if (!l) return;

        document.getElementById('noLesson').style.display = 'none';
        document.getElementById('lessonPane').style.display = '';
        document.getElementById('lessonTitle').value = l.title;
        setEditorBody(l.body || '');
        document.getElementById('saveState').textContent = '';

        renderLessonList();
        renderQuestions();
    }

    /** True when the lesson title or body differs from what was last loaded or saved. */
    function isDirty() {
        if (currentId === null || !editor || !editorReady) return false;
        return baselineTitle !== document.getElementById('lessonTitle').value
            || baselineBody !== editor.getContent();
    }

    /** Asks for a title, creates an empty lesson with it and opens it. */
    function newLesson() {
        const title = prompt(window.t('lms.editor.new_lesson_prompt'));
        if (!title || !title.trim()) return;
        createLesson(title.trim(), '').then(id => { if (id) selectLesson(id); });
    }

    /** Creates a lesson through lessons.php, reloads the list and returns the new id, or null on failure. */
    async function createLesson(title, body) {
        const d = await post('lessons.php', { course_id: COURSE_ID, title, body });
        if (!d.success) { showToast(d.error, 'error'); return null; }
        await loadLessons();
        return d.id;
    }

    /** Saves the open lesson's title and body through lessons.php and resets the unsaved-changes baseline. */
    async function saveLesson() {
        const title = document.getElementById('lessonTitle').value.trim();
        if (!title) { showToast(window.t('lms.editor.title_required'), 'error'); return; }

        const d = await post('lessons.php', { id: currentId, course_id: COURSE_ID, title, body: editor.getContent() });
        if (!d.success) { showToast(d.error, 'error'); return; }

        // What is on screen IS what is stored now, so it becomes the new
        // baseline — otherwise the lesson stays "modified" after a save and
        // asks about discarding changes that have just been written.
        snapshotBaseline();

        document.getElementById('saveState').textContent = window.t('lms.editor.saved');
        showToast(window.t('lms.editor.saved'), 'success');
        await loadLessons();
    }

    /** Deletes a lesson after a confirm, closes the editor pane if it was the open one and reloads the list. */
    async function deleteLesson(id) {
        const ok = await showConfirm({
            title: window.t('lms.editor.delete_lesson_title'),
            message: window.t('lms.editor.delete_lesson_msg'),
            okLabel: window.t('lms.confirm.ok_delete'),
            okClass: 'danger'
        });
        if (!ok) return;

        const d = await post('lessons.php', { _method: 'DELETE', id });
        if (!d.success) { showToast(d.error, 'error'); return; }
        if (currentId === id) {
            currentId = null;
            document.getElementById('lessonPane').style.display = 'none';
            document.getElementById('noLesson').style.display = '';
        }
        await loadLessons();
    }

    // ---------- questions ----------

    /** The lesson currently open in the editor. */
    function currentLesson() { return lessons.find(l => l.id === currentId); }

    /**
     * Draws the open lesson's quiz questions with their answers, the correct ones marked, and Edit /
     * Delete buttons.
     */
    function renderQuestions() {
        const l = currentLesson();
        const el = document.getElementById('questionList');
        const qs = (l && l.questions) || [];

        if (!qs.length) {
            el.innerHTML = `<div class="lms-empty">${esc(window.t('lms.editor.no_questions'))}</div>`;
            return;
        }

        el.innerHTML = qs.map((q, i) => `
            <div class="lms-question">
                <div class="lms-question-head">
                    <span class="lms-question-num">${i + 1}</span>
                    <span class="lms-question-text">${esc(q.question_text)}</span>
                    <span class="lms-question-type">${esc(typeLabel(q.question_type))}</span>
                    <span class="lms-question-actions">
                        <button class="table-action-btn" onclick="LMSEditor.editQuestion(${q.id})" title="${esc(window.t('lms.editor.edit'))}">✎</button>
                        <button class="table-action-btn delete" onclick="LMSEditor.deleteQuestion(${q.id})" title="${esc(window.t('lms.confirm.ok_delete'))}">🗑</button>
                    </span>
                </div>
                <ul class="lms-question-answers">
                    ${q.answers.map(a => `<li class="${a.is_correct ? 'correct' : ''}">${esc(a.answer_text)}</li>`).join('')}
                </ul>
            </div>
        `).join('');
    }

    /** The translated label for a question type (single, multiple, truefalse). */
    function typeLabel(t) {
        return { single: window.t('lms.editor.type_single'), multiple: window.t('lms.editor.type_multiple'), truefalse: window.t('lms.editor.type_truefalse') }[t] || t;
    }

    /** Opens the question modal, filled from a question when editing or with two blank answers when adding. */
    function openQuestionModal(q) {
        document.getElementById('qId').value = q ? q.id : '';
        document.getElementById('qText').value = q ? q.question_text : '';
        document.getElementById('qType').value = q ? q.question_type : 'single';
        document.getElementById('qExplanation').value = q ? (q.explanation || '') : '';
        document.getElementById('questionModalTitle').textContent =
            q ? window.t('lms.editor.edit_question') : window.t('lms.editor.add_question');

        const rows = document.getElementById('answerRows');
        rows.innerHTML = '';
        if (q) q.answers.forEach(a => addAnswerRow(a.answer_text, !!a.is_correct));
        else { addAnswerRow(); addAnswerRow(); }

        onTypeChange();
        document.getElementById('questionModal').classList.add('active');
    }

    /** Opens the question modal for one question of the open lesson. */
    function editQuestion(id) {
        const q = (currentLesson().questions || []).find(x => x.id === id);
        if (q) openQuestionModal(q);
    }

    /** Adds an answer row to the question modal - a radio button for single-answer types, a tick box for multiple. */
    function addAnswerRow(text = '', correct = false) {
        const type = document.getElementById('qType').value;
        const rows = document.getElementById('answerRows');
        const row = document.createElement('div');
        row.className = 'lms-answer-row';
        // Radio for the types that allow exactly one right answer, checkbox for
        // 'multiple' — the control itself tells the author what the rule is.
        row.innerHTML = `
            <input type="${type === 'multiple' ? 'checkbox' : 'radio'}" name="correct" ${correct ? 'checked' : ''} title="${esc(window.t('lms.editor.mark_correct'))}">
            <input type="text" class="answer-text" value="${esc(text)}" placeholder="${esc(window.t('lms.editor.answer_placeholder'))}">
            <button type="button" class="table-action-btn delete" onclick="this.parentElement.remove()">×</button>
        `;
        rows.appendChild(row);
    }

    /**
     * Reshapes the answer rows when the question type changes - fixed True / False rows for truefalse,
     * radio or tick boxes otherwise - and keeps only one answer ticked for single-answer types.
     */
    function onTypeChange() {
        const type = document.getElementById('qType').value;
        const rows = document.getElementById('answerRows');

        if (type === 'truefalse') {
            // True/false has exactly two fixed options — don't make the author type them.
            const correctIsTrue = !rows.querySelector('.lms-answer-row:nth-child(2) input[type=radio]:checked');
            rows.innerHTML = '';
            addAnswerRow(window.t('lms.editor.true'), correctIsTrue);
            addAnswerRow(window.t('lms.editor.false'), !correctIsTrue);
            rows.querySelectorAll('.answer-text').forEach(i => i.readOnly = true);
            rows.querySelectorAll('.table-action-btn').forEach(b => b.style.display = 'none');
            document.getElementById('addAnswerBtn').style.display = 'none';
            return;
        }

        document.getElementById('addAnswerBtn').style.display = '';
        rows.querySelectorAll('.answer-text').forEach(i => i.readOnly = false);
        rows.querySelectorAll('.table-action-btn').forEach(b => b.style.display = '');

        // Swap radio <-> checkbox in place, keeping what's already ticked.
        rows.querySelectorAll('.lms-answer-row').forEach(row => {
            const old = row.querySelector('input[name=correct]');
            const want = type === 'multiple' ? 'checkbox' : 'radio';
            if (old.type !== want) {
                const wasChecked = old.checked;
                old.type = want;
                old.checked = wasChecked;
            }
        });

        // Going multiple -> single can leave several ticked, which the server rejects.
        if (type !== 'multiple') {
            const checked = rows.querySelectorAll('input[name=correct]:checked');
            if (checked.length > 1) checked.forEach((c, i) => { if (i > 0) c.checked = false; });
        }
    }

    /**
     * Saves the question modal (text, type, explanation and the non-empty answers) through questions.php
     * and reloads the lessons.
     */
    async function saveQuestion(e) {
        e.preventDefault();
        const answers = [...document.querySelectorAll('#answerRows .lms-answer-row')].map(row => ({
            answer_text: row.querySelector('.answer-text').value.trim(),
            is_correct: row.querySelector('input[name=correct]').checked
        })).filter(a => a.answer_text !== '');

        const d = await post('questions.php', {
            id: document.getElementById('qId').value || 0,
            lesson_id: currentId,
            question_text: document.getElementById('qText').value.trim(),
            question_type: document.getElementById('qType').value,
            explanation: document.getElementById('qExplanation').value.trim(),
            answers
        });

        if (!d.success) { showToast(d.error, 'error'); return; }
        closeModal('questionModal');
        showToast(window.t('lms.editor.saved'), 'success');
        await loadLessons();
    }

    /** Deletes a question after a confirm and reloads the lessons. */
    async function deleteQuestion(id) {
        const ok = await showConfirm({
            title: window.t('lms.editor.delete_question_title'),
            message: window.t('lms.editor.delete_question_msg'),
            okLabel: window.t('lms.confirm.ok_delete'),
            okClass: 'danger'
        });
        if (!ok) return;

        const d = await post('questions.php', { _method: 'DELETE', id });
        if (!d.success) { showToast(d.error, 'error'); return; }
        await loadLessons();
    }

    // ---------- course settings ----------

    /** Shows the course's pass mark, or "no pass mark", on the chip in the header. */
    function renderPassMark() {
        const chip = document.getElementById('passMarkChip');
        chip.textContent = course.pass_mark === null
            ? window.t('lms.editor.no_pass_mark')
            : window.t('lms.editor.pass_mark_chip', { pct: course.pass_mark });
    }

    /** Opens the course settings modal filled with the title, description and pass mark. */
    function openCourseModal() {
        document.getElementById('cTitle').value = course.title || '';
        document.getElementById('cDescription').value = course.description || '';
        document.getElementById('cPassMark').value = course.pass_mark === null ? '' : course.pass_mark;
        document.getElementById('courseModal').classList.add('active');
    }

    /** Saves the course title, description and pass mark through course.php and updates the header. */
    async function saveCourse(e) {
        e.preventDefault();
        const pm = document.getElementById('cPassMark').value;
        const d = await post('course.php', {
            _method: 'PUT',
            id: COURSE_ID,
            title: document.getElementById('cTitle').value.trim(),
            description: document.getElementById('cDescription').value.trim(),
            pass_mark: pm === '' ? null : parseInt(pm, 10)
        });
        if (!d.success) { showToast(d.error, 'error'); return; }

        course.title = document.getElementById('cTitle').value.trim();
        course.description = document.getElementById('cDescription').value.trim();
        course.pass_mark = pm === '' ? null : parseInt(pm, 10);

        document.getElementById('courseTitle').textContent = course.title;
        renderPassMark();
        closeModal('courseModal');
        showToast(window.t('lms.editor.saved'), 'success');
    }

    // ---------- AI ----------

    /**
     * Asks the AI for a course outline on a topic and creates one lesson per outline item, each holding
     * the AI's summary as a starting paragraph.
     */
    async function generateOutline(e) {
        e.preventDefault();
        const btn = document.getElementById('outlineBtn');
        const topic = document.getElementById('oTopic').value.trim();
        const count = parseInt(document.getElementById('oCount').value, 10) || 5;

        busy(btn, true);
        const d = await post('ai_author.php', { mode: 'outline', topic, lesson_count: count });
        busy(btn, false);
        if (!d.success) { showToast(d.error, 'error'); return; }

        // Each lesson lands as a real, empty lesson with the AI's summary as a
        // starting paragraph — a skeleton to write into, not finished content.
        for (const l of d.draft.lessons) {
            await post('lessons.php', {
                course_id: COURSE_ID,
                title: l.title,
                body: l.summary ? `<p>${escapeForHtml(l.summary)}</p>` : ''
            });
        }
        closeModal('outlineModal');
        showToast(window.t('lms.editor.outline_done', { n: d.draft.lessons.length }), 'success');
        currentId = null;
        await loadLessons();
    }

    /** Opens the AI lesson modal, loading the knowledge article list into its drop-down the first time. */
    async function openLessonAiModal() {
        // GH #217: no picker (Knowledge switched off) - nothing to fill.
        if (articles === null && document.getElementById('aiArticle')) {
            const r = await fetch(`${API}knowledge_articles.php`);
            const d = await r.json();
            articles = d.data || [];
            const sel = document.getElementById('aiArticle');
            articles.forEach(a => {
                const o = document.createElement('option');
                o.value = a.id;
                o.textContent = a.title;
                sel.appendChild(o);
            });
        }
        document.getElementById('lessonAiModal').classList.add('active');
    }

    /**
     * Asks the AI to draft a lesson from a knowledge article or the lesson title and puts the draft in the
     * editor - nothing is saved until the author presses Save.
     */
    async function generateLesson(e) {
        e.preventDefault();
        const btn = document.getElementById('lessonAiBtn');
        const articleId = (document.getElementById('aiArticle') || {}).value || '';   // GH #217: absent while Knowledge is off
        const title = document.getElementById('lessonTitle').value.trim();

        if (!articleId && !title) { showToast(window.t('lms.editor.ai_lesson_need'), 'error'); return; }

        busy(btn, true);
        const d = await post('ai_author.php', { mode: 'lesson', article_id: articleId || 0, title });
        busy(btn, false);
        if (!d.success) { showToast(d.error, 'error'); return; }

        // Into the editor, not into the database — the author still has to press Save.
        if (d.draft.title && !title) document.getElementById('lessonTitle').value = d.draft.title;
        editor.setContent(d.draft.body);
        closeModal('lessonAiModal');
        showToast(window.t('lms.editor.ai_lesson_done'), 'success');
    }

    /**
     * Asks the AI for three quiz questions on the saved lesson and saves each through questions.php;
     * refuses while the lesson has unsaved changes.
     */
    async function generateQuiz() {
        if (!currentId) return;
        if (isDirty()) { showToast(window.t('lms.editor.save_first'), 'error'); return; }

        showToast(window.t('lms.editor.ai_thinking'), 'info');
        const d = await post('ai_author.php', { mode: 'quiz', lesson_id: currentId, question_count: 3 });
        if (!d.success) { showToast(d.error, 'error'); return; }

        // Each generated question goes through the same save endpoint as a typed
        // one, so a malformed key is rejected here rather than reaching a learner.
        let saved = 0;
        for (const q of d.draft.questions) {
            const r = await post('questions.php', { lesson_id: currentId, ...q });
            if (r.success) saved++;
        }
        showToast(window.t('lms.editor.ai_quiz_done', { n: saved }), 'success');
        await loadLessons();
    }

    // ---------- plumbing ----------

    /** Escapes text for safe use in HTML (the same as esc). */
    function escapeForHtml(s) { return esc(s); }

    /** Disables a button and shows the "thinking" label on it while an AI request runs, then puts the label back. */
    function busy(btn, on) {
        btn.disabled = on;
        if (on) { btn.dataset.label = btn.textContent; btn.textContent = window.t('lms.editor.ai_thinking'); }
        else if (btn.dataset.label) { btn.textContent = btn.dataset.label; }
    }

    /**
     * POSTs a JSON body to an LMS API endpoint and returns the JSON; a network error comes back as
     * {success: false, error}.
     */
    async function post(endpoint, body) {
        try {
            const r = await fetch(API + endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            return await r.json();
        } catch (err) {
            return { success: false, error: String(err) };
        }
    }

    /** Closes a modal by id. */
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    document.addEventListener('DOMContentLoaded', init);

    return {
        newLesson, saveLesson, deleteLesson, selectLesson,
        openQuestionModal, editQuestion, deleteQuestion, addAnswerRow, onTypeChange,
        openCourseModal, openOutlineModal: () => document.getElementById('outlineModal').classList.add('active'),
        openLessonAiModal, generateQuiz, closeModal
    };
})();
