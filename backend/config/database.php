<?php
/**
 * Database Configuration - Simplified Version
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'flight_booking_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Simple session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get database connection
function getDBConnection()
{
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        return $conn;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Unknown database") !== false) {
            return createDatabase();
        }
        die("Connection failed: " . $e->getMessage());
    }
}

// Create database and all tables
function createDatabase()
{
    try {
        $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create database
        $conn->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $conn->exec("USE " . DB_NAME);

        // Users table
        $conn->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                tel VARCHAR(50),
                user_type ENUM('passenger', 'company') NOT NULL,
                account_balance DECIMAL(10,2) DEFAULT 5000.00,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Passengers table
        $conn->exec("
            CREATE TABLE IF NOT EXISTS passengers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                photo VARCHAR(500),
                passport_img VARCHAR(500),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        // Companies table
        $conn->exec("
            CREATE TABLE IF NOT EXISTS companies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                bio TEXT,
                address TEXT,
                location VARCHAR(255),
                logo_img VARCHAR(500),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        // Flights table
        $conn->exec("
            CREATE TABLE IF NOT EXISTS flights (
                id INT AUTO_INCREMENT PRIMARY KEY,
                company_id INT NOT NULL,
                flight_name VARCHAR(255) NOT NULL,
                flight_id VARCHAR(100) NOT NULL UNIQUE,
                itinerary TEXT NOT NULL,
                fees DECIMAL(10,2) NOT NULL,
                max_passengers INT NOT NULL,
                registered_passengers INT DEFAULT 0,
                pending_passengers INT DEFAULT 0,
                start_date DATE NOT NULL,
                start_time TIME NOT NULL,
                end_date DATE NOT NULL,
                end_time TIME NOT NULL,
                status ENUM('active', 'cancelled', 'completed') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (company_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        // Bookings table
        $conn->exec("
            CREATE TABLE IF NOT EXISTS bookings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                passenger_id INT NOT NULL,
                flight_id INT NOT NULL,
                status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
                payment_type ENUM('account', 'cash') NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (passenger_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE
            )
        ");

        // Messages table
        $conn->exec("
            CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sender_id INT NOT NULL,
                receiver_id INT NOT NULL,
                flight_id INT,
                message TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE SET NULL
            )
        ");

        return getDBConnection();
    } catch (PDOException $e) {
        die("Database creation failed: " . $e->getMessage());
    }
}
?>