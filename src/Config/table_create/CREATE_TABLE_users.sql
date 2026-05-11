CREATE TABLE users (
    member_id INT PRIMARY KEY,
    uuid VARCHAR(36) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'visitor',
    status VARCHAR(255) NOT NULL DEFAULT 'active',
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


    -- role ENUM(
    --     'admin',
    --     'staff',
    --     'member',
    --     'visitor'
    -- ) DEFAULT 'visitor',
    -- status ENUM(
    --     'active',
    --     'blocked'