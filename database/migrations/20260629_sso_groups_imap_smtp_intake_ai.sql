
-- SSO group access, generic IMAP/SMTP intake, and AI-ready foundations.
CREATE TABLE IF NOT EXISTS `access_groups` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `group_key` VARCHAR(80) NOT NULL,
    `display_name` VARCHAR(120) NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_access_groups_key` (`group_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `access_group_modules` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `access_group_id` INT NOT NULL,
    `module_key` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_access_group_module` (`access_group_id`, `module_key`),
    CONSTRAINT `fk_access_group_modules_group` FOREIGN KEY (`access_group_id`) REFERENCES `access_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `analyst_access_groups` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `analyst_id` INT NOT NULL,
    `access_group_id` INT NOT NULL,
    `source` VARCHAR(20) NOT NULL DEFAULT 'manual',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_analyst_access_group_source` (`analyst_id`, `access_group_id`, `source`),
    CONSTRAINT `fk_aag_analyst` FOREIGN KEY (`analyst_id`) REFERENCES `analysts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_aag_group` FOREIGN KEY (`access_group_id`) REFERENCES `access_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_provider_group_mappings` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `provider_id` INT NOT NULL,
    `external_group_value` VARCHAR(255) NOT NULL,
    `access_group_id` INT NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_provider_external_group` (`provider_id`, `external_group_value`),
    CONSTRAINT `fk_apgm_provider` FOREIGN KEY (`provider_id`) REFERENCES `auth_providers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_apgm_group` FOREIGN KEY (`access_group_id`) REFERENCES `access_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `intake_channels` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `channel_key` VARCHAR(100) NOT NULL,
    `display_name` VARCHAR(150) NOT NULL,
    `channel_type` VARCHAR(20) NOT NULL DEFAULT 'email',
    `enabled` TINYINT(1) NOT NULL DEFAULT 1,
    `config_json` JSON NULL,
    `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_intake_channels_key` (`channel_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `intake_messages` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `channel_id` INT NOT NULL,
    `external_message_id` VARCHAR(255) NOT NULL,
    `sender_identity` VARCHAR(255) NULL,
    `subject` VARCHAR(500) NULL,
    `body_text` LONGTEXT NULL,
    `body_html` LONGTEXT NULL,
    `status` VARCHAR(30) NOT NULL DEFAULT 'new',
    `ticket_id` INT NULL,
    `metadata_json` JSON NULL,
    `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `processed_datetime` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_intake_external_message` (`channel_id`, `external_message_id`),
    CONSTRAINT `fk_intake_messages_channel` FOREIGN KEY (`channel_id`) REFERENCES `intake_channels` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_intake_messages_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ai_provider_settings` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `ai_triage_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `ai_pre_ticket_collection_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `ai_provider_type` VARCHAR(50) NULL,
    `ai_endpoint_url` TEXT NULL,
    `ai_model` VARCHAR(120) NULL,
    `ai_is_sovereign_offline` TINYINT(1) NOT NULL DEFAULT 0,
    `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_datetime` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `access_groups` (`group_key`, `display_name`, `description`) VALUES
('guest', 'FreeITSM Guest', 'Read-only or portal-adjacent access foundation.'),
('support', 'FreeITSM Support', 'Service desk analyst module access.'),
('admin', 'FreeITSM Admin', 'Administrative FreeITSM access.'),
('sysadmin', 'FreeITSM SysAdmin', 'System administration access.'),
('devops', 'FreeITSM DevOps', 'DevOps and operational module access.');

DROP PROCEDURE IF EXISTS add_target_mailbox_column_if_missing;
DELIMITER //
CREATE PROCEDURE add_target_mailbox_column_if_missing(IN p_column VARCHAR(64), IN p_definition TEXT, IN p_after_column VARCHAR(64))
BEGIN
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'target_mailboxes')
       AND NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'target_mailboxes' AND column_name = p_column) THEN
        SET @ddl = CONCAT('ALTER TABLE `target_mailboxes` ADD `', p_column, '` ', p_definition, ' AFTER `', p_after_column, '`');
        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END//
DELIMITER ;

CALL add_target_mailbox_column_if_missing('provider_type', "VARCHAR(20) NOT NULL DEFAULT 'graph'", 'provider');
CALL add_target_mailbox_column_if_missing('auth_mode', "VARCHAR(20) NOT NULL DEFAULT 'delegated'", 'target_mailbox');
CALL add_target_mailbox_column_if_missing('imap_host', "TEXT NULL", 'imap_encryption');
CALL add_target_mailbox_column_if_missing('imap_username', "TEXT NULL", 'imap_host');
CALL add_target_mailbox_column_if_missing('imap_password_encrypted', "TEXT NULL", 'imap_username');
CALL add_target_mailbox_column_if_missing('imap_folder', "VARCHAR(100) NOT NULL DEFAULT 'INBOX'", 'imap_password_encrypted');
CALL add_target_mailbox_column_if_missing('smtp_host', "TEXT NULL", 'imap_folder');
CALL add_target_mailbox_column_if_missing('smtp_port', "INT NOT NULL DEFAULT 587", 'smtp_host');
CALL add_target_mailbox_column_if_missing('smtp_encryption', "VARCHAR(10) NOT NULL DEFAULT 'tls'", 'smtp_port');
CALL add_target_mailbox_column_if_missing('smtp_username', "TEXT NULL", 'smtp_encryption');
CALL add_target_mailbox_column_if_missing('smtp_password_encrypted', "TEXT NULL", 'smtp_username');
CALL add_target_mailbox_column_if_missing('smtp_from_address', "TEXT NULL", 'smtp_password_encrypted');
CALL add_target_mailbox_column_if_missing('smtp_from_name', "VARCHAR(255) NULL", 'smtp_from_address');
CALL add_target_mailbox_column_if_missing('intake_enabled', "TINYINT(1) NOT NULL DEFAULT 1", 'smtp_from_name');
CALL add_target_mailbox_column_if_missing('outbound_enabled', "TINYINT(1) NOT NULL DEFAULT 1", 'intake_enabled');
CALL add_target_mailbox_column_if_missing('last_checked_at', "DATETIME NULL", 'last_checked_datetime');
DROP PROCEDURE add_target_mailbox_column_if_missing;

UPDATE `target_mailboxes`
   SET `provider_type` = CASE WHEN `provider` = 'google' THEN 'gmail' WHEN `provider` = 'imap_smtp' THEN 'imap_smtp' ELSE 'graph' END
 WHERE (`provider_type` IS NULL OR `provider_type` = '');
UPDATE `target_mailboxes` SET `auth_mode` = 'delegated' WHERE `auth_mode` IS NULL OR `auth_mode` = '' OR `auth_mode` = 'oauth';
UPDATE `target_mailboxes` SET `imap_folder` = COALESCE(NULLIF(`imap_folder`, ''), `email_folder`, 'INBOX') WHERE `imap_folder` IS NULL OR `imap_folder` = '';
