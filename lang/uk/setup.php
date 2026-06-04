<?php
/**
 * Ukrainian (uk) — Setup Verification (first-run installer) strings.
 *
 * Covers the single setup/index.php page: the page title, summary badges,
 * individual check names + details, the Database Verify section, the default
 * login block, the footer warning, and the JS strings used by runDbVerify().
 *
 * Dynamic bits (paths, driver names, extension names, raw error messages) are
 * passed in via {placeholder} params rather than translated.
 */
return [
    'title'   => 'FreeITSM Встановлення',
    'heading' => 'Перевірка встановлення',

    'summary' => [
        'passed'   => '{n} пройдено',
        'warning'  => '{n} попередження',
        'warnings' => '{n} попереджень',
        'failed'   => '{n} не вдалося',
    ],

    'checks' => [
        'config'         => 'config.php',
        'db_config'      => 'db_config.php',
        'db_connection'  => 'З\'єднання з базою даних',
        'encryption_key' => 'Ключ шифрування',
        'ssl_verify'     => 'Перевірка SSL-сертифіката',
        'display_errors' => 'Відображення помилок',
        'php_version'    => 'Версія PHP',
        'php_extension'  => 'Розширення PHP: {ext}',
    ],

    'detail' => [
        'found'                    => 'Знайдено',
        'config_not_found'         => 'Не знайдено — скопіюйте config.php до кореневої теки застосунку',
        'db_config_not_found'      => 'Не знайдено за шляхом: {path}',
        'db_config_path_unset'     => 'Змінна $db_config_path не задана у config.php',
        'db_connected'             => 'Підключено (драйвер: {driver})',
        'db_constants_undefined'   => 'Константи бази даних не визначені — перевірте db_config.php',
        'encryption_key_missing'   => 'Не знайдено за шляхом: {path} — потрібен для шифрування чутливих налаштувань',
        'encryption_key_undefined' => 'ENCRYPTION_KEY_PATH не визначено у includes/encryption.php',
        'ssl_enabled'              => 'Увімкнено',
        'ssl_disabled'             => 'Вимкнено — увімкніть для продуктивного середовища (встановіть SSL_VERIFY_PEER у значення true у config.php)',
        'ssl_undefined'            => 'SSL_VERIFY_PEER не визначено у config.php',
        'display_errors_enabled'   => 'Увімкнено — вимкніть для продуктивного середовища (встановіть display_errors у значення 0 у config.php)',
        'display_errors_disabled'  => 'Вимкнено',
        'php_version_ok'           => '{version}',
        'php_version_too_low'      => '{version} — потрібна PHP 7.4 або вище',
        'extension_loaded'         => 'Завантажено',
        'extension_not_loaded'     => 'Не завантажено — увімкніть у php.ini',
        'pdo_mysql_not_loaded'     => 'Не завантажено — увімкніть pdo_mysql у php.ini',
    ],

    'db_verify' => [
        'heading' => 'Перевірка бази даних',
        'intro'   => 'Перевірте та автоматично створіть відсутні таблиці або стовпці в базі даних.',
        'run'     => 'Запустити',
    ],

    'login' => [
        'heading'  => 'Вхід за замовчуванням',
        'intro'    => 'Обліковий запис адміністратора за замовчуванням створюється під час запуску перевірки бази даних.',
        'username' => 'Ім\'я користувача:',
        'password' => 'Пароль:',
    ],

    'footer' => [
        'warning'   => 'Після переведення системи в продуктивний режим видаліть теку {folder} з міркувань безпеки.',
        'signature' => 'Перевірка встановлення FreeITSM',
    ],

    'js' => [
        'running'        => 'Виконується...',
        'run'            => 'Запустити',
        'tables_checked' => '{n} таблиць перевірено:',
        'ok'             => '{n} гаразд',
        'created'        => '{n} створено',
        'updated'        => '{n} оновлено',
        'errors'         => '{n} помилок',
        'unknown_error'  => 'Невідома помилка',
        'verify_failed'  => 'Не вдалося виконати перевірку БД: {error}',
    ],
];
