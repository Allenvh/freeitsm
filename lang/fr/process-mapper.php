<?php
/**
 * Français (fr) — Process Mapper module strings.
 * Pilot translation for phase 1 — switching to French should produce visible UI changes.
 */
return [
    'title' => 'Cartographie des processus',

    'nav' => [
        'processes' => 'Processus',
        'settings'  => 'Paramètres',
        'help'      => 'Aide',
    ],

    'sidebar' => [
        'new_process'        => '+ Nouveau processus',
        'search_placeholder' => 'Rechercher des processus…',
        'no_processes_yet'   => 'Aucun processus',
    ],

    'toolbar' => [
        'process'   => 'Étape',
        'decision'  => 'Décision',
        'terminal'  => 'Début/Fin',
        'document'  => 'Document',
        'connect'   => 'Connecter',
        'group'     => 'Groupe',
        'lane'      => 'Couloir',
        'export'    => 'Exporter',
        'save'      => 'Enregistrer',
    ],

    'context' => [
        'create_new' => 'Créer…',
    ],

    'shapes' => [
        'rectangle'     => 'Rectangle',
        'rounded'       => 'Arrondi',
        'pill'          => 'Pilule',
        'circle'        => 'Cercle',
        'diamond'       => 'Losange',
        'parallelogram' => 'Parallélogramme',
        'trapezoid'     => 'Trapèze',
        'hexagon'       => 'Hexagone',
        'document'      => 'Document',
        'cylinder'      => 'Cylindre',
        'cloud'         => 'Nuage',
        'subroutine'    => 'Sous-routine',
    ],

    'settings' => [
        'title'            => 'Types d\'étape',
        'intro'            => 'Définissez les types de bloc disponibles dans la barre d\'outils de la cartographie des processus. Chaque type est un nom, une forme et une couleur.',
        'add_type'         => 'Ajouter un type',
        'col_order'        => 'Ordre',
        'col_shape'        => 'Forme',
        'col_name'         => 'Nom',
        'col_colour'       => 'Couleur',
        'col_active'       => 'Actif',
        'col_actions'      => 'Actions',
        'none'             => 'Aucun type d\'étape',
        'builtin'          => 'Intégré',
        'yes'              => 'Oui',
        'no'               => 'Non',
        'add_title'        => 'Ajouter un type d\'étape',
        'edit_title'       => 'Modifier le type d\'étape',
        'field_name'       => 'Nom',
        'field_shape'      => 'Forme',
        'field_colour'     => 'Couleur',
        'field_active'     => 'Actif',
        'name_placeholder' => 'ex. Base de données, Étape manuelle',
        'name_required'    => 'Le nom est requis',
        'saved'            => 'Enregistré',
        'deleted'          => 'Supprimé',
        'delete_confirm'   => 'Supprimer ce type d\'étape ?',
    ],

    'autosave' => [
        'label'   => 'Auto-enregistrement',
        'saved'   => 'Enregistré',
        'unsaved' => 'Non enregistré',
        'unsaved_changes' => 'Modifications non enregistrées',
        'saving'  => 'Enregistrement…',
        'failed'  => 'Échec de l\'enregistrement —',
        'retry'   => 'réessayer',
        'off'     => 'Auto-enregistrement désactivé',
        'tooltip' => 'Enregistre automatiquement quelques secondes après l\'arrêt des modifications',
    ],

    'detail' => [
        'step_title'   => 'Détails de l\'étape',
        'group_title'  => 'Détails du groupe',
        'lane_title'   => 'Détails du couloir',
        'label'        => 'Libellé',
        'type'         => 'Type',
        'colour'       => 'Couleur',
        'gradient'     => 'Dégradé',
        'description'  => 'Description',
        'position'     => 'Position',
        'size'         => 'Taille',
        'height'       => 'Hauteur',
        'order'        => 'Ordre (haut en bas)',
        'connectors'   => 'Connecteurs',
        'no_connectors'=> 'Aucun connecteur',
        'step_type' => [
            'process'  => 'Étape',
            'decision' => 'Décision',
            'terminal' => 'Début/Fin',
            'document' => 'Document',
        ],
        'step_description_placeholder' => 'Ajouter des notes sur cette étape…',
        'lane_label_placeholder'       => 'ex. RH / IT / Fournisseur',
        'group_label_placeholder'      => 'ex. Phase de résolution',
        'lane_hint'                    => 'Glissez l\'en-tête gauche du couloir pour réorganiser. Glissez le bord inférieur pour redimensionner. Déposez une étape dans la bande pour l\'affecter à ce couloir.',
    ],

    'export_modal' => [
        'title'  => 'Exporter — Diagramme Mermaid',
        'hint'   => 'Collez ce code dans n\'importe quel éditeur Markdown prenant en charge Mermaid (GitHub, GitLab, Notion, Confluence, Obsidian…). Les couloirs deviennent des blocs <code>subgraph</code> ; la mise en page automatique remplace vos positions.',
        'copy'   => 'Copier',
        'copied' => 'Copié ✓',
        'close'  => 'Fermer',
    ],

    'toast' => [
        'no_process_open' => 'Ouvrez ou créez d\'abord un processus',
        'saved'           => 'Enregistré',
        'save_failed'     => 'Échec de l\'enregistrement',
    ],
];
