CREATE TABLE member_profiles (
    member_id CHAR(36) PRIMARY KEY,
    birthday DATE NULL,
    nic VARCHAR(20) NOT NULL UNIQUE,
    al_batch_year SMALLINT NULL,
    cricket_years VARCHAR(255) NULL,
    membership_year SMALLINT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    batch_year INT DEFAULT NULL,
    gender VARCHAR(20) DEFAULT NULL,
    address TEXT,
    profile_photo TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);