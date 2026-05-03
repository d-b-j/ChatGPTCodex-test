ALTER TABLE `members`
    ADD COLUMN `qr_code_path` varchar(255) DEFAULT NULL AFTER `status`,
    ADD COLUMN `qr_code_content_url` varchar(255) DEFAULT NULL AFTER `qr_code_path`,
    ADD COLUMN `qr_code_generated_at` datetime DEFAULT NULL AFTER `qr_code_content_url`,
    ADD KEY `idx_members_qr_code_generated_at` (`qr_code_generated_at`);
