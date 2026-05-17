<?php
// ============================================================
// config/db.php — Database Connection
// Healix Hospital Management System
// ============================================================

// Database credentials — update these for your server
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'healix');

// Create the MySQLi connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if connection failed
if ($conn->connect_error) {
    die("
        <div style='font-family:sans-serif;padding:30px;background:#fff0f0;border:1px solid red;border-radius:8px;margin:40px auto;max-width:500px;'>
            <h3 style='color:red;'>Database Connection Failed</h3>
            <p>" . $conn->connect_error . "</p>
            <p>Please check your credentials in <code>config/db.php</code></p>
        </div>
    ");
}

// Set charset to UTF-8 for proper character encoding
$conn->set_charset("utf8mb4");
?>
