CREATE DATABASE leadex;
USE leadex;

CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('buyer', 'seller') NOT NULL,
    remember_token VARCHAR(64) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL
);

CREATE TABLE leads (
    lead_id INT PRIMARY KEY AUTO_INCREMENT,
    seller_id INT,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    status ENUM('available', 'sold') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    lead_id INT,
    buyer_id INT,
    seller_id INT,
    amount DECIMAL(10,2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lead_id) REFERENCES leads(lead_id),
    FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    FOREIGN KEY (seller_id) REFERENCES users(user_id)
);

-- Insert default categories
INSERT INTO categories (category_name) VALUES 
('Business'),
('Technology'),
('Real Estate'),
('Marketing'),
('Education'); 

-- Add status field to leads table if not exists
ALTER TABLE leads ADD COLUMN IF NOT EXISTS status ENUM('available', 'sold') DEFAULT 'available';

-- Add created_at field if not exists
ALTER TABLE leads ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Add indexes for better performance
ALTER TABLE leads ADD INDEX idx_status (status);
ALTER TABLE leads ADD INDEX idx_category (category_id);
ALTER TABLE leads ADD INDEX idx_seller (seller_id);

-- Add foreign key constraints if missing
ALTER TABLE transactions ADD FOREIGN KEY (lead_id) REFERENCES leads(lead_id);
ALTER TABLE transactions ADD FOREIGN KEY (buyer_id) REFERENCES users(user_id);
ALTER TABLE transactions ADD FOREIGN KEY (seller_id) REFERENCES users(user_id); 