/**
 * CMDB Browse page — class sidebar + object table.
 * Click a class to filter; click a row to open the object detail page.
 */

const API = '../api/cmdb/';

let classes = [];
let activeClass = null;
let objects = [];
let searchTimer = null;

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c]);
}
function showInlineToast(msg, isError = false) {
    if (typeof showToast === 'function') showToast(msg, isError ? 'error' : 'success');
    else alert(msg);
}
async function postJson(url, body) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body || {})
    });
    return res.json();
}

document.addEventListener('DOMContentLoaded', () => {
    loadClasses();
});

async function loadClasses() {
    const list = document.getElementById('classList');
    try {
        const res = await fetch(API + 'get_classes.php');
        const data = await res.json();
        if (!data.success) throw new Error(data.error || 'Failed to load classes');
        classes = (data.classes || []).filter(c => c.is_active);
        if (classes.length === 0) {
            list.innerHTML = `<div style="padding: 16px; color: #6b7280; font-size: 13px;">
                No classes yet. <a href="settings/" style="color: #be185d;">Set some up in Settings</a>.
            </div>`;
            return;
        }
        renderClassList();
    } catch (err) {
        list.innerHTML = `<div style="padding: 16px; color: #b91c1c; font-size: 13px;">Error: ${escapeHtml(err.message)}</div>`;
    }
}

function renderClassList() {
    const list = document.getElementById('classList');
    list.innerHTML = classes.map(c => `
        <div class="class-item ${activeClass && activeClass.id === c.id ? 'active' : ''} ${c.object_count === 0 ? 'empty' : ''}"
             onclick="selectClass(${c.id})">
            <span>${escapeHtml(c.name)}</span>
            <span class="count">${c.object_count}</span>
        </div>
    `).join('');
}

function selectClass(id) {
    activeClass = classes.find(c => c.id === id);
    if (!activeClass) return;
    document.getElementById('mainTitle').textContent = activeClass.name;
    document.getElementById('newObjectBtn').disabled = false;
    document.getElementById('searchInput').value = '';
    renderClassList();
    loadObjects();
}

async function loadObjects(searchOverride = null) {
    if (!activeClass) return;
    const list = document.getElementById('objectList');
    list.innerHTML = `<div class="empty-state"><p>Loading…</p></div>`;
    const search = searchOverride !== null ? searchOverride : document.getElementById('searchInput').value.trim();
    const url = API + 'get_objects.php?class_id=' + activeClass.id + (search ? '&search=' + encodeURIComponent(search) : '');
    try {
        const res = await fetch(url);
        const data = await res.json();
        if (!data.success) throw new Error(data.error || 'Failed to load objects');
        objects = data.objects || [];
        renderObjects();
    } catch (err) {
        list.innerHTML = `<div class="empty-state"><p style="color: #b91c1c;">Error: ${escapeHtml(err.message)}</p></div>`;
    }
}

function renderObjects() {
    const list = document.getElementById('objectList');
    if (objects.length === 0) {
        list.innerHTML = `
            <div class="empty-state">
                <h3>No objects in <em>${escapeHtml(activeClass.name)}</em> yet.</h3>
                <p>Click <strong>+ New</strong> to create one.</p>
            </div>`;
        return;
    }
    list.innerHTML = `
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Children</th>
                    <th style="width: 180px;">Updated</th>
                </tr>
            </thead>
            <tbody>
                ${objects.map(o => `
                    <tr onclick="openObject(${o.id})">
                        <td><span class="object-name">${escapeHtml(o.name)}</span></td>
                        <td>${o.parent_id
                            ? `<span class="parent-link"><strong>${escapeHtml(o.parent_name || '?')}</strong> <span style="color:#9ca3af">(${escapeHtml(o.parent_class_name || '')})</span></span>`
                            : '<span style="color:#d1d5db;">—</span>'}</td>
                        <td>${o.child_count > 0 ? `<span class="badge-count">${o.child_count}</span>` : '<span style="color:#d1d5db;">—</span>'}</td>
                        <td style="color:#6b7280; font-size: 13px;">${formatDate(o.updated_datetime)}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

function formatDate(s) {
    if (!s) return '';
    try {
        const d = new Date(s.replace(' ', 'T') + 'Z');
        return d.toLocaleString();
    } catch (e) { return s; }
}

function onSearchInput() {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => loadObjects(), 200);
}

function openObject(id) {
    window.location.href = 'object.php?id=' + id;
}

// New Object modal
function openNewObjectModal() {
    if (!activeClass) return;
    document.getElementById('newObjectClassName').textContent = activeClass.name;
    document.getElementById('newObjectName').value = '';
    document.getElementById('newObjectModal').classList.add('active');
    setTimeout(() => document.getElementById('newObjectName').focus(), 0);
}

function closeNewObjectModal() {
    document.getElementById('newObjectModal').classList.remove('active');
}

async function createObject() {
    const name = document.getElementById('newObjectName').value.trim();
    if (name === '') {
        showInlineToast('Name is required', true);
        return;
    }
    try {
        const data = await postJson(API + 'save_object.php', {
            class_id: activeClass.id,
            name,
            parent_id: null,
            property_values: []
        });
        if (!data.success) throw new Error(data.error || 'Save failed');
        closeNewObjectModal();
        // Jump straight into the new object's detail page so the analyst can fill in properties
        window.location.href = 'object.php?id=' + data.id;
    } catch (err) {
        showInlineToast('Error: ' + err.message, true);
    }
}

// Allow Enter to submit the new-object name
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('newObjectName');
    if (input) {
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); createObject(); }
        });
    }
});
