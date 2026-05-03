CREATE TABLE member_attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    member_id CHAR(36) NOT NULL,

    field_key VARCHAR(100) NOT NULL,
    field_label VARCHAR(255) NOT NULL,

    category VARCHAR(100) DEFAULT NULL,
    context_ref VARCHAR(100) DEFAULT NULL,

    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL, 
    mime_type VARCHAR(150) NOT NULL,
    file_size BIGINT UNSIGNED NOT NULL,

    uploaded_by VARCHAR(100) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);