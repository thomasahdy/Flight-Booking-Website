<?php
/**
 * Database Configuration
 * This file contains the database connection settings for XAMPP MySQL
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'tripma_db');
define('DB_USER', 'root');
define('DB_PASS', '');


function getDBConnection()
{
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch (PDOException $e) {
        // If database doesn't exist, try to create it
        if (strpos($e->getMessage(), "Unknown database") !== false) {
            return createDatabase();
        }
        die("Connection failed: " . $e->getMessage());
    }
}


function createDatabase()
{
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST,
            DB_USER,
            DB_PASS
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $conn->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");


        $conn->exec("USE " . DB_NAME);


        $conn->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                full_name VARCHAR(255) NOT NULL,
                phone VARCHAR(50),
                user_type ENUM('passenger', 'company') NOT NULL DEFAULT 'passenger',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");


        $conn->exec("
            CREATE TABLE IF NOT EXISTS passengers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                profile_image VARCHAR(500),
                passport_image VARCHAR(500),
                passport_number VARCHAR(50),

                account_balance DECIMAL(10,2) DEFAULT 0,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");


        $conn->exec("
            CREATE TABLE IF NOT EXISTS companies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                company_name VARCHAR(255) NOT NULL,
                logo_url VARCHAR(500),
                bio TEXT,
                address TEXT,
                location VARCHAR(255),
                account_balance DECIMAL(10,2) DEFAULT 0,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        $conn->exec("
            CREATE TABLE IF NOT EXISTS flights (
                flight_id INT AUTO_INCREMENT PRIMARY KEY,
                company_id INT NOT NULL,
                flight_name VARCHAR(255) NOT NULL,
                fees DECIMAL(10,2) NOT NULL,
                max_passengers INT NOT NULL,
                registered_count INT DEFAULT 0,
                pending_count INT DEFAULT 0,
                completed BOOLEAN DEFAULT FALSE,
                status ENUM('active', 'cancelled', 'completed') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
            )
        ");

        $conn->exec("
            CREATE TABLE IF NOT EXISTS itineraries (
                id INT AUTO_INCREMENT PRIMARY KEY,
                flight_id INT NOT NULL,
                city_order INT NOT NULL,
                city_name VARCHAR(255) NOT NULL,
                arrival_time DATETIME NOT NULL,
                departure_time DATETIME NOT NULL,
                FOREIGN KEY (flight_id) REFERENCES flights(flight_id) ON DELETE CASCADE
            )
        ");

        $conn->exec("
            CREATE TABLE IF NOT EXISTS flight_passengers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                flight_id INT NOT NULL,
                user_id INT NOT NULL,
                status ENUM('pending', 'registered', 'completed') DEFAULT 'pending',
                FOREIGN KEY (flight_id) REFERENCES flights(flight_id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        $conn->exec("
            CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sender_id INT NOT NULL,
                receiver_id INT NOT NULL,
                message TEXT NOT NULL,
                FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        return getDBConnection();
    } catch (PDOException $e) {
        die("Database creation failed: " . $e->getMessage());
    }
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
