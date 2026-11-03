-- Additive and repeatable. Existing requester/analyst IDs and ticket FKs remain unchanged.
CREATE TABLE IF NOT EXISTS ad_settings (
 id INT PRIMARY KEY,
 enabled TINYINT NOT NULL DEFAULT 0,
 multi_domain TINYINT NOT NULL DEFAULT 0,
 allow_fallback TINYINT NOT NULL DEFAULT 0,
 max_attempts INT NOT NULL DEFAULT 1,
 authority_id INT NULL,
 default_provider_id INT NULL,
 allowed_domains TEXT NOT NULL,
 base_url VARCHAR(500) NOT NULL DEFAULT '',
 mailbox_id INT NULL,
 verification_minutes INT NOT NULL DEFAULT 20,
 refresh_seconds INT NOT NULL DEFAULT 900,
 sensitive_seconds INT NOT NULL DEFAULT 60,
 emergency_analyst_id INT NULL,
 revision INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT IGNORE INTO ad_settings (id, allowed_domains) VALUES (1, '[]');
CREATE TABLE IF NOT EXISTS ad_providers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 enabled TINYINT NOT NULL DEFAULT 0,
 trusted TINYINT NOT NULL DEFAULT 0,
 authentication_enabled TINYINT NOT NULL DEFAULT 1,
 priority INT NOT NULL DEFAULT 100,
 config_json JSON NOT NULL,
 password_encrypted TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_profiles (
 id INT AUTO_INCREMENT PRIMARY KEY,
 email VARCHAR(255) NOT NULL,
 verified_at DATETIME NULL,
 user_id INT NULL,
 analyst_id INT NULL,
 last_provider_id INT NULL,
 authorization_status VARCHAR(40) NOT NULL DEFAULT 'pending',
 authorization_json JSON NULL,
 refreshed_at DATETIME NULL,
 revision INT NOT NULL DEFAULT 0,
 disabled TINYINT NOT NULL DEFAULT 0,
 UNIQUE KEY ad_email (email),
 UNIQUE KEY ad_user (user_id),
 UNIQUE KEY ad_analyst (analyst_id),
 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (analyst_id) REFERENCES analysts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_identities (
 provider_id INT NOT NULL,
 object_guid CHAR(32) NOT NULL,
 profile_id INT NOT NULL,
 directory_email VARCHAR(255) NOT NULL,
 linked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 last_login_at DATETIME NULL,
 PRIMARY KEY (provider_id, object_guid),
 UNIQUE KEY ad_profile_provider (profile_id, provider_id),
 FOREIGN KEY (provider_id) REFERENCES ad_providers(id),
 FOREIGN KEY (profile_id) REFERENCES ad_profiles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_group_mappings (
 id INT AUTO_INCREMENT PRIMARY KEY,
 provider_id INT NOT NULL,
 group_guid CHAR(32) NOT NULL,
 group_name VARCHAR(255) NOT NULL,
 access_group_id INT NOT NULL,
 precedence INT NOT NULL DEFAULT 100,
 UNIQUE KEY ad_group (provider_id, group_guid),
 FOREIGN KEY (provider_id) REFERENCES ad_providers(id),
 FOREIGN KEY (access_group_id) REFERENCES access_groups(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_verifications (
 token_hash CHAR(64) PRIMARY KEY,
 email VARCHAR(255) NOT NULL,
 expires_at DATETIME NOT NULL,
 consumed_at DATETIME NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY ad_verification_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_rate_limits (
 bucket CHAR(64) PRIMARY KEY,
 window_start BIGINT NOT NULL,
 attempts INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ad_audit (
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 profile_id INT NULL,
 actor_analyst_id INT NULL,
 event VARCHAR(80) NOT NULL,
 details_json JSON NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY ad_history (profile_id, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
