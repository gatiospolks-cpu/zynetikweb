-- Création de la base de données
CREATE DATABASE IF NOT EXISTS zynetikweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE zynetikweb;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'staff', 'founder') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des sites web (pour le dashboard)
CREATE TABLE IF NOT EXISTS websites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    site_name VARCHAR(100) NOT NULL,
    site_url VARCHAR(255) NOT NULL,
    status ENUM('online', 'pending', 'offline') DEFAULT 'online',
    plan VARCHAR(50) DEFAULT 'Starter Pack',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des serveurs (pour le dashboard)
CREATE TABLE IF NOT EXISTS servers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    server_name VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    location VARCHAR(100) NOT NULL,
    status ENUM('online', 'offline') DEFAULT 'online',
    cpu_usage INT DEFAULT 0,
    ram_usage VARCHAR(50) DEFAULT '0/0',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- Table des permissions par rôle
CREATE TABLE IF NOT EXISTS role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user', 'staff', 'founder') NOT NULL UNIQUE,
    perm_manage_users TINYINT(1) DEFAULT 0,
    perm_create_services TINYINT(1) DEFAULT 0,
    perm_view_services TINYINT(1) DEFAULT 1,
    perm_delete_services TINYINT(1) DEFAULT 0
);

-- Insertion des permissions par défaut
INSERT IGNORE INTO role_permissions (role, perm_manage_users, perm_create_services, perm_view_services, perm_delete_services) VALUES 
('user', 0, 0, 1, 0),
('staff', 0, 1, 1, 0),
('founder', 1, 1, 1, 1);
