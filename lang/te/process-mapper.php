<?php
/**
 * తెలుగు (te) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'ప్రక్రియ మాపర్',

    'nav' => [
        'processes' => 'ప్రక్రియలు',
        'settings'  => 'సెట్టింగ్‌లు',
        'help'      => 'సహాయం',
    ],

    'sidebar' => [
        'new_process'        => '+ కొత్త ప్రక్రియ',
        'search_placeholder' => 'ప్రక్రియలను శోధించండి…',
        'no_processes_yet'   => 'ఇంకా ప్రక్రియలు లేవు',
    ],

    'toolbar' => [
        'process'   => 'ప్రక్రియ',
        'decision'  => 'నిర్ణయం',
        'terminal'  => 'ప్రారంభం/ముగింపు',
        'document'  => 'పత్రం',
        'connect'   => 'కనెక్ట్',
        'group'     => 'సమూహం',
        'lane'      => 'లేన్',
        'export'    => 'ఎగుమతి',
        'save'      => 'భద్రపరచు',
    ],

    'context' => [
        'create_new' => 'కొత్తది సృష్టించు…',
    ],

    'shapes' => [
        'rectangle'     => 'దీర్ఘచతురస్రం',
        'rounded'       => 'గుండ్రని',
        'pill'          => 'పిల్',
        'circle'        => 'వృత్తం',
        'diamond'       => 'రాంబస్',
        'parallelogram' => 'సమాంతర చతుర్భుజం',
        'trapezoid'     => 'ట్రెపీజియం',
        'hexagon'       => 'షడ్భుజి',
        'document'      => 'పత్రం',
        'cylinder'      => 'స్థూపం',
        'cloud'         => 'క్లౌడ్',
        'subroutine'    => 'సబ్‌రూటీన్',
    ],

    'settings' => [
        'title'            => 'దశ రకాలు',
        'intro'            => 'ప్రక్రియ మాపర్ టూల్‌బార్‌లో అందుబాటులో ఉన్న బ్లాక్ రకాలను నిర్వచించండి. ప్రతి రకం ఒక పేరు, ఒక ఆకారం మరియు ఒక రంగు.',
        'add_type'         => 'రకాన్ని జోడించు',
        'col_order'        => 'క్రమం',
        'col_shape'        => 'ఆకారం',
        'col_name'         => 'పేరు',
        'col_colour'       => 'రంగు',
        'col_active'       => 'క్రియాశీలం',
        'col_actions'      => 'చర్యలు',
        'none'             => 'ఇంకా దశ రకాలు లేవు',
        'builtin'          => 'అంతర్నిర్మితం',
        'yes'              => 'అవును',
        'no'               => 'కాదు',
        'add_title'        => 'దశ రకాన్ని జోడించు',
        'edit_title'       => 'దశ రకాన్ని సవరించు',
        'field_name'       => 'పేరు',
        'field_shape'      => 'ఆకారం',
        'field_colour'     => 'రంగు',
        'field_active'     => 'క్రియాశీలం',
        'name_placeholder' => 'ఉదా. డేటాబేస్, మాన్యువల్ దశ',
        'name_required'    => 'పేరు అవసరం',
        'saved'            => 'భద్రపరచబడింది',
        'deleted'          => 'తొలగించబడింది',
        'delete_confirm'   => 'ఈ దశ రకాన్ని తొలగించాలా?',
    ],

    'autosave' => [
        'label'   => 'ఆటో సేవ్',
        'saved'   => 'భద్రపరచబడింది',
        'unsaved' => 'భద్రపరచబడలేదు',
        'unsaved_changes' => 'భద్రపరచబడని మార్పులు',
        'saving'  => 'భద్రపరుస్తోంది…',
        'failed'  => 'సేవ్ విఫలమైంది —',
        'retry'   => 'మళ్ళీ ప్రయత్నించు',
        'off'     => 'ఆటో సేవ్ ఆఫ్',
        'tooltip' => 'మీరు ఎడిటింగ్ ఆపిన కొన్ని సెకన్ల తర్వాత ఆటోమేటిక్‌గా సేవ్ చేస్తుంది',
    ],

    'detail' => [
        'step_title'   => 'దశ వివరాలు',
        'group_title'  => 'సమూహ వివరాలు',
        'lane_title'   => 'లేన్ వివరాలు',
        'label'        => 'లేబుల్',
        'type'         => 'రకం',
        'colour'       => 'రంగు',
        'gradient'     => 'గ్రేడియంట్',
        'description'  => 'వివరణ',
        'position'     => 'స్థానం',
        'size'         => 'పరిమాణం',
        'height'       => 'ఎత్తు',
        'order'        => 'క్రమం (పై నుండి క్రిందికి)',
        'connectors'   => 'కనెక్టర్లు',
        'no_connectors'=> 'కనెక్టర్లు లేవు',
        'step_type' => [
            'process'  => 'ప్రక్రియ',
            'decision' => 'నిర్ణయం',
            'terminal' => 'ప్రారంభం/ముగింపు',
            'document' => 'పత్రం',
        ],
        'step_description_placeholder' => 'ఈ దశ గురించి గమనికలను జోడించండి…',
        'lane_label_placeholder'       => 'ఉదా. HR / IT / విక్రేత',
        'group_label_placeholder'      => 'ఉదా. పరిష్కార దశ',
        'lane_hint'                    => 'క్రమాన్ని మార్చడానికి లేన్ యొక్క ఎడమ-అంచు హెడర్‌ను లాగండి. పరిమాణాన్ని మార్చడానికి దిగువ అంచును లాగండి. ఈ లేన్‌కు కేటాయించడానికి బ్యాండ్‌లో ఒక దశను వదలండి.',
    ],

    'export_modal' => [
        'title'  => 'ఎగుమతి — Mermaid ఫ్లోచార్ట్',
        'hint'   => 'Mermaid మద్దతు ఉన్న ఏదైనా Markdown ఎడిటర్‌లో ఈ మార్క్‌అప్‌ను అతికించండి (GitHub, GitLab, Notion, Confluence, Obsidian…). లేన్‌లు <code>subgraph</code> బ్లాక్‌లుగా మారతాయి; ఆటో-లేఅవుట్ మీ చేతితో ఇచ్చిన స్థానాలను భర్తీ చేస్తుంది.',
        'copy'   => 'కాపీ',
        'copied' => 'కాపీ చేయబడింది ✓',
        'close'  => 'మూసివేయి',
    ],

    'toast' => [
        'no_process_open' => 'మొదట ఒక ప్రక్రియను తెరవండి లేదా సృష్టించండి',
        'saved'           => 'భద్రపరచబడింది',
        'save_failed'     => 'భద్రపరచడం విఫలమైంది',
    ],
];
