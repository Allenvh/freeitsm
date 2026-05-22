<?php
/**
 * മലയാളം (ml) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'പ്രക്രിയ മാപ്പർ',

    'nav' => [
        'processes' => 'പ്രക്രിയകൾ',
        'settings'  => 'ക്രമീകരണങ്ങൾ',
        'help'      => 'സഹായം',
    ],

    'sidebar' => [
        'new_process'        => '+ പുതിയ പ്രക്രിയ',
        'search_placeholder' => 'പ്രക്രിയകൾ തിരയുക…',
        'no_processes_yet'   => 'ഇതുവരെ പ്രക്രിയകളില്ല',
    ],

    'toolbar' => [
        'process'   => 'പ്രക്രിയ',
        'decision'  => 'തീരുമാനം',
        'terminal'  => 'തുടക്കം/അവസാനം',
        'document'  => 'പ്രമാണം',
        'connect'   => 'ബന്ധിപ്പിക്കുക',
        'group'     => 'ഗ്രൂപ്പ്',
        'lane'      => 'ലെയ്ൻ',
        'export'    => 'കയറ്റുമതി',
        'save'      => 'സംരക്ഷിക്കുക',
    ],

    'context' => [
        'create_new' => 'പുതിയത് സൃഷ്ടിക്കുക…',
    ],

    'shapes' => [
        'rectangle'     => 'ദീർഘചതുരം',
        'rounded'       => 'വൃത്താകൃതി',
        'pill'          => 'പിൽ',
        'circle'        => 'വൃത്തം',
        'diamond'       => 'സമചതുർഭുജം',
        'parallelogram' => 'സമാന്തരികം',
        'trapezoid'     => 'ലംബകം',
        'hexagon'       => 'ഷഡ്ഭുജം',
        'document'      => 'പ്രമാണം',
        'cylinder'      => 'സിലിണ്ടർ',
        'cloud'         => 'ക്ലൗഡ്',
        'subroutine'    => 'സബ്‌റൂട്ടീൻ',
    ],

    'settings' => [
        'title'            => 'ഘട്ട തരങ്ങൾ',
        'intro'            => 'പ്രക്രിയ മാപ്പർ ടൂൾബാറിൽ ലഭ്യമായ ബ്ലോക്ക് തരങ്ങൾ നിർവചിക്കുക. ഓരോ തരവും ഒരു പേര്, ഒരു രൂപം, ഒരു നിറം എന്നിവയാണ്.',
        'add_type'         => 'തരം ചേർക്കുക',
        'col_order'        => 'ക്രമം',
        'col_shape'        => 'രൂപം',
        'col_name'         => 'പേര്',
        'col_colour'       => 'നിറം',
        'col_active'       => 'സജീവം',
        'col_actions'      => 'പ്രവർത്തനങ്ങൾ',
        'none'             => 'ഇതുവരെ ഘട്ട തരങ്ങളില്ല',
        'builtin'          => 'അന്തർനിർമിതം',
        'yes'              => 'അതെ',
        'no'               => 'അല്ല',
        'add_title'        => 'ഘട്ട തരം ചേർക്കുക',
        'edit_title'       => 'ഘട്ട തരം എഡിറ്റ് ചെയ്യുക',
        'field_name'       => 'പേര്',
        'field_shape'      => 'രൂപം',
        'field_colour'     => 'നിറം',
        'field_active'     => 'സജീവം',
        'name_placeholder' => 'ഉദാ. ഡാറ്റാബേസ്, മാനുവൽ ഘട്ടം',
        'name_required'    => 'പേര് ആവശ്യമാണ്',
        'saved'            => 'സംരക്ഷിച്ചു',
        'deleted'          => 'ഇല്ലാതാക്കി',
        'delete_confirm'   => 'ഈ ഘട്ട തരം ഇല്ലാതാക്കണോ?',
    ],

    'autosave' => [
        'label'   => 'ഓട്ടോ-സേവ്',
        'saved'   => 'സംരക്ഷിച്ചു',
        'unsaved' => 'സംരക്ഷിച്ചിട്ടില്ല',
        'unsaved_changes' => 'സംരക്ഷിക്കാത്ത മാറ്റങ്ങൾ',
        'saving'  => 'സംരക്ഷിക്കുന്നു…',
        'failed'  => 'സംരക്ഷണം പരാജയപ്പെട്ടു —',
        'retry'   => 'വീണ്ടും ശ്രമിക്കുക',
        'off'     => 'ഓട്ടോ-സേവ് ഓഫ്',
        'tooltip' => 'എഡിറ്റിംഗ് നിർത്തിയ ഏതാനും സെക്കൻഡുകൾക്ക് ശേഷം സ്വയം സംരക്ഷിക്കുന്നു',
    ],

    'detail' => [
        'step_title'   => 'ഘട്ടത്തിന്റെ വിശദാംശങ്ങൾ',
        'group_title'  => 'ഗ്രൂപ്പിന്റെ വിശദാംശങ്ങൾ',
        'lane_title'   => 'ലെയ്നിന്റെ വിശദാംശങ്ങൾ',
        'label'        => 'ലേബൽ',
        'type'         => 'തരം',
        'colour'       => 'നിറം',
        'gradient'     => 'ഗ്രേഡിയന്റ്',
        'description'  => 'വിവരണം',
        'position'     => 'സ്ഥാനം',
        'size'         => 'വലുപ്പം',
        'height'       => 'ഉയരം',
        'order'        => 'ക്രമം (മുകളിൽ നിന്ന് താഴേക്ക്)',
        'connectors'   => 'കണക്ടറുകൾ',
        'no_connectors'=> 'കണക്ടറുകൾ ഇല്ല',
        'step_type' => [
            'process'  => 'പ്രക്രിയ',
            'decision' => 'തീരുമാനം',
            'terminal' => 'തുടക്കം/അവസാനം',
            'document' => 'പ്രമാണം',
        ],
        'step_description_placeholder' => 'ഈ ഘട്ടത്തെക്കുറിച്ച് കുറിപ്പുകൾ ചേർക്കുക…',
        'lane_label_placeholder'       => 'ഉദാ. HR / IT / വെണ്ടർ',
        'group_label_placeholder'      => 'ഉദാ. പരിഹാര ഘട്ടം',
        'lane_hint'                    => 'പുനഃക്രമീകരിക്കാൻ ലെയ്നിന്റെ ഇടത് അരിക് തലക്കെട്ട് വലിച്ചിടുക. വലുപ്പം മാറ്റാൻ താഴത്തെ അരിക് വലിച്ചിടുക. ഈ ലെയ്നിലേക്ക് അസൈൻ ചെയ്യാൻ ബാൻഡിൽ ഒരു ഘട്ടം ഇടുക.',
    ],

    'export_modal' => [
        'title'  => 'കയറ്റുമതി — Mermaid ഫ്ലോചാർട്ട്',
        'hint'   => 'Mermaid പിന്തുണയ്ക്കുന്ന ഏതെങ്കിലും Markdown എഡിറ്ററിൽ ഈ മാർക്കപ്പ് ഒട്ടിക്കുക (GitHub, GitLab, Notion, Confluence, Obsidian…). ലെയ്നുകൾ <code>subgraph</code> ബ്ലോക്കുകളായി മാറുന്നു; ഓട്ടോ-ലേഔട്ട് നിങ്ങൾ കൈകൊണ്ട് വച്ച സ്ഥാനങ്ങളെ മാറ്റി പകരം വയ്ക്കുന്നു.',
        'copy'   => 'പകർത്തുക',
        'copied' => 'പകർത്തി ✓',
        'close'  => 'അടയ്ക്കുക',
    ],

    'toast' => [
        'no_process_open' => 'ആദ്യം ഒരു പ്രക്രിയ തുറക്കുകയോ സൃഷ്ടിക്കുകയോ ചെയ്യുക',
        'saved'           => 'സംരക്ഷിച്ചു',
        'save_failed'     => 'സംരക്ഷിക്കാൻ കഴിഞ്ഞില്ല',
    ],
];
