<?php
/**
 * Русский (ru) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'Карта процессов',

    'toolbar' => [
        'process'   => 'Процесс',
        'decision'  => 'Решение',
        'terminal'  => 'Начало/Конец',
        'document'  => 'Документ',
        'connect'   => 'Соединить',
        'group'     => 'Группа',
        'lane'      => 'Дорожка',
        'export'    => 'Экспорт',
        'save'      => 'Сохранить',
    ],

    'autosave' => [
        'label'   => 'Автосохранение',
        'saved'   => 'Сохранено',
        'unsaved' => 'Не сохранено',
        'unsaved_changes' => 'Несохранённые изменения',
        'saving'  => 'Сохранение…',
        'failed'  => 'Ошибка сохранения —',
        'retry'   => 'повторить',
        'off'     => 'Автосохранение выключено',
        'tooltip' => 'Автоматически сохраняет через несколько секунд после прекращения редактирования',
    ],

    'detail' => [
        'step_title'   => 'Свойства шага',
        'group_title'  => 'Свойства группы',
        'lane_title'   => 'Свойства дорожки',
        'label'        => 'Название',
        'type'         => 'Тип',
        'colour'       => 'Цвет',
        'gradient'     => 'Градиент',
        'description'  => 'Описание',
        'position'     => 'Позиция',
        'size'         => 'Размер',
        'height'       => 'Высота',
        'order'        => 'Порядок (сверху вниз)',
        'connectors'   => 'Соединения',
        'no_connectors'=> 'Нет соединений',
    ],

    'export_modal' => [
        'title'  => 'Экспорт — Диаграмма Mermaid',
        'hint'   => 'Вставьте этот код в любой Markdown-редактор с поддержкой Mermaid (GitHub, GitLab, Notion, Confluence, Obsidian…). Дорожки превращаются в блоки <code>subgraph</code>; автоматическая компоновка заменяет позиции, заданные вручную.',
        'copy'   => 'Копировать',
        'copied' => 'Скопировано ✓',
        'close'  => 'Закрыть',
    ],

    'toast' => [
        'no_process_open' => 'Сначала откройте или создайте процесс',
        'saved'           => 'Сохранено',
        'save_failed'     => 'Не удалось сохранить',
    ],
];
