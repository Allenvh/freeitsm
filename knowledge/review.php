<?php
/**
 * Knowledge Base - Article Review Management
 */
session_start();
require_once '../config.php';

$current_page = 'review';
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Knowledge review</title>
    <link rel="stylesheet" href="../assets/css/inbox.css">
    <link rel="stylesheet" href="../assets/css/knowledge.css">
    <style>
        .review-container {
            padding: 16px 30px 24px;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .review-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
        }

        .filter-tab {
            padding: 8px 16px;
            background: #f5f5f5;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            color: #666;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-tab:hover {
            background: #e8e8e8;
        }

        .filter-tab.active {
            background: #8764b8;
            color: white;
        }

        .filter-tab .badge {
            background: rgba(0, 0, 0, 0.15);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
        }

        .filter-tab.active .badge {
            background: rgba(255, 255, 255, 0.25);
        }

        .filter-tab.overdue .badge {
            background: #dc3545;
            color: white;
        }

        .filter-tab.active.overdue .badge {
            background: rgba(255, 255, 255, 0.9);
            color: #dc3545;
        }

        .review-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .review-table th,
        .review-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .review-table th {
            background: #f9f9f9;
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
        }

        .review-table tr:hover {
            background: #fafafa;
        }

        .review-table tr:last-child td {
            border-bottom: none;
        }

        .article-title-link {
            color: #8764b8;
            text-decoration: none;
            font-weight: 500;
        }

        .article-title-link:hover {
            text-decoration: underline;
        }

        .review-date {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .review-date.overdue {
            color: #dc3545;
            font-weight: 500;
        }

        .review-date.upcoming {
            color: #fd7e14;
        }

        .review-date.ok {
            color: #28a745;
        }

        .review-date .days-badge {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
            background: #f0f0f0;
        }

        .review-date.overdue .days-badge {
            background: #dc3545;
            color: white;
        }

        .review-date.upcoming .days-badge {
            background: #fd7e14;
            color: white;
        }

        .no-date {
            color: #999;
            font-style: italic;
        }

        .owner-cell {
            color: #666;
        }

        .owner-cell.unassigned {
            color: #999;
            font-style: italic;
        }

        /* Icon-only edit button — same shape as the action buttons in the
           other settings pages. Square white box that picks up the module's
           accent (purple) on hover. */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            padding: 0;
            background: none;
            border: 1px solid #ddd;
            color: #666;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-btn:hover {
            background: #f0f0f0;
            border-color: #8764b8;
            color: #8764b8;
        }
        .action-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Days overdue column — bare number, red, bold. Empty cell if the
           article isn't overdue. */
        .days-overdue {
            color: #dc3545;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .loading {
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #8764b8;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="review-container">
        <div class="review-header">
            <h1>Article review schedule</h1>
        </div>

        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all" onclick="filterArticles('all')">
                All articles <span class="badge" id="countAll">0</span>
            </button>
            <button class="filter-tab overdue" data-filter="overdue" onclick="filterArticles('overdue')">
                Overdue <span class="badge" id="countOverdue">0</span>
            </button>
            <button class="filter-tab" data-filter="upcoming" onclick="filterArticles('upcoming')">
                Due in 30 days <span class="badge" id="countUpcoming">0</span>
            </button>
            <button class="filter-tab" data-filter="no_date" onclick="filterArticles('no_date')">
                No review date <span class="badge" id="countNoDate">0</span>
            </button>
        </div>

        <div id="reviewContent">
            <div class="loading"><div class="spinner"></div></div>
        </div>
    </div>

    <script>
        const API_BASE = '../api/knowledge/';
        let currentFilter = 'all';

        document.addEventListener('DOMContentLoaded', function() {
            loadReviewList();
        });

        async function loadReviewList() {
            try {
                const response = await fetch(API_BASE + 'get_review_list.php?filter=' + currentFilter);
                const data = await response.json();

                if (data.success) {
                    renderReviewTable(data.articles);
                    updateCounts(data.counts);
                } else {
                    document.getElementById('reviewContent').innerHTML =
                        '<div class="empty-state">Error loading articles: ' + data.error + '</div>';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('reviewContent').innerHTML =
                    '<div class="empty-state">Failed to load review list</div>';
            }
        }

        function renderReviewTable(articles) {
            const container = document.getElementById('reviewContent');

            if (articles.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M9 16l2 2 4-4"></path>
                        </svg>
                        <p>No articles found for this filter</p>
                    </div>
                `;
                return;
            }

            let html = `
                <table class="review-table">
                    <thead>
                        <tr>
                            <th>Article title</th>
                            <th>Owner</th>
                            <th>Next review date</th>
                            <th>Days overdue</th>
                            <th>Last modified</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            articles.forEach(article => {
                const reviewDateClass = article.is_overdue ? 'overdue' :
                    (article.days_until_review !== null && article.days_until_review <= 30) ? 'upcoming' : 'ok';

                let reviewDateHtml = '';
                if (article.next_review_date_formatted) {
                    let dueBadge = '';
                    if (!article.is_overdue && article.days_until_review !== null && article.days_until_review <= 30) {
                        dueBadge = `<span class="days-badge">in ${article.days_until_review} days</span>`;
                    }
                    reviewDateHtml = `<span class="review-date ${reviewDateClass}">${article.next_review_date_formatted} ${dueBadge}</span>`;
                } else {
                    reviewDateHtml = '<span class="no-date">Not set</span>';
                }

                const daysOverdueHtml = article.is_overdue
                    ? `<span class="days-overdue">${Math.abs(article.days_until_review)}</span>`
                    : '';

                const ownerHtml = article.owner_name
                    ? `<span class="owner-cell">${escapeHtml(article.owner_name)}</span>`
                    : '<span class="owner-cell unassigned">Unassigned</span>';

                const modifiedDate = new Date(article.modified_datetime).toLocaleDateString('en-GB', {
                    day: 'numeric', month: 'short', year: 'numeric'
                });

                html += `
                    <tr>
                        <td>
                            <a href="./?article=${article.id}" class="article-title-link">${escapeHtml(article.title)}</a>
                        </td>
                        <td>${ownerHtml}</td>
                        <td>${reviewDateHtml}</td>
                        <td>${daysOverdueHtml}</td>
                        <td>${modifiedDate}</td>
                        <td>
                            <a href="./?article=${article.id}&edit=1" class="action-btn" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }

        function updateCounts(counts) {
            document.getElementById('countAll').textContent = counts.total || 0;
            document.getElementById('countOverdue').textContent = counts.overdue || 0;
            document.getElementById('countUpcoming').textContent = counts.upcoming || 0;
            document.getElementById('countNoDate').textContent = counts.no_date || 0;
        }

        function filterArticles(filter) {
            currentFilter = filter;

            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.toggle('active', tab.dataset.filter === filter);
            });

            // Show loading
            document.getElementById('reviewContent').innerHTML =
                '<div class="loading"><div class="spinner"></div></div>';

            loadReviewList();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
