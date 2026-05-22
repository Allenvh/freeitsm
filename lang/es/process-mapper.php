<?php
/**
 * Español (es) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'Mapeador de procesos',

    'nav' => [
        'processes' => 'Procesos',
        'settings'  => 'Configuración',
        'help'      => 'Ayuda',
    ],

    'sidebar' => [
        'new_process'        => '+ Nuevo proceso',
        'search_placeholder' => 'Buscar procesos…',
        'no_processes_yet'   => 'Aún no hay procesos',
    ],

    'toolbar' => [
        'process'   => 'Proceso',
        'decision'  => 'Decisión',
        'terminal'  => 'Inicio/Fin',
        'document'  => 'Documento',
        'connect'   => 'Conectar',
        'group'     => 'Grupo',
        'lane'      => 'Carril',
        'export'    => 'Exportar',
        'save'      => 'Guardar',
    ],

    'context' => [
        'create_new' => 'Crear nuevo…',
    ],

    'shapes' => [
        'rectangle'     => 'Rectángulo',
        'rounded'       => 'Redondeado',
        'pill'          => 'Píldora',
        'circle'        => 'Círculo',
        'diamond'       => 'Rombo',
        'parallelogram' => 'Paralelogramo',
        'trapezoid'     => 'Trapecio',
        'hexagon'       => 'Hexágono',
        'document'      => 'Documento',
        'cylinder'      => 'Cilindro',
        'cloud'         => 'Nube',
        'subroutine'    => 'Subrutina',
    ],

    'settings' => [
        'title'            => 'Tipos de paso',
        'intro'            => 'Defina los tipos de bloque disponibles en la barra de herramientas del Mapeador de procesos. Cada tipo es un nombre, una forma y un color.',
        'add_type'         => 'Añadir tipo',
        'col_order'        => 'Orden',
        'col_shape'        => 'Forma',
        'col_name'         => 'Nombre',
        'col_colour'       => 'Color',
        'col_active'       => 'Activo',
        'col_actions'      => 'Acciones',
        'none'             => 'Aún no hay tipos de paso',
        'builtin'          => 'Integrado',
        'yes'              => 'Sí',
        'no'               => 'No',
        'add_title'        => 'Añadir tipo de paso',
        'edit_title'       => 'Editar tipo de paso',
        'field_name'       => 'Nombre',
        'field_shape'      => 'Forma',
        'field_colour'     => 'Color',
        'field_active'     => 'Activo',
        'name_placeholder' => 'p. ej. Base de datos, Paso manual',
        'name_required'    => 'El nombre es obligatorio',
        'saved'            => 'Guardado',
        'deleted'          => 'Eliminado',
        'delete_confirm'   => '¿Eliminar este tipo de paso?',
    ],

    'autosave' => [
        'label'   => 'Autoguardado',
        'saved'   => 'Guardado',
        'unsaved' => 'Sin guardar',
        'unsaved_changes' => 'Cambios sin guardar',
        'saving'  => 'Guardando…',
        'failed'  => 'Error al guardar —',
        'retry'   => 'reintentar',
        'off'     => 'Autoguardado desactivado',
        'tooltip' => 'Guarda automáticamente unos segundos después de dejar de editar',
    ],

    'detail' => [
        'step_title'   => 'Detalles del paso',
        'group_title'  => 'Detalles del grupo',
        'lane_title'   => 'Detalles del carril',
        'label'        => 'Etiqueta',
        'type'         => 'Tipo',
        'colour'       => 'Color',
        'gradient'     => 'Degradado',
        'description'  => 'Descripción',
        'position'     => 'Posición',
        'size'         => 'Tamaño',
        'height'       => 'Altura',
        'order'        => 'Orden (de arriba abajo)',
        'connectors'   => 'Conectores',
        'no_connectors'=> 'Sin conectores',
        'step_type' => [
            'process'  => 'Proceso',
            'decision' => 'Decisión',
            'terminal' => 'Inicio/Fin',
            'document' => 'Documento',
        ],
        'step_description_placeholder' => 'Añadir notas sobre este paso…',
        'lane_label_placeholder'       => 'p. ej. RR. HH. / IT / Proveedor',
        'group_label_placeholder'      => 'p. ej. Fase de resolución',
        'lane_hint'                    => 'Arrastre el encabezado izquierdo del carril para reordenar. Arrastre el borde inferior para cambiar el tamaño. Suelte un paso en la banda para asignarlo a este carril.',
    ],

    'export_modal' => [
        'title'  => 'Exportar — Diagrama Mermaid',
        'hint'   => 'Pegue este código en cualquier editor Markdown compatible con Mermaid (GitHub, GitLab, Notion, Confluence, Obsidian…). Los carriles se convierten en bloques <code>subgraph</code>; el diseño automático sustituye sus posiciones.',
        'copy'   => 'Copiar',
        'copied' => 'Copiado ✓',
        'close'  => 'Cerrar',
    ],

    'toast' => [
        'no_process_open' => 'Abra o cree primero un proceso',
        'saved'           => 'Guardado',
        'save_failed'     => 'Error al guardar',
    ],
];
