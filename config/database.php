<?php
// Secure connection and database path config
$db_file = __DIR__ . '/attendease.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create tables if they do not exist
    $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
    $pdo->exec($schema);

    // Specialized Key for Admin Password Reset
    define('ADMIN_RESET_KEY', 'SUPER_SECRET_KEY_123');

    // Seed data if database is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $seed = file_get_contents(__DIR__ . '/../database/seed.sql');
        
        // Execute seed statements one by one or as a script
        // SQLite PDO handles multiple statements in exec()
        $pdo->exec($seed);
    }


} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
