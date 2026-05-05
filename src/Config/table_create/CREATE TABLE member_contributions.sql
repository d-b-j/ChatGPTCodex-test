CREATE TABLE member_contributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id VARCHAR(36) NOT NULL,
    field_id VARCHAR(255),
    title VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    support_documents TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);