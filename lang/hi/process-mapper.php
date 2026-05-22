<?php
/**
 * हिन्दी (hi) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'प्रक्रिया मैपर',

    'nav' => [
        'processes' => 'प्रक्रियाएँ',
        'settings'  => 'सेटिंग्स',
        'help'      => 'सहायता',
    ],

    'sidebar' => [
        'new_process'        => '+ नई प्रक्रिया',
        'search_placeholder' => 'प्रक्रियाएँ खोजें…',
        'no_processes_yet'   => 'अभी कोई प्रक्रिया नहीं',
    ],

    'toolbar' => [
        'process'   => 'प्रक्रिया',
        'decision'  => 'निर्णय',
        'terminal'  => 'प्रारंभ/अंत',
        'document'  => 'दस्तावेज़',
        'connect'   => 'जोड़ें',
        'group'     => 'समूह',
        'lane'      => 'लेन',
        'export'    => 'निर्यात',
        'save'      => 'सहेजें',
    ],

    'context' => [
        'create_new' => 'नया बनाएँ…',
    ],

    'shapes' => [
        'rectangle'     => 'आयत',
        'rounded'       => 'गोलाकार',
        'pill'          => 'पिल',
        'circle'        => 'वृत्त',
        'diamond'       => 'समचतुर्भुज',
        'parallelogram' => 'समांतर चतुर्भुज',
        'trapezoid'     => 'समलंब',
        'hexagon'       => 'षट्भुज',
        'document'      => 'दस्तावेज़',
        'cylinder'      => 'बेलन',
        'cloud'         => 'क्लाउड',
        'subroutine'    => 'सबरूटीन',
    ],

    'settings' => [
        'title'            => 'चरण प्रकार',
        'intro'            => 'प्रक्रिया मैपर टूलबार में उपलब्ध ब्लॉक प्रकार परिभाषित करें। प्रत्येक प्रकार एक नाम, एक आकृति और एक रंग है।',
        'add_type'         => 'प्रकार जोड़ें',
        'col_order'        => 'क्रम',
        'col_shape'        => 'आकृति',
        'col_name'         => 'नाम',
        'col_colour'       => 'रंग',
        'col_active'       => 'सक्रिय',
        'col_actions'      => 'क्रियाएँ',
        'none'             => 'अभी कोई चरण प्रकार नहीं',
        'builtin'          => 'अंतर्निहित',
        'yes'              => 'हाँ',
        'no'               => 'नहीं',
        'add_title'        => 'चरण प्रकार जोड़ें',
        'edit_title'       => 'चरण प्रकार संपादित करें',
        'field_name'       => 'नाम',
        'field_shape'      => 'आकृति',
        'field_colour'     => 'रंग',
        'field_active'     => 'सक्रिय',
        'name_placeholder' => 'जैसे डेटाबेस, मैनुअल चरण',
        'name_required'    => 'नाम आवश्यक है',
        'saved'            => 'सहेजा गया',
        'deleted'          => 'हटाया गया',
        'delete_confirm'   => 'इस चरण प्रकार को हटाएँ?',
    ],

    'autosave' => [
        'label'   => 'स्वतः सहेजें',
        'saved'   => 'सहेजा गया',
        'unsaved' => 'सहेजा नहीं गया',
        'unsaved_changes' => 'असहेजे परिवर्तन',
        'saving'  => 'सहेजा जा रहा है…',
        'failed'  => 'सहेजना विफल —',
        'retry'   => 'पुनः प्रयास करें',
        'off'     => 'स्वतः सहेजें बंद',
        'tooltip' => 'संपादन रुकने के कुछ सेकंड बाद स्वतः सहेजता है',
    ],

    'detail' => [
        'step_title'   => 'चरण विवरण',
        'group_title'  => 'समूह विवरण',
        'lane_title'   => 'लेन विवरण',
        'label'        => 'लेबल',
        'type'         => 'प्रकार',
        'colour'       => 'रंग',
        'gradient'     => 'ग्रेडिएंट',
        'description'  => 'विवरण',
        'position'     => 'स्थिति',
        'size'         => 'आकार',
        'height'       => 'ऊँचाई',
        'order'        => 'क्रम (ऊपर से नीचे)',
        'connectors'   => 'कनेक्टर',
        'no_connectors'=> 'कोई कनेक्टर नहीं',
        'step_type' => [
            'process'  => 'प्रक्रिया',
            'decision' => 'निर्णय',
            'terminal' => 'प्रारंभ/अंत',
            'document' => 'दस्तावेज़',
        ],
        'step_description_placeholder' => 'इस चरण के बारे में नोट्स जोड़ें…',
        'lane_label_placeholder'       => 'जैसे HR / IT / विक्रेता',
        'group_label_placeholder'      => 'जैसे समाधान चरण',
        'lane_hint'                    => 'पुनः क्रमित करने के लिए लेन का बायाँ हेडर खींचें। आकार बदलने के लिए निचला किनारा खींचें। इस लेन को सौंपने के लिए बैंड में एक चरण छोड़ें।',
    ],

    'export_modal' => [
        'title'  => 'निर्यात — Mermaid फ़्लोचार्ट',
        'hint'   => 'इस मार्कअप को Mermaid का समर्थन करने वाले किसी भी Markdown संपादक में चिपकाएँ (GitHub, GitLab, Notion, Confluence, Obsidian…)। लेन <code>subgraph</code> ब्लॉक बन जाती हैं; ऑटो-लेआउट आपकी हाथ से दी गई स्थितियों को बदल देता है।',
        'copy'   => 'कॉपी करें',
        'copied' => 'कॉपी हो गया ✓',
        'close'  => 'बंद करें',
    ],

    'toast' => [
        'no_process_open' => 'पहले कोई प्रक्रिया खोलें या बनाएँ',
        'saved'           => 'सहेजा गया',
        'save_failed'     => 'सहेजना विफल',
    ],
];
