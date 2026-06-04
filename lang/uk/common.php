<?php
/**
 * Ukrainian (uk) — Common shared UI strings.
 *
 * Used everywhere. Keep it small — module-specific strings belong in lang/en/<module>.php.
 * Other locales mirror this file structure under lang/<locale>/common.php.
 */
return [
    // Left-panel visibility preference — shared labels reused by every module
    // that has a left panel (settings pages + System → Preferences). Only the
    // identical strings live here; per-module intro/description copy stays in
    // each module's own file.
    'left_panel' => [
        'tab'        => 'Ліва панель',
        'visibility' => 'Видимість',
        'always'     => 'Завжди видима',
        'hover'      => 'Показувати при наведенні',
    ],

    // Shared AI provider/model/key panel (includes/ai_settings_panel.php),
    // reused by every module's AI settings tab.
    'ai' => [
        'provider'            => 'Постачальник',
        'provider_anthropic'  => 'Anthropic (Claude)',
        'provider_openai'     => 'OpenAI (GPT)',
        'provider_openrouter' => 'OpenRouter (один ключ, багато моделей)',
        'openrouter_note'     => 'З OpenRouter один ключ дає доступ до сотень моделей. Зверніть увагу, що запити маршрутизуються через сервіс OpenRouter.',
        'model'               => 'Модель',
        'model_placeholder'   => 'Введіть або оберіть модель…',
        'model_set'           => 'Модель',
        'loading_models'      => 'Завантаження списку моделей…',
        'no_models'           => 'Відповідних моделей не знайдено — можна ввести будь-який ідентифікатор моделі',
        'openrouter_pricing'  => 'Ціни вказано за 1M токенів (вхід / вихід).',
        'models_stale'        => 'кешовано',
        'api_key'             => 'API-ключ',
        'api_key_help'        => 'Зберігається у зашифрованому вигляді. Залиште порожнім, щоб зберегти поточний ключ.',
        'api_key_set'         => 'Ключ збережено. Залиште порожнім, щоб зберегти його.',
        'verify_ssl'          => 'Перевіряти SSL-сертифікат',
        'verify_ssl_help'     => 'Залиште увімкненим у робочому середовищі. Вимикайте лише якщо сервер не може перевірити сертифікат постачальника.',
        'save'                => 'Зберегти',
        'test'                => 'Перевірити',
        'testing'             => 'Перевірка…',
        'test_ok'             => 'З\'єднання успішне',
        'test_failed'         => 'Перевірка не вдалася',
        'saved'               => 'Збережено',
        'save_failed'         => 'Не вдалося зберегти',
    ],

    // Buttons
    'save'         => 'Зберегти',
    'cancel'       => 'Скасувати',
    'delete'       => 'Видалити',
    'add'          => 'Додати',
    'edit'         => 'Редагувати',
    'close'        => 'Закрити',
    'copy'         => 'Копіювати',
    'copied'       => 'Скопійовано',
    'retry'        => 'Повторити',
    'export'       => 'Експорт',
    'open'         => 'Відкрити',
    'apply'        => 'Застосувати',

    // Confirm / state
    'yes'          => 'Так',
    'no'           => 'Ні',
    'ok'           => 'OK',
    'loading'      => 'Завантаження...',
    'saving'       => 'Збереження...',
    'saved'        => 'Збережено',
    'unsaved'      => 'Незбережено',
    'unsaved_changes' => 'Незбережені зміни',
    'failed'       => 'Помилка',

    // Time / units (often inlined)
    'just_now'     => 'щойно',
    'today'        => 'Сьогодні',
    'yesterday'    => 'Вчора',

    // Form helpers
    'required'     => 'Обов\'язково',
    'optional'     => 'Необов\'язково',
    'select_one'   => 'Оберіть…',
    'search'       => 'Пошук',

    // Errors
    'error_generic'        => 'Щось пішло не так.',
    'error_network'        => 'Помилка мережі',
    'error_not_logged_in'  => 'Необхідно увійти в систему.',

    // Home / landing page (index.php)
    'home' => [
        'header_title'     => 'Служба підтримки',
        'browser_title'    => 'Служба підтримки - ITSM',
        'welcome_heading'  => 'Що ви хочете зробити?',
        'welcome_subtitle' => 'Оберіть модуль для початку роботи',
        'footer'           => 'Service Desk ITSM',
    ],

    // Waffle module-switcher panel (shared header)
    'waffle' => [
        'title' => 'Модулі ITSM',
    ],

    // Per-module display name + one-line description.
    // Used by the home cards (name + description tooltip) and the waffle panel (name only).
    'modules' => [
        'watchtower'     => ['name' => 'Watchtower',  'description' => 'Єдина панель уваги по всіх модулях'],
        'tickets'        => ['name' => 'Заявки',      'description' => 'Управління запитами на підтримку, електронною поштою та зверненнями користувачів'],
        'assets'         => ['name' => 'Активи',      'description' => 'Облік ІТ-активів та призначення користувачам'],
        'knowledge'      => ['name' => 'Знання',      'description' => 'Створення та перегляд статей бази знань'],
        'changes'        => ['name' => 'Зміни',       'description' => 'Планування, відстеження та управління ІТ-змінами'],
        'calendar'       => ['name' => 'Календар',    'description' => 'Відстеження подій, дедлайнів та розкладів'],
        'morning-checks' => ['name' => 'Перевірки',   'description' => 'Реєстрація щоденних перевірок інфраструктури'],
        'reporting'      => ['name' => 'Звіти',       'description' => 'Перегляд системних журналів та аналітики'],
        'software'       => ['name' => 'Програми',    'description' => 'Перегляд інвентарю програмного забезпечення та ліцензій'],
        'forms'          => ['name' => 'Форми',       'description' => 'Створення власних форм та перегляд відповідей'],
        'contracts'      => ['name' => 'Контракти',   'description' => 'Управління постачальниками, контактами та контрактами'],
        'service-status' => ['name' => 'Статус',      'description' => 'Моніторинг стану сервісів та відстеження інцидентів'],
        'wiki'           => ['name' => 'Wiki',        'description' => 'Перегляд автоматично згенерованої документації кодової бази'],
        'lms'            => ['name' => 'LMS',         'description' => 'Система управління навчанням з програвачем курсів SCORM'],
        'process-mapper' => ['name' => 'Процеси',     'description' => 'Інструмент візуального відображення блок-схем та процесів'],
        'tasks'          => ['name' => 'Завдання',    'description' => 'Kanban-дошка та список для відстеження завдань'],
        'cmdb'           => ['name' => 'CMDB',        'description' => 'База даних управління конфігураціями'],
        'network-mapper' => ['name' => 'Мережа',      'description' => 'Проектування та документування мережевих діаграм'],
        'workflow'       => ['name' => 'Робочі потоки', 'description' => 'Міжмодульна автоматизація — тригери, умови, дії'],
        'system'         => ['name' => 'Система',     'description' => 'Системне адміністрування та конфігурація'],
    ],

    // Account / user menu in the shared header
    'account' => [
        'mail_check'      => 'Перевірити нові листи',
        'preferences'     => 'Налаштування',
        'change_password' => 'Змінити пароль',
        'mfa'             => 'Багатофакторна автентифікація',
        'trusted_device'  => 'Довірений пристрій',
        'logout'          => 'Вийти',
        'logout_confirm'  => 'Ви впевнені, що хочете вийти?',
        'badge_off'       => 'Вимк.',
        'badge_on'        => 'Увімк.',
    ],

    // Change-password modal (static labels — dynamic JS toasts stay English for now)
    'password_modal' => [
        'title'            => 'Змінити пароль',
        'current_password' => 'Поточний пароль',
        'new_password'     => 'Новий пароль',
        'confirm_password' => 'Підтвердити новий пароль',
        'submit'           => 'Змінити пароль',
    ],

    // MFA modal (just the static title — the dynamic content is JS-rendered)
    'mfa_modal' => [
        'title' => 'Багатофакторна автентифікація',
    ],

    // Calendar primitives — months, weekdays, navigation. Shared across any module
    // that renders a calendar (tickets/calendar.php today; top-level calendar/ next).
    'calendar' => [
        'previous'   => 'Назад',
        'next'       => 'Вперед',
        'today'      => 'Сьогодні',
        'view_month' => 'Місяць',
        'view_week'  => 'Тиждень',
        'view_day'   => 'День',

        'months' => [
            'january'   => 'Січень',
            'february'  => 'Лютий',
            'march'     => 'Березень',
            'april'     => 'Квітень',
            'may'       => 'Травень',
            'june'      => 'Червень',
            'july'      => 'Липень',
            'august'    => 'Серпень',
            'september' => 'Вересень',
            'october'   => 'Жовтень',
            'november'  => 'Листопад',
            'december'  => 'Грудень',
        ],

        'weekdays' => [
            'monday'    => 'Понеділок',
            'tuesday'   => 'Вівторок',
            'wednesday' => 'Середа',
            'thursday'  => 'Четвер',
            'friday'    => 'П\'ятниця',
            'saturday'  => 'Субота',
            'sunday'    => 'Неділя',
        ],
    ],
];
