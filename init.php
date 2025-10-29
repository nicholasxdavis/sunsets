<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');

$dsnHost = getenv('DATABASE') ?: '127.0.0.1';
$dbName  = getenv('NAME') ?: 'sunsets';
$dbUser  = getenv('USER') ?: 'user';
$dbPass  = getenv('USERPASS') ?: '';

try {
    $pdo = new PDO("mysql:host=$dsnHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>DB connection failed</h1>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}

function ensureTables(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS blogs (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NULL,
        image VARCHAR(512) NULL,
        redirect VARCHAR(512) NULL,
        buttonText VARCHAR(64) DEFAULT 'Read',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS issues (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NULL,
        image VARCHAR(512) NULL,
        redirect VARCHAR(512) NULL,
        buttonText VARCHAR(64) DEFAULT 'View',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS newsletter_subscriptions (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sponsor_applications (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        companyName VARCHAR(255) NOT NULL,
        contactName VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(64) NULL,
        businessType VARCHAR(64) NOT NULL,
        package VARCHAR(64) NOT NULL,
        message TEXT NULL,
        applicationDate DATETIME NOT NULL,
        status VARCHAR(32) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}

ensureTables($pdo);

echo '<h1>Initialization complete</h1>';
echo '<p>All required tables are present.</p>';
echo '<ul>';
echo '<li>blogs</li><li>issues</li><li>newsletter_subscriptions</li><li>sponsor_applications</li>';
echo '</ul>';
echo '<p>You can now use the site and admin to post content.</p>';


