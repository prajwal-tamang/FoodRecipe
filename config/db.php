<?php
/**
 * ========================================
 * DATABASE CONNECTION FILE
 * ========================================
 * 
 * This file connects PHP to MySQL database.
 * Include this file at the top of every PHP page that needs database.
 * 
 * HOW IT WORKS:
 * 1. We set the connection details (host, username, password, database)
 * 2. We use mysqli_connect() to connect to MySQL
 * 3. If connection fails, we show an error
 * 4. We also start a session for the login system
 */

// ============ DATABASE SETTINGS ============
// Change these if your setup is different

$host = "localhost";           // Server name (keep as provided by your host)
$username = "np03cs4a240143";  // MySQL username (updated)
$password = "3aRSTCtc9P";      // MySQL password (updated)
$database = "np03cs4a240143";  // The name of our database (updated)

// ============ CONNECT TO DATABASE ============

$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection worked
if (!$conn) {
    // If connection failed, stop and show error
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Set character encoding (allows special characters like é, ñ, etc.)
mysqli_set_charset($conn, "utf8mb4");

// ============ START SESSION ============
// Sessions are used to remember logged-in users

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============ INCLUDE HELPER FUNCTIONS ============
// This file contains useful functions like isLoggedIn(), safe(), etc.

require_once __DIR__ . '/../includes/functions.php';
?>