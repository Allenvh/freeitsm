<?php
/**
 * Tasks Module Help Guide — full page with left-pane scroll-spy navigation
 */
session_start();
require_once '../config.php';
require_once '../includes/i18n.php';
I18n::initFromSession();

if (!isset($_SESSION['analyst_id'])) {
    header('Location: ../login.php');
    exit;
}

$current_page = 'help';
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(I18n::getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Tasks Guide</title>
    <link rel="stylesheet" href="../assets/css/inbox.css">
    <style>
        body { display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        .header { flex-shrink: 0; }

        .thp-container { display: flex; flex: 1; min-height: 0; background: #f5f5f5; }

        /* Left sidebar navigation */
        .thp-sidebar {
            width: 260px;
            background: #fff;
            border-right: 1px solid #ddd;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex-shrink: 0;
            overflow-y: auto;
        }
        .thp-sidebar h3 {
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 12px;
        }
        .thp-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 13px;
            color: #555;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .thp-nav-link:hover { background: #f5f5f5; color: #333; }
        .thp-nav-link.active { background: #f3f0ff; color: #6d28d9; font-weight: 600; }
        .thp-nav-num {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #eee;
            color: #888;
            font-weight: 700;
            font-size: 11px;
            flex-shrink: 0;
        }
        .thp-nav-link.active .thp-nav-num { background: #7c3aed; color: #fff; }

        /* Main content */
        .thp-main { flex: 1; overflow-y: auto; }

        .thp-hero {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 50%, #5b21b6 100%);
            color: #fff;
            padding: 40px 48px 36px;
            text-align: center;
        }
        .thp-hero h2 { margin: 0 0 8px; font-size: 26px; font-weight: 700; }
        .thp-hero p { margin: 0; font-size: 15px; opacity: 0.85; }

        .thp-content { max-width: 1120px; margin: 0 auto; padding: 10px 48px 48px; }

        /* Sections */
        .thp-section { padding: 28px 0; border-bottom: 1px solid #eee; scroll-margin-top: 20px; }
        .thp-section:last-child { border-bottom: none; padding-bottom: 0; }
        .thp-section-header { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px; }
        .thp-section-header h3 { margin: 0; font-size: 18px; color: #333; }
        .thp-section-header p { margin: 6px 0 0; font-size: 14px; color: #666; line-height: 1.6; }
        .thp-section > p { font-size: 14px; color: #555; line-height: 1.7; margin: 0 0 14px; }
        .thp-section-num {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f3f0ff;
            color: #6d28d9;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }
        .thp-section h4 { margin: 22px 0 8px; font-size: 14.5px; color: #333; }

        /* Feature cards grid */
        .thp-features-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .thp-feature-card {
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            background: #fff;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .thp-feature-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .thp-feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }
        .thp-feature-icon.purple { background: #f3e8ff; color: #7c3aed; }
        .thp-feature-icon.blue   { background: #e3f2fd; color: #0078d4; }
        .thp-feature-icon.green  { background: #e8f5e9; color: #2e7d32; }
        .thp-feature-icon.orange { background: #fff3e0; color: #e65100; }
        .thp-feature-icon.teal   { background: #e0f2f1; color: #00695c; }
        .thp-feature-icon.red    { background: #fce4ec; color: #c62828; }
        .thp-feature-card h4 { margin: 0 0 6px; font-size: 15px; color: #333; }
        .thp-feature-card p { margin: 0; font-size: 12.5px; color: #666; line-height: 1.5; }

        /* Numbered steps */
        .thp-steps { display: flex; flex-direction: column; gap: 12px; margin-left: 46px; }
        .thp-step-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 10px 14px;
            border-radius: 8px;
            background: #fafafa;
            font-size: 14px;
            color: #444;
            line-height: 1.5;
        }
        .thp-step-num {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #7c3aed;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        /* Definition-style list */
        .thp-fields { display: flex; flex-direction: column; gap: 8px; }
        .thp-fields > div {
            font-size: 13.5px;
            color: #555;
            line-height: 1.6;
            padding: 8px 12px;
            background: #fafafa;
            border-radius: 6px;
        }
        .thp-fields strong { color: #333; }

        /* Tip callout */
        .thp-tip {
            font-size: 13px !important;
            color: #6d28d9 !important;
            background: #f3f0ff;
            padding: 10px 14px;
            border-radius: 8px;
            border-left: 3px solid #7c3aed;
            margin-top: 12px !important;
        }

        /* Quick tips grid */
        .thp-tips-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .thp-tip-card {
            display: flex;
            gap: 12px;
            padding: 14px;
            background: #fafafa;
            border-radius: 8px;
            font-size: 13px;
            color: #555;
            line-height: 1.5;
        }
        .thp-tip-icon { font-size: 22px; flex-shrink: 0; }
        .thp-tip-card strong { color: #333; }

        @media (max-width: 900px) {
            .thp-sidebar { display: none; }
            .thp-content { padding: 10px 24px 40px; }
            .thp-hero { padding: 30px 24px; }
        }
        @media (max-width: 700px) {
            .thp-features-grid { grid-template-columns: 1fr; }
            .thp-tips-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="thp-container">
        <!-- Left pane navigation -->
        <div class="thp-sidebar">
            <h3>Guide</h3>
            <a href="#overview" class="thp-nav-link active" data-section="overview">
                <span class="thp-nav-num">1</span> Overview
            </a>
            <a href="#board" class="thp-nav-link" data-section="board">
                <span class="thp-nav-num">2</span> The board
            </a>
            <a href="#list" class="thp-nav-link" data-section="list">
                <span class="thp-nav-num">3</span> List view
            </a>
            <a href="#calendar" class="thp-nav-link" data-section="calendar">
                <span class="thp-nav-num">4</span> Calendar view
            </a>
            <a href="#timeline" class="thp-nav-link" data-section="timeline">
                <span class="thp-nav-num">5</span> Timeline view
            </a>
            <a href="#panel" class="thp-nav-link" data-section="panel">
                <span class="thp-nav-num">6</span> The task panel
            </a>
            <a href="#tags" class="thp-nav-link" data-section="tags">
                <span class="thp-nav-num">7</span> Tags
            </a>
            <a href="#settings" class="thp-nav-link" data-section="settings">
                <span class="thp-nav-num">8</span> Settings
            </a>
            <a href="#tips" class="thp-nav-link" data-section="tips">
                <span class="thp-nav-num">9</span> Quick tips
            </a>
        </div>

        <!-- Main content area -->
        <div class="thp-main" id="helpMain">
            <div class="thp-hero">
                <h2>Tasks module guide</h2>
                <p>Plan, assign, and track internal work on a Kanban board &mdash; with calendar, timeline, tags, and per-status columns.</p>
            </div>

            <div class="thp-content">

                <!-- 1. Overview -->
                <div class="thp-section" id="overview">
                    <div class="thp-section-header">
                        <span class="thp-section-num">1</span>
                        <div>
                            <h3>Overview</h3>
                            <p>The Tasks module is a lightweight work tracker for your service desk team. Use it for the jobs that aren't tickets &mdash; project work, internal admin, recurring chores, follow-ups &mdash; and see them as a Kanban board, a sortable list, a calendar, or a Gantt-style timeline. Tasks can link to tickets and changes, carry tags, and be broken into subtasks.</p>
                        </div>
                    </div>
                    <div class="thp-features-grid">
                        <div class="thp-feature-card">
                            <div class="thp-feature-icon purple">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                            </div>
                            <h4>Board</h4>
                            <p>A Kanban board with one column per status. Drag cards between columns to change status, drag column headers to reorder, and quick-add tasks straight into a column.</p>
                        </div>
                        <div class="thp-feature-card">
                            <div class="thp-feature-icon blue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            </div>
                            <h4>List view</h4>
                            <p>The same tasks as a sortable table &mdash; click any column header to sort by title, status, priority, assignee, team, or due date.</p>
                        </div>
                        <div class="thp-feature-card">
                            <div class="thp-feature-icon green">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                            <h4>Calendar</h4>
                            <p>A month grid placing tasks by due date. Multi-day tasks can show as a single chip, a spanning bar, or a chip on every day &mdash; your choice in Settings.</p>
                        </div>
                        <div class="thp-feature-card">
                            <div class="thp-feature-icon orange">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="14" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="5" y1="18" x2="16" y2="18"></line></svg>
                            </div>
                            <h4>Timeline</h4>
                            <p>A Gantt-style chart showing each task as a bar from its start date to its due date, grouped by assignee, status, or shown flat.</p>
                        </div>
                        <div class="thp-feature-card">
                            <div class="thp-feature-icon teal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            </div>
                            <h4>Tags</h4>
                            <p>Multi-value labels for cross-cutting themes &mdash; Security, ISO, Environment &mdash; that you can filter and search by.</p>
                        </div>
                        <div class="thp-feature-card">
                            <div class="thp-feature-icon red">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            </div>
                            <h4>Settings</h4>
                            <p>Configure the statuses, priorities and tags; control the calendar's multi-day rendering and what each card shows.</p>
                        </div>
                    </div>
                    <p class="thp-tip">The board, list, calendar and timeline are just different windows onto the same tasks. A change made in one is reflected in all of them.</p>
                </div>

                <!-- 2. The board -->
                <div class="thp-section" id="board">
                    <div class="thp-section-header">
                        <span class="thp-section-num">2</span>
                        <div>
                            <h3>The board</h3>
                            <p>The board is the default view &mdash; a Kanban layout with one column for each status. It's the fastest way to see where everything stands and to move work along.</p>
                        </div>
                    </div>

                    <h4>Columns are statuses</h4>
                    <p>Each column is a status defined in Settings &rarr; Statuses, shown in display order with the status's colour as a header dot. Add a status and it becomes a column; there's no separate "column" concept to manage. To reorder the columns, just <strong>drag a column header</strong> left or right &mdash; the new order is saved.</p>

                    <h4>Creating tasks</h4>
                    <div class="thp-steps">
                        <div class="thp-step-item">
                            <div class="thp-step-num">1</div>
                            <div><strong>Click the + on a column header</strong> &mdash; an inline box opens at the top of that column.</div>
                        </div>
                        <div class="thp-step-item">
                            <div class="thp-step-num">2</div>
                            <div><strong>Type a title and press Enter</strong> &mdash; the task is created in that column's status and assigned to you. Click it any time to fill in the rest.</div>
                        </div>
                    </div>

                    <h4>Moving and reordering</h4>
                    <p>Drag a card to another column to change its status; drag it up or down within a column to reorder. A purple line shows where it will land. Dropping a card into a status flagged as <em>Closed</em> stamps its completion time automatically.</p>

                    <h4>Right-click a card</h4>
                    <p>Right-clicking any card opens a quick-action menu &mdash; assign an analyst or team, change status or priority, or create a subtask &mdash; without opening the card at all.</p>

                    <h4>Search and filters</h4>
                    <p>The sidebar carries a <strong>Search</strong> box (filters as you type, matching the title and description &mdash; and tag names if enabled) plus filters for <strong>My Tasks / All Tasks</strong>, <strong>Team</strong>, <strong>Analyst</strong> and, when enabled, <strong>Tag</strong>. Filters and search apply to the list view too.</p>
                    <p class="thp-tip">Choose what each card shows &mdash; priority, assignee, team, dates, a description excerpt, subtask progress, tags &mdash; under Settings &rarr; Card, so you can scan tasks without opening them.</p>
                </div>

                <!-- 3. List view -->
                <div class="thp-section" id="list">
                    <div class="thp-section-header">
                        <span class="thp-section-num">3</span>
                        <div>
                            <h3>List view</h3>
                            <p>The list is the board's data twin &mdash; the same tasks as a sortable table. Switch to it with the View toggle in the sidebar.</p>
                        </div>
                    </div>
                    <p>Click any column header &mdash; Title, Status, Priority, Assignee, Team, Due &mdash; to sort by it; click again to reverse the direction. The search and sidebar filters work exactly as they do on the board, so you can narrow the list down and then sort what's left. Click a row to open the task.</p>
                    <p class="thp-tip">The list is the place to find a task whose status has no board column (for example a custom "Icebox" status) &mdash; the list shows every task regardless of status.</p>
                </div>

                <!-- 4. Calendar view -->
                <div class="thp-section" id="calendar">
                    <div class="thp-section-header">
                        <span class="thp-section-num">4</span>
                        <div>
                            <h3>Calendar view</h3>
                            <p>The calendar places tasks on a month grid so you can see deadlines and workload at a glance. Tasks are coloured by their status.</p>
                        </div>
                    </div>
                    <p>Navigate with the prev / next / Today buttons. The sidebar shows a colour legend and the same My / All / Team / Analyst filters as the board. Clicking a task jumps to the board with its panel open.</p>
                    <h4>How multi-day tasks are drawn</h4>
                    <p>A task with a start date earlier than its due date covers a range of days. How that range is shown is set once, per install, in Settings &rarr; Calendar:</p>
                    <div class="thp-fields">
                        <div><strong>Deadline chip</strong> &mdash; one chip on the due date only. The tidiest option; the full span is still visible on the timeline.</div>
                        <div><strong>Spanning bar</strong> &mdash; one continuous bar across the whole range, wrapping at week rows. Best for seeing duration.</div>
                        <div><strong>Every day</strong> &mdash; a chip in every day cell the task covers. Thorough, but long tasks can crowd the grid.</div>
                    </div>
                    <p style="margin-top:14px;">A task that has only a due date always shows as a single chip on that date, whichever mode is chosen.</p>
                </div>

                <!-- 5. Timeline view -->
                <div class="thp-section" id="timeline">
                    <div class="thp-section-header">
                        <span class="thp-section-num">5</span>
                        <div>
                            <h3>Timeline view</h3>
                            <p>The timeline is a Gantt-style chart. Each task is a horizontal bar running from its start date to its due date, so you can see overlaps, gaps and durations.</p>
                        </div>
                    </div>
                    <p>A task with only a due date shows as a single-day bar. Rows can be grouped by <strong>Assignee</strong>, <strong>Status</strong>, or shown flat &mdash; pick from the sidebar. A dashed marker shows today, the task-name column and date header stay put while you scroll, and the zoom control switches the day-column width. Click a bar to open the task on the board.</p>
                </div>

                <!-- 6. The task panel -->
                <div class="thp-section" id="panel">
                    <div class="thp-section-header">
                        <span class="thp-section-num">6</span>
                        <div>
                            <h3>The task panel</h3>
                            <p>Clicking a task opens a slide-in panel on the right. Every field auto-saves the moment you change it &mdash; there's no Save button.</p>
                        </div>
                    </div>
                    <div class="thp-fields">
                        <div><strong>Title</strong> &mdash; click and type at the top of the panel.</div>
                        <div><strong>Status &amp; Priority</strong> &mdash; dropdowns built from your configured lookups.</div>
                        <div><strong>Assignee &amp; Team</strong> &mdash; who owns the task and which team it belongs to.</div>
                        <div><strong>Start &amp; Due dates</strong> &mdash; together they define the span shown on the calendar and timeline.</div>
                        <div><strong>Tags</strong> &mdash; a type-to-filter picker; add as many as you like, remove with the &times; on each chip.</div>
                        <div><strong>Description</strong> &mdash; a rich-text editor with bold, lists and links.</div>
                        <div><strong>Links</strong> &mdash; search and attach related tickets or changes.</div>
                        <div><strong>Subtasks</strong> &mdash; break the task into a checklist; progress shows on the parent card.</div>
                        <div><strong>Comments</strong> &mdash; a running discussion thread on the task.</div>
                    </div>
                    <p style="margin-top:14px;">The delete button (top-right of the panel) removes the task and all of its subtasks &mdash; it asks for confirmation first.</p>
                </div>

                <!-- 7. Tags -->
                <div class="thp-section" id="tags">
                    <div class="thp-section-header">
                        <span class="thp-section-num">7</span>
                        <div>
                            <h3>Tags</h3>
                            <p>Tags are multi-value labels for cross-cutting themes &mdash; the things that don't fit a status or a priority. A task can carry any number of them.</p>
                        </div>
                    </div>
                    <p>Status answers "what stage is this at?", priority answers "how urgent?", and a tag answers "what is this <em>about</em>?" &mdash; Security, ISO, Environment, and so on. Because a task can hold several tags, you can slice your work by theme across every status and owner.</p>
                    <p>Tags are managed in Settings &rarr; Tags, each with a name and colour. Five display options there control whether tags can be created from a task and where they appear &mdash; card chips, the sidebar filter, search matching, and the calendar/timeline.</p>
                    <p class="thp-tip">A tag is the right home for a "someday / maybe" theme. Tag a handful of ideas as <em>Blue Sky</em> and you can pull them up any time with the tag filter, without a dedicated column.</p>
                </div>

                <!-- 8. Settings -->
                <div class="thp-section" id="settings">
                    <div class="thp-section-header">
                        <span class="thp-section-num">8</span>
                        <div>
                            <h3>Settings</h3>
                            <p>The Settings page has five tabs. Changes apply to everyone &mdash; they shape the board, calendar and cards for the whole team.</p>
                        </div>
                    </div>
                    <div class="thp-fields">
                        <div><strong>Statuses</strong> &mdash; the workflow states, which double as board columns. Set a colour, a display order, which one is the default for new tasks, and which count as <em>Closed</em>.</div>
                        <div><strong>Priorities</strong> &mdash; the priority bands shown on cards, each with a colour and a default.</div>
                        <div><strong>Calendar</strong> &mdash; choose how multi-day tasks are drawn on the calendar: deadline chip, spanning bar, or every day.</div>
                        <div><strong>Card</strong> &mdash; toggle which extras appear on board cards: priority, assignee, team, start date, due date, description excerpt, subtask progress, and the linked-item indicator.</div>
                        <div><strong>Tags</strong> &mdash; manage the tag list and the five tag display options.</div>
                    </div>
                </div>

                <!-- 9. Quick tips -->
                <div class="thp-section" id="tips">
                    <div class="thp-section-header">
                        <span class="thp-section-num">9</span>
                        <div>
                            <h3>Quick tips</h3>
                            <p>A few shortcuts and habits that make the module quicker to live in.</p>
                        </div>
                    </div>
                    <div class="thp-tips-grid">
                        <div class="thp-tip-card">
                            <span class="thp-tip-icon">🖱️</span>
                            <div><strong>Right-click is faster.</strong> Re-assign or re-prioritise a card from its context menu without opening it.</div>
                        </div>
                        <div class="thp-tip-card">
                            <span class="thp-tip-icon">⌨️</span>
                            <div><strong>Quick-add, then Enter.</strong> Capture a task title with the column + button and flesh it out later.</div>
                        </div>
                        <div class="thp-tip-card">
                            <span class="thp-tip-icon">🔍</span>
                            <div><strong>Search is instant.</strong> It filters as you type and matches every word &mdash; no need to wait or press Enter.</div>
                        </div>
                        <div class="thp-tip-card">
                            <span class="thp-tip-icon">🏷️</span>
                            <div><strong>Tag for themes.</strong> Use tags for anything that cuts across statuses &mdash; compliance, security, projects.</div>
                        </div>
                        <div class="thp-tip-card">
                            <span class="thp-tip-icon">📅</span>
                            <div><strong>Two dates, two views.</strong> Set a start and a due date and the task draws itself on both the calendar and the timeline.</div>
                        </div>
                        <div class="thp-tip-card">
                            <span class="thp-tip-icon">🔗</span>
                            <div><strong>Link the source.</strong> Attach the ticket or change a task came from, so the context is one click away.</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Scroll-spy: highlight the active section in the sidebar as the user scrolls
        const helpMain = document.getElementById('helpMain');
        const navLinks = document.querySelectorAll('.thp-nav-link');
        const sections = [];

        navLinks.forEach(link => {
            const el = document.getElementById(link.dataset.section);
            if (el) sections.push({ id: link.dataset.section, el });
        });

        helpMain.addEventListener('scroll', function () {
            const scrollTop = helpMain.scrollTop;
            let current = sections[0] && sections[0].id;
            for (const s of sections) {
                if (s.el.offsetTop - 200 <= scrollTop) current = s.id;
            }
            navLinks.forEach(link => {
                link.classList.toggle('active', link.dataset.section === current);
            });
        });

        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const el = document.getElementById(this.dataset.section);
                if (el) {
                    const containerTop = helpMain.getBoundingClientRect().top;
                    const elTop = el.getBoundingClientRect().top;
                    helpMain.scrollTo({ top: helpMain.scrollTop + (elTop - containerTop) - 20, behavior: 'smooth' });
                }
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
