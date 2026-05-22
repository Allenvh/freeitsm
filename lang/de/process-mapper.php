<?php
/**
 * Deutsch (de) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'Prozess-Mapper',

    'nav' => [
        'processes' => 'Prozesse',
        'settings'  => 'Einstellungen',
        'help'      => 'Hilfe',
    ],

    'sidebar' => [
        'new_process'        => '+ Neuer Prozess',
        'search_placeholder' => 'Prozesse suchen…',
        'no_processes_yet'   => 'Noch keine Prozesse',
    ],

    'toolbar' => [
        'process'   => 'Prozess',
        'decision'  => 'Entscheidung',
        'terminal'  => 'Start/Ende',
        'document'  => 'Dokument',
        'connect'   => 'Verbinden',
        'group'     => 'Gruppe',
        'lane'      => 'Schwimmbahn',
        'export'    => 'Exportieren',
        'save'      => 'Speichern',
    ],

    'context' => [
        'create_new' => 'Neu erstellen…',
    ],

    'shapes' => [
        'rectangle'     => 'Rechteck',
        'rounded'       => 'Abgerundet',
        'pill'          => 'Pille',
        'circle'        => 'Kreis',
        'diamond'       => 'Raute',
        'parallelogram' => 'Parallelogramm',
        'trapezoid'     => 'Trapez',
        'hexagon'       => 'Sechseck',
        'document'      => 'Dokument',
        'cylinder'      => 'Zylinder',
        'cloud'         => 'Wolke',
        'subroutine'    => 'Unterprogramm',
    ],

    'settings' => [
        'title'            => 'Schritttypen',
        'intro'            => 'Legen Sie die Blocktypen fest, die in der Werkzeugleiste des Prozess-Mappers verfügbar sind. Jeder Typ besteht aus einem Namen, einer Form und einer Farbe.',
        'add_type'         => 'Typ hinzufügen',
        'col_order'        => 'Reihenfolge',
        'col_shape'        => 'Form',
        'col_name'         => 'Name',
        'col_colour'       => 'Farbe',
        'col_active'       => 'Aktiv',
        'col_actions'      => 'Aktionen',
        'none'             => 'Noch keine Schritttypen',
        'builtin'          => 'Integriert',
        'yes'              => 'Ja',
        'no'               => 'Nein',
        'add_title'        => 'Schritttyp hinzufügen',
        'edit_title'       => 'Schritttyp bearbeiten',
        'field_name'       => 'Name',
        'field_shape'      => 'Form',
        'field_colour'     => 'Farbe',
        'field_active'     => 'Aktiv',
        'name_placeholder' => 'z. B. Datenbank, Manueller Schritt',
        'name_required'    => 'Name ist erforderlich',
        'saved'            => 'Gespeichert',
        'deleted'          => 'Gelöscht',
        'delete_confirm'   => 'Diesen Schritttyp löschen?',
    ],

    'autosave' => [
        'label'   => 'Automatisch speichern',
        'saved'   => 'Gespeichert',
        'unsaved' => 'Nicht gespeichert',
        'unsaved_changes' => 'Nicht gespeicherte Änderungen',
        'saving'  => 'Speichern…',
        'failed'  => 'Speichern fehlgeschlagen —',
        'retry'   => 'erneut versuchen',
        'off'     => 'Automatisches Speichern aus',
        'tooltip' => 'Speichert automatisch ein paar Sekunden nach der letzten Änderung',
    ],

    'detail' => [
        'step_title'   => 'Schritt-Details',
        'group_title'  => 'Gruppen-Details',
        'lane_title'   => 'Schwimmbahn-Details',
        'label'        => 'Bezeichnung',
        'type'         => 'Typ',
        'colour'       => 'Farbe',
        'gradient'     => 'Verlauf',
        'description'  => 'Beschreibung',
        'position'     => 'Position',
        'size'         => 'Größe',
        'height'       => 'Höhe',
        'order'        => 'Reihenfolge (oben nach unten)',
        'connectors'   => 'Verbindungen',
        'no_connectors'=> 'Keine Verbindungen',
        'step_type' => [
            'process'  => 'Prozess',
            'decision' => 'Entscheidung',
            'terminal' => 'Start/Ende',
            'document' => 'Dokument',
        ],
        'step_description_placeholder' => 'Notizen zu diesem Schritt hinzufügen…',
        'lane_label_placeholder'       => 'z. B. HR / IT / Lieferant',
        'group_label_placeholder'      => 'z. B. Lösungsphase',
        'lane_hint'                    => 'Ziehen Sie den linken Rand der Schwimmbahn, um sie neu zu ordnen. Ziehen Sie den unteren Rand, um die Größe zu ändern. Ziehen Sie einen Schritt in die Bahn, um ihn dieser zuzuweisen.',
    ],

    'export_modal' => [
        'title'  => 'Export — Mermaid-Flussdiagramm',
        'hint'   => 'Fügen Sie diesen Code in einen beliebigen Markdown-Editor mit Mermaid-Unterstützung ein (GitHub, GitLab, Notion, Confluence, Obsidian…). Schwimmbahnen werden zu <code>subgraph</code>-Blöcken; das automatische Layout ersetzt Ihre handgesetzten Positionen.',
        'copy'   => 'Kopieren',
        'copied' => 'Kopiert ✓',
        'close'  => 'Schließen',
    ],

    'toast' => [
        'no_process_open' => 'Öffnen oder erstellen Sie zuerst einen Prozess',
        'saved'           => 'Gespeichert',
        'save_failed'     => 'Speichern fehlgeschlagen',
    ],
];
