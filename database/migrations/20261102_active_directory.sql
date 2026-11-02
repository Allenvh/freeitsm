-- Optional native Active Directory authentication. Safe to run more than once.
-- Uses existing users, analysts, access groups and shared authentication providers.
CREATE TABLE IF NOT EXISTS ad_config (
 id INT NOT NULL PRIMARY KEY,
 enabled TINYINT NOT NULL DEFAULT 0,
 multi_domain TINYINT NOT NULL DEFAULT 0,
 default_provider_id INT NULL,
 authority_provider_id INT NULL,
 emergency_analyst_id INT NOT NULL DEFAULT 1,
 refresh_seconds INT NOT NULL DEFAULT 900,
 max_attempts INT NOT NULL DEFAULT 1,
 verification_minutes INT NOT NULL DEFAULT 20,
 allowed_email_domains TEXT NOT NULL,
 public_url VARCHAR(500) NOT NULL DEFAULT '',
 smtp_mailbox_id INT NULL,
 revision INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT IGNORE INTO ad_config (id, allowed_email_domains) VALUES (1, '[]');
CREATE TABLE IF NOT EXISTS ad_provider_config (
 provider_id INT NOT NULL PRIMARY KEY,
 authentication_enabled TINYINT NOT NULL DEFAULT 1,
 trusted_linking TINYINT NOT NULL DEFAULT 0,
 config_json JSON NOT NULL,
 bind_password TEXT NULL,
 FOREIGN KEY (provider_id) REFERENCES auth_providers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_profiles (
 id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL UNIQUE,
 analyst_id INT NULL UNIQUE,
 managed_analyst TINYINT NOT NULL DEFAULT 0,
 managed_user TINYINT NOT NULL DEFAULT 0,
 verified_at DATETIME NOT NULL,
 authorization_status VARCHAR(40) NOT NULL DEFAULT 'local',
 authorization_checked_at DATETIME NULL,
 authorization_revision INT NOT NULL DEFAULT 0,
 authority_guid CHAR(32) NULL,
 authority_provider_id INT NULL,
 authorization_groups JSON NULL,
 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (analyst_id) REFERENCES analysts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_identities (
 provider_id INT NOT NULL,
 object_guid CHAR(32) NOT NULL,
 profile_id INT NOT NULL,
 directory_email VARCHAR(255) NOT NULL,
 last_login_at DATETIME NULL,
 PRIMARY KEY (provider_id, object_guid),
 UNIQUE KEY uq_ad_profile_provider (profile_id, provider_id),
 FOREIGN KEY (provider_id) REFERENCES auth_providers(id),
 FOREIGN KEY (profile_id) REFERENCES ad_profiles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_verification_tokens (
 token_hash CHAR(64) NOT NULL PRIMARY KEY,
 email VARCHAR(255) NOT NULL,
 expires_at DATETIME NOT NULL,
 used_at DATETIME NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_ad_verification_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_rate_limits (
 bucket CHAR(64) NOT NULL PRIMARY KEY,
 attempts INT NOT NULL DEFAULT 1,
 expires_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_audit (
 id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 profile_id INT NULL,
 actor_analyst_id INT NULL,
 event VARCHAR(80) NOT NULL,
 details JSON NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_ad_audit_profile (profile_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_role_mappings (
 provider_id INT NOT NULL,
 group_guid CHAR(32) NOT NULL,
 access_group_id INT NOT NULL,
 priority INT NOT NULL DEFAULT 0,
 PRIMARY KEY (provider_id, group_guid, access_group_id),
 FOREIGN KEY (provider_id) REFERENCES auth_providers(id),
 FOREIGN KEY (access_group_id) REFERENCES access_groups(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
