<?php
/**
 * Forms Settings - Configure forms module settings
 */
session_start();
require_once '../../config.php';

if (!isset($_SESSION['analyst_id'])) {
    header('Location: ../../login.php');
    exit;
}

$analyst_name = $_SESSION['analyst_name'] ?? 'Analyst';
$current_page = 'settings';
$path_prefix = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Forms Settings</title>
    <link rel="stylesheet" href="../../assets/css/inbox.css">
    <style>
        .container {
            height: calc(100vh - 48px);
            overflow-y: auto;
            max-width: none;
            margin: 0;
            /* 30px top padding pushed the tab bar off the global
               header; tightened to match the other modules' settings
               pages (16px 30px 24px). */
            padding: 16px 30px 24px;
        }

        /* Teal theme for tabs */
        .tab:hover { color: #00897b; }
        .tab.active { color: #00897b; border-bottom-color: #00897b; }

        .section-header h2 {
            margin: 0 0 8px;
            font-size: 18px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
            color: #333;
        }

        .form-group small {
            display: block;
            margin-top: 4px;
            color: #888;
            font-size: 12px;
        }

        .alignment-options {
            display: flex;
            gap: 12px;
            max-width: 420px;
        }

        .alignment-option {
            flex: 1;
            padding: 16px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.15s;
            background: #fafafa;
        }

        .alignment-option:hover {
            border-color: #80cbc4;
            background: #f0f7f6;
        }

        .alignment-option.selected {
            border-color: #00897b;
            background: #e0f2f1;
        }

        .alignment-option svg {
            display: block;
            margin: 0 auto 6px;
            color: #666;
        }

        .alignment-option.selected svg {
            color: #00897b;
        }

        .alignment-option span {
            font-size: 13px;
            font-weight: 500;
            color: #666;
        }

        .alignment-option.selected span {
            color: #00897b;
            font-weight: 600;
        }

        .logo-preview {
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }

        .logo-preview-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #999;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .logo-preview img {
            display: block;
            max-width: 200px;
            height: auto;
            transition: margin 0.2s;
        }

        .logo-preview img.align-left { margin: 0 auto 0 0; }
        .logo-preview img.align-center { margin: 0 auto; }
        .logo-preview img.align-right { margin: 0 0 0 auto; }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary { background: #00897b; color: white; }
        .btn-primary:hover { background: #00695c; }

        /* AI tab — provider / model / key form. Matches the look of
           the Workflow + RFP Builder AI tabs so admins moving between
           modules see one consistent shape. */
        .ai-form { max-width: 640px; }
        .ai-form select,
        .ai-form input[type="text"] {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            background: white;
        }
        .ai-form select:focus,
        .ai-form input:focus { outline: none; border-color: #00897b; }
        .ai-form .toggle-row {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 500;
            color: #333;
        }
        .ai-form .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }
        .ai-form .toggle-switch input {
            opacity: 0; width: 0; height: 0;
        }
        .ai-form .toggle-slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #ccc;
            border-radius: 22px;
            transition: background 0.15s;
        }
        .ai-form .toggle-slider::before {
            content: '';
            position: absolute;
            height: 16px; width: 16px;
            left: 3px; bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: transform 0.15s;
        }
        .ai-form .toggle-switch input:checked + .toggle-slider { background: #00897b; }
        .ai-form .toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }
        .ai-form .ssl-warning {
            display: none;
            margin-top: 8px;
            padding: 10px 12px;
            background: #fff7e0;
            border: 1px solid #ffd86b;
            border-radius: 6px;
            font-size: 12px;
            color: #6b4f00;
        }
        .ai-form .ai-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: 22px;
        }
        .ai-form .btn-test {
            background: white;
            border: 1px solid #ddd;
            color: #333;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        .ai-form .btn-test:hover { background: #f5f5f5; border-color: #00897b; color: #00897b; }
        .ai-form .test-status { font-size: 13px; margin-left: 8px; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="tabs">
            <button class="tab active" data-tab="layout" onclick="switchTab('layout')">Layout</button>
            <button class="tab" data-tab="ai" onclick="switchTab('ai')">AI</button>
        </div>

        <!-- Layout Tab -->
        <div class="tab-content active" id="layout-tab">
            <div class="section-header">
                <h2>Layout Settings</h2>
            </div>
            <p style="color: #666; margin-bottom: 24px;">Configure how forms appear when users fill them in and in the form preview.</p>

            <div class="form-group">
                <label>Logo Alignment</label>
                <small>Controls the position of the company logo on forms.</small>
                <div class="alignment-options" style="margin-top: 10px;">
                    <div class="alignment-option" data-align="left" onclick="selectAlignment('left')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>
                        <span>Left</span>
                    </div>
                    <div class="alignment-option selected" data-align="center" onclick="selectAlignment('center')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>
                        <span>Centre</span>
                    </div>
                    <div class="alignment-option" data-align="right" onclick="selectAlignment('right')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="10" x2="7" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="7" y2="18"></line></svg>
                        <span>Right</span>
                    </div>
                </div>
            </div>

            <div class="logo-preview">
                <div class="logo-preview-label">Preview</div>
                <img id="logoPreview" src="../../assets/images/CompanyLogo.png" alt="Company Logo" class="align-center">
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" onclick="saveSettings()">Save</button>
            </div>
        </div>

        <!-- AI Tab — per-module billing. Provider, model, key + test
             connection. Saved settings drive api/forms/ai_generate.php. -->
        <div class="tab-content" id="ai-tab">
            <div class="section-header">
                <h2>AI</h2>
            </div>
            <p style="color: #666; margin-bottom: 24px; max-width: 720px;">
                Configure the AI provider used by the form builder's AI Assist. These settings are billed against the key you supply here, so the Forms feature's usage stays separate from other modules' AI usage on your provider's dashboard.
            </p>

            <form id="formsAiForm" class="ai-form" autocomplete="off" onsubmit="event.preventDefault(); FormsAi.save();">
                <div class="form-group">
                    <label for="formsProvider">Provider</label>
                    <select id="formsProvider" onchange="FormsAi.onProviderChange()">
                        <option value="anthropic">Anthropic (Claude)</option>
                        <option value="openai">OpenAI (GPT)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="formsModel">Model</label>
                    <input type="text" id="formsModel" list="formsModelOptions" placeholder="e.g. claude-sonnet-4-6" autocomplete="off">
                    <datalist id="formsModelOptions"></datalist>
                    <small>You can pick a model from the suggestions or paste any model ID supported by your provider.</small>
                </div>

                <div class="form-group">
                    <label for="formsApiKey">API key</label>
                    <input type="text" id="formsApiKey" autocomplete="off" placeholder="Paste your API key">
                    <small>
                        Stored encrypted at rest. Anthropic: <a href="https://console.anthropic.com/settings/keys" target="_blank" rel="noopener" style="color:#00897b;">console.anthropic.com</a>.
                        OpenAI: <a href="https://platform.openai.com/api-keys" target="_blank" rel="noopener" style="color:#00897b;">platform.openai.com</a>.
                    </small>
                </div>

                <div class="form-group">
                    <label class="toggle-row">
                        <span class="toggle-switch">
                            <input type="checkbox" id="formsVerifySsl" checked onchange="FormsAi.onVerifySslChange()">
                            <span class="toggle-slider"></span>
                        </span>
                        Verify SSL certificate
                    </label>
                    <small>Turn off only if your network's proxy is doing TLS inspection with a self-signed CA.</small>
                    <div id="formsSslWarning" class="ssl-warning">
                        <strong>Warning:</strong> SSL verification is off &mdash; outbound traffic to the AI provider isn't being verified. Only use this on a controlled network with a known proxy.
                    </div>
                </div>

                <div class="ai-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn-test" id="formsTestBtn" onclick="FormsAi.testKey()">Test connection</button>
                    <span id="formsTestStatus" class="test-status"></span>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast notification -->

    <script>
        const API_BASE = '../../api/forms/';
        let currentAlignment = 'center';

        function switchTab(tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            const btn = document.querySelector('.tab[data-tab="' + tab + '"]');
            if (btn) btn.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tab + '-tab').classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
            FormsAi.load();
        });

        // ===== AI tab — per-module billing =====
        // Provider / model / API key / verify-SSL form for the form
        // builder's AI Assist. Mirrors the Workflow + RFP Builder AI
        // settings pattern. Saves to forms_ai_* keys in system_settings
        // so the spend lands against the Forms feature on the provider
        // dashboard.
        const FORMS_AI_MODEL_OPTIONS = {
            anthropic: [
                { id: 'claude-opus-4-7',           label: 'Opus 4.7 — most capable' },
                { id: 'claude-sonnet-4-6',         label: 'Sonnet 4.6 — recommended (best balance)' },
                { id: 'claude-haiku-4-5-20251001', label: 'Haiku 4.5 — fastest and cheapest' },
            ],
            openai: [
                { id: 'gpt-4.1',     label: 'GPT-4.1 — most capable' },
                { id: 'gpt-4o',      label: 'GPT-4o — recommended default' },
                { id: 'gpt-4o-mini', label: 'GPT-4o mini — fastest and cheapest' },
            ],
        };
        const FORMS_AI_DEFAULT_MODEL = {
            anthropic: 'claude-sonnet-4-6',
            openai:    'gpt-4o',
        };

        const FormsAi = (() => {
            function escHtml(s) {
                const d = document.createElement('div');
                d.textContent = s == null ? '' : String(s);
                return d.innerHTML;
            }
            function setStatus(msg, kind) {
                const el = document.getElementById('formsTestStatus');
                el.textContent = msg;
                el.style.color = kind === 'success' ? '#065f46'
                               : kind === 'error'   ? '#d13438'
                               : kind === 'busy'    ? '#b45309'
                               :                      '#555';
            }
            function refreshModelOptions() {
                const provider = document.getElementById('formsProvider').value;
                const list = document.getElementById('formsModelOptions');
                const opts = FORMS_AI_MODEL_OPTIONS[provider] || [];
                list.innerHTML = opts.map(m => `<option value="${escHtml(m.id)}">${escHtml(m.label)}</option>`).join('');
            }
            function onProviderChange() {
                refreshModelOptions();
                const provider = document.getElementById('formsProvider').value;
                const modelEl  = document.getElementById('formsModel');
                const known    = (FORMS_AI_MODEL_OPTIONS[provider] || []).map(m => m.id);
                if (!modelEl.value || !known.includes(modelEl.value)) {
                    modelEl.value = FORMS_AI_DEFAULT_MODEL[provider];
                }
            }
            function onVerifySslChange() {
                const checked = document.getElementById('formsVerifySsl').checked;
                document.getElementById('formsSslWarning').style.display = checked ? 'none' : 'block';
            }

            async function load() {
                try {
                    const r = await fetch(API_BASE + 'get_ai_settings.php', { credentials: 'same-origin' });
                    const d = await r.json();
                    if (!d.success) throw new Error(d.error || 'Load failed');
                    const s = d.settings || {};
                    document.getElementById('formsProvider').value = s.forms_ai_provider || 'anthropic';
                    refreshModelOptions();
                    document.getElementById('formsModel').value =
                        s.forms_ai_model || FORMS_AI_DEFAULT_MODEL[document.getElementById('formsProvider').value];
                    document.getElementById('formsApiKey').value = s.forms_ai_api_key || '';
                    document.getElementById('formsApiKey').placeholder = d.has_key
                        ? 'Key is saved — paste a new one to change it'
                        : 'Paste your API key';
                    document.getElementById('formsVerifySsl').checked = (s.forms_ai_verify_ssl !== '0');
                    onVerifySslChange();
                } catch (e) {
                    setStatus('Could not load settings: ' + e.message, 'error');
                }
            }

            async function save() {
                const payload = {
                    provider:   document.getElementById('formsProvider').value,
                    model:      document.getElementById('formsModel').value.trim(),
                    api_key:    document.getElementById('formsApiKey').value,
                    verify_ssl: document.getElementById('formsVerifySsl').checked ? '1' : '0',
                };
                try {
                    const r = await fetch(API_BASE + 'save_ai_settings.php', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                    });
                    const d = await r.json();
                    if (!d.success) throw new Error(d.error || 'Save failed');
                    showToast('AI settings saved', 'success');
                    setStatus('', '');
                    await load();
                } catch (e) {
                    showToast(e.message, 'error');
                }
            }

            async function testKey() {
                const btn = document.getElementById('formsTestBtn');
                const payload = {
                    provider:   document.getElementById('formsProvider').value,
                    model:      document.getElementById('formsModel').value.trim(),
                    api_key:    document.getElementById('formsApiKey').value,
                    verify_ssl: document.getElementById('formsVerifySsl').checked ? '1' : '0',
                };
                if (!payload.model) { setStatus('Pick a model first', 'error'); return; }
                btn.disabled = true;
                setStatus('Testing…', 'busy');
                try {
                    const r = await fetch(API_BASE + 'test_ai_key.php', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                    });
                    const d = await r.json();
                    if (!d.success) throw new Error(d.error || 'Failed');
                    const tokens = (d.tokens_in != null && d.tokens_out != null)
                        ? ` — ${d.tokens_in} in / ${d.tokens_out} out tokens` : '';
                    setStatus(`OK — ${d.provider} · ${d.model} · ${d.latency_ms}ms${tokens}`, 'success');
                } catch (e) {
                    setStatus('Failed: ' + e.message, 'error');
                } finally {
                    btn.disabled = false;
                }
            }

            return { load, save, testKey, onProviderChange, onVerifySslChange };
        })();

        function selectAlignment(align) {
            currentAlignment = align;
            document.querySelectorAll('.alignment-option').forEach(el => el.classList.remove('selected'));
            document.querySelector(`.alignment-option[data-align="${align}"]`).classList.add('selected');
            // Update preview
            const img = document.getElementById('logoPreview');
            img.className = 'align-' + align;
        }

        async function loadSettings() {
            try {
                const res = await fetch(API_BASE + 'get_settings.php');
                const data = await res.json();
                if (data.success && data.settings) {
                    const align = data.settings.logo_alignment || 'center';
                    selectAlignment(align);
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function saveSettings() {
            try {
                const res = await fetch(API_BASE + 'save_settings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ settings: { logo_alignment: currentAlignment } })
                });
                const data = await res.json();

                if (data.success) {
                    showToast('Settings saved', 'success');
                } else {
                    showToast('Error: ' + data.error, 'error');
                }
            } catch (e) {
                showToast('Failed to save settings', 'error');
            }
        }
    </script>
</body>
</html>
