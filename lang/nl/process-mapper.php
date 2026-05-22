<?php
/**
 * Nederlands (nl) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'Processchema',

    'nav' => [
        'processes' => 'Processen',
        'settings'  => 'Instellingen',
        'help'      => 'Help',
    ],

    'sidebar' => [
        'new_process'        => '+ Nieuw proces',
        'search_placeholder' => 'Processen zoeken…',
        'no_processes_yet'   => 'Nog geen processen',
    ],

    'toolbar' => [
        'process'   => 'Proces',
        'decision'  => 'Beslissing',
        'terminal'  => 'Start/Einde',
        'document'  => 'Document',
        'connect'   => 'Verbinden',
        'group'     => 'Groep',
        'lane'      => 'Baan',
        'export'    => 'Exporteren',
        'save'      => 'Opslaan',
    ],

    'context' => [
        'create_new' => 'Nieuw maken…',
    ],

    'shapes' => [
        'rectangle'     => 'Rechthoek',
        'rounded'       => 'Afgerond',
        'pill'          => 'Pil',
        'circle'        => 'Cirkel',
        'diamond'       => 'Ruit',
        'parallelogram' => 'Parallellogram',
        'trapezoid'     => 'Trapezium',
        'hexagon'       => 'Zeshoek',
        'document'      => 'Document',
        'cylinder'      => 'Cilinder',
        'cloud'         => 'Wolk',
        'subroutine'    => 'Subroutine',
    ],

    'settings' => [
        'title'            => 'Staptypen',
        'intro'            => 'Definieer de bloktypen die beschikbaar zijn in de werkbalk van het Processchema. Elk type bestaat uit een naam, een vorm en een kleur.',
        'add_type'         => 'Type toevoegen',
        'col_order'        => 'Volgorde',
        'col_shape'        => 'Vorm',
        'col_name'         => 'Naam',
        'col_colour'       => 'Kleur',
        'col_active'       => 'Actief',
        'col_actions'      => 'Acties',
        'none'             => 'Nog geen staptypen',
        'builtin'          => 'Ingebouwd',
        'yes'              => 'Ja',
        'no'               => 'Nee',
        'add_title'        => 'Staptype toevoegen',
        'edit_title'       => 'Staptype bewerken',
        'field_name'       => 'Naam',
        'field_shape'      => 'Vorm',
        'field_colour'     => 'Kleur',
        'field_active'     => 'Actief',
        'name_placeholder' => 'bijv. Database, Handmatige stap',
        'name_required'    => 'Naam is verplicht',
        'saved'            => 'Opgeslagen',
        'deleted'          => 'Verwijderd',
        'delete_confirm'   => 'Dit staptype verwijderen?',
    ],

    'autosave' => [
        'label'   => 'Automatisch opslaan',
        'saved'   => 'Opgeslagen',
        'unsaved' => 'Niet opgeslagen',
        'unsaved_changes' => 'Niet-opgeslagen wijzigingen',
        'saving'  => 'Opslaan…',
        'failed'  => 'Opslaan mislukt —',
        'retry'   => 'opnieuw proberen',
        'off'     => 'Automatisch opslaan uit',
        'tooltip' => 'Slaat automatisch op enkele seconden na de laatste wijziging',
    ],

    'detail' => [
        'step_title'   => 'Stapdetails',
        'group_title'  => 'Groepsdetails',
        'lane_title'   => 'Baandetails',
        'label'        => 'Label',
        'type'         => 'Type',
        'colour'       => 'Kleur',
        'gradient'     => 'Verloop',
        'description'  => 'Beschrijving',
        'position'     => 'Positie',
        'size'         => 'Grootte',
        'height'       => 'Hoogte',
        'order'        => 'Volgorde (boven naar beneden)',
        'connectors'   => 'Verbindingen',
        'no_connectors'=> 'Geen verbindingen',
        'step_type' => [
            'process'  => 'Proces',
            'decision' => 'Beslissing',
            'terminal' => 'Start/Einde',
            'document' => 'Document',
        ],
        'step_description_placeholder' => 'Notities over deze stap toevoegen…',
        'lane_label_placeholder'       => 'bv. HR / IT / Leverancier',
        'group_label_placeholder'      => 'bv. Oplossingsfase',
        'lane_hint'                    => 'Sleep de linker kop van de baan om te herordenen. Sleep de onderrand om het formaat aan te passen. Laat een stap in de baan vallen om hem aan deze baan toe te wijzen.',
    ],

    'export_modal' => [
        'title'  => 'Exporteren — Mermaid-stroomschema',
        'hint'   => 'Plak deze code in een willekeurige Markdown-editor die Mermaid ondersteunt (GitHub, GitLab, Notion, Confluence, Obsidian…). Banen worden <code>subgraph</code>-blokken; de automatische lay-out vervangt uw handmatige posities.',
        'copy'   => 'Kopiëren',
        'copied' => 'Gekopieerd ✓',
        'close'  => 'Sluiten',
    ],

    'toast' => [
        'no_process_open' => 'Open of maak eerst een proces',
        'saved'           => 'Opgeslagen',
        'save_failed'     => 'Opslaan mislukt',
    ],
];
