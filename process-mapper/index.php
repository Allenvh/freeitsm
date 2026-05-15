<?php
/**
 * Process Mapper - Visual flowchart builder
 */
session_start();
require_once '../config.php';

$current_page = 'process-mapper';
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Process Mapper</title>
    <link rel="stylesheet" href="../assets/css/inbox.css">
    <link rel="stylesheet" href="../assets/css/process-mapper.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="pm-layout">
        <!-- Sidebar: process list -->
        <div class="pm-sidebar" id="pmSidebar">
            <div class="pm-sidebar-header">
                <button class="btn btn-primary btn-full" onclick="PM.createProcess()">+ New Process</button>
            </div>
            <div class="pm-sidebar-search">
                <input type="text" id="processSearch" placeholder="Search processes..." oninput="PM.filterProcesses(this.value)">
            </div>
            <div class="pm-process-list" id="processList">
                <div class="pm-empty">No processes yet</div>
            </div>
        </div>

        <!-- Canvas area -->
        <div class="pm-canvas-wrap" id="canvasWrap">
            <!-- Toolbar -->
            <div class="pm-toolbar" id="pmToolbar">
                <div class="pm-toolbar-left">
                    <button class="pm-tool-btn" data-type="process" title="Process step">
                        <svg width="18" height="18" viewBox="0 0 18 18"><rect x="1" y="3" width="16" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        <span>Process</span>
                    </button>
                    <button class="pm-tool-btn" data-type="decision" title="Decision">
                        <svg width="18" height="18" viewBox="0 0 18 18"><polygon points="9,1 17,9 9,17 1,9" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        <span>Decision</span>
                    </button>
                    <button class="pm-tool-btn" data-type="start" title="Start / End">
                        <svg width="18" height="18" viewBox="0 0 18 18"><ellipse cx="9" cy="9" rx="8" ry="5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        <span>Terminal</span>
                    </button>
                    <button class="pm-tool-btn" data-type="document" title="Document">
                        <svg width="18" height="18" viewBox="0 0 18 18"><path d="M2 2h14v12c-2.3 1.3-4.7 1.3-7 0s-4.7-1.3-7 0V2z" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        <span>Document</span>
                    </button>
                    <div class="pm-tool-sep"></div>
                    <button class="pm-tool-btn" id="connectBtn" title="Draw connector (or drag from edge handle)">
                        <svg width="18" height="18" viewBox="0 0 18 18"><line x1="3" y1="15" x2="15" y2="3" stroke="currentColor" stroke-width="1.5"/><polyline points="10,3 15,3 15,8" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        <span>Connect</span>
                    </button>
                    <button class="pm-tool-btn" onclick="PM.addGroup()" title="Add a labelled coloured rectangle behind steps to visually group them">
                        <svg width="18" height="18" viewBox="0 0 18 18"><rect x="1" y="1" width="16" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 2"/></svg>
                        <span>Group</span>
                    </button>
                    <button class="pm-tool-btn" onclick="PM.addLane()" title="Add a horizontal swimlane (steps you drop into the band gain its lane assignment)">
                        <svg width="18" height="18" viewBox="0 0 18 18"><rect x="1" y="3" width="16" height="4" fill="none" stroke="currentColor" stroke-width="1.5"/><rect x="1" y="11" width="16" height="4" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        <span>Lane</span>
                    </button>
                    <div class="pm-tool-sep"></div>
                    <button class="pm-tool-btn" onclick="PM.openExportModal()" title="Export this flow as Mermaid markup (paste into GitHub, Notion, Confluence, Obsidian, etc.)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        <span>Export</span>
                    </button>
                </div>
                <div class="pm-toolbar-right">
                    <button class="pm-tool-btn" onclick="PM.deleteSelected()" title="Delete selected (Del)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                    <div class="pm-tool-sep"></div>
                    <span class="pm-status" id="pmStatus" aria-live="polite"></span>
                    <label class="pm-autosave-toggle" title="Auto-save every couple of seconds after you stop editing">
                        <input type="checkbox" id="pmAutosaveToggle" onchange="PM.toggleAutosave(this.checked)">
                        <span class="pm-autosave-switch"></span>
                        <span class="pm-autosave-label">Autosave</span>
                    </label>
                    <button class="pm-tool-btn" onclick="PM.save()" title="Save (Ctrl+S)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span>Save</span>
                    </button>
                </div>
            </div>

            <!-- Canvas with dot grid -->
            <div class="pm-canvas" id="pmCanvas" tabindex="0">
                <svg class="pm-connectors-svg" id="connectorsSvg"></svg>
                <!-- Steps are rendered here as absolutely positioned divs -->
                <div class="pm-canvas-empty" id="canvasEmpty">
                    <p>Select a process from the sidebar or create a new one</p>
                </div>
            </div>
        </div>

        <!-- Detail panel (slides in) -->
        <div class="pm-detail-panel" id="detailPanel">
            <div class="pm-detail-header">
                <h3 id="detailTitle">Step Details</h3>
                <button class="pm-detail-close" onclick="PM.closeDetail()">&times;</button>
            </div>
            <!-- Step detail body -->
            <div class="pm-detail-body" id="detailBodyStep">
                <div class="form-group">
                    <label class="form-label">Label</label>
                    <input type="text" class="form-input" id="detailLabel" oninput="PM.updateStepFromDetail()">
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select class="form-input" id="detailType" onchange="PM.updateStepFromDetail()">
                        <option value="process">Process</option>
                        <option value="decision">Decision</option>
                        <option value="start">Terminal (Start/End)</option>
                        <option value="document">Document</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Colour</label>
                    <input type="color" class="form-input" id="detailColor" value="#0078d4" onchange="PM.updateStepFromDetail()" style="height: 36px; padding: 2px;">
                    <div class="pm-gradient-row">
                        <label class="pm-gradient-toggle">
                            <input type="checkbox" id="detailGradient" onchange="PM.updateStepFromDetail()">
                            <span>Gradient</span>
                        </label>
                        <input type="color" class="form-input pm-gradient-color2" id="detailColor2" value="#003a6b" onchange="PM.updateStepFromDetail()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-input" id="detailDescription" rows="5" oninput="PM.updateStepFromDetail()" placeholder="Add notes about this step..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="number" class="form-input" id="detailX" style="width: 50%;" onchange="PM.updateStepFromDetail()">
                        <input type="number" class="form-input" id="detailY" style="width: 50%;" onchange="PM.updateStepFromDetail()">
                    </div>
                </div>
                <hr style="margin: 16px 0; border: none; border-top: 1px solid #e0e0e0;">
                <h4 style="margin-bottom: 12px; font-size: 13px; color: #666;">Connectors</h4>
                <div id="detailConnectors"></div>
            </div>
            <!-- Lane detail body (shown when a lane is selected) -->
            <div class="pm-detail-body" id="detailBodyLane" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Label</label>
                    <input type="text" class="form-input" id="detailLaneLabel" oninput="PM.updateLaneFromDetail()" placeholder="e.g. HR / IT / Vendor">
                </div>
                <div class="form-group">
                    <label class="form-label">Colour</label>
                    <input type="color" class="form-input" id="detailLaneColor" value="#f5f7fa" onchange="PM.updateLaneFromDetail()" style="height: 36px; padding: 2px;">
                    <div class="pm-gradient-row">
                        <label class="pm-gradient-toggle">
                            <input type="checkbox" id="detailLaneGradient" onchange="PM.updateLaneFromDetail()">
                            <span>Gradient</span>
                        </label>
                        <input type="color" class="form-input pm-gradient-color2" id="detailLaneColor2" value="#cfd8dc" onchange="PM.updateLaneFromDetail()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Height</label>
                    <input type="number" class="form-input" id="detailLaneHeight" min="80" onchange="PM.updateLaneFromDetail()">
                </div>
                <div class="form-group">
                    <label class="form-label">Order (top to bottom)</label>
                    <input type="number" class="form-input" id="detailLaneOrder" min="0" onchange="PM.updateLaneFromDetail()">
                </div>
                <p style="font-size: 12px; color: #888; line-height: 1.5; margin-top: 8px;">
                    Drag the lane's left-edge header to reorder. Drag the bottom edge to resize. Drop a step into the band to assign it to this lane.
                </p>
            </div>
            <!-- Group detail body (shown when a group is selected) -->
            <div class="pm-detail-body" id="detailBodyGroup" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Label</label>
                    <input type="text" class="form-input" id="detailGroupLabel" oninput="PM.updateGroupFromDetail()" placeholder="e.g. Resolution phase">
                </div>
                <div class="form-group">
                    <label class="form-label">Colour</label>
                    <input type="color" class="form-input" id="detailGroupColor" value="#e3f2fd" onchange="PM.updateGroupFromDetail()" style="height: 36px; padding: 2px;">
                    <div class="pm-gradient-row">
                        <label class="pm-gradient-toggle">
                            <input type="checkbox" id="detailGroupGradient" onchange="PM.updateGroupFromDetail()">
                            <span>Gradient</span>
                        </label>
                        <input type="color" class="form-input pm-gradient-color2" id="detailGroupColor2" value="#b3d9f7" onchange="PM.updateGroupFromDetail()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="number" class="form-input" id="detailGroupX" style="width: 50%;" onchange="PM.updateGroupFromDetail()">
                        <input type="number" class="form-input" id="detailGroupY" style="width: 50%;" onchange="PM.updateGroupFromDetail()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Size</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="number" class="form-input" id="detailGroupW" style="width: 50%;" onchange="PM.updateGroupFromDetail()" min="80">
                        <input type="number" class="form-input" id="detailGroupH" style="width: 50%;" onchange="PM.updateGroupFromDetail()" min="60">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <!-- Export → Mermaid modal -->
    <div class="pm-modal-overlay" id="exportModal" style="display: none;" onclick="if (event.target === this) PM.closeExportModal()">
        <div class="pm-modal">
            <div class="pm-modal-header">
                <h3>Export — Mermaid flowchart</h3>
                <button class="pm-modal-close" onclick="PM.closeExportModal()" title="Close">&times;</button>
            </div>
            <div class="pm-modal-body">
                <p class="pm-modal-hint">Paste this markup into any Markdown editor that supports Mermaid (GitHub, GitLab, Notion, Confluence, Obsidian…). Lanes become <code>subgraph</code> blocks; auto-layout takes over from your hand-placed positions.</p>
                <textarea readonly id="exportText" class="pm-modal-textarea" spellcheck="false"></textarea>
                <div class="pm-modal-actions">
                    <button class="pm-modal-btn pm-modal-btn-primary" id="exportCopyBtn" onclick="PM.copyExport()">Copy</button>
                    <button class="pm-modal-btn" onclick="PM.closeExportModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>window.API_BASE = '../api/process-mapper/';</script>
    <script src="../assets/js/process-mapper.js"></script>
</body>
</html>
