<?php
// ==============================================================================
// AK23 / Maarifa23 Automated Database & Environment Initializer
// Standalone script: runs before composer or Laravel bootstrap
// ==============================================================================

$baseDir = dirname(__DIR__);
$coreDir = $baseDir . '/core';
$envFile = $coreDir . '/.env';
$envExample = $coreDir . '/.env.example';
$sqlDump = $coreDir . '/database/akcourse_production.sql';

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE') ?: 'm23_m23';
$dbUser = getenv('DB_USERNAME') ?: 'm23_m23';
$dbPass = getenv('DB_PASSWORD') ?: 'M23!@kTech';
$appUrl = getenv('APP_URL') ?: 'https://m23.ak23studiokits.com';

echo "======================================================================\n";
echo " Maarifa23 Database & Environment Initializer\n";
echo "======================================================================\n";

// 1. Prepare .env file
if (!file_exists($envFile)) {
    if (file_exists($envExample)) {
        copy($envExample, $envFile);
        echo " Created core/.env from .env.example\n";
    } else {
        file_put_contents($envFile, "");
        echo " Created fresh core/.env\n";
    }
}

$envContent = file_get_contents($envFile);

$replacements = [
    'APP_NAME' => '"Maarifa23"',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'APP_URL' => $appUrl,
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => $dbHost,
    'DB_PORT' => $dbPort,
    'DB_DATABASE' => $dbName,
    'DB_USERNAME' => $dbUser,
    'DB_PASSWORD' => $dbPass,
    'SESSION_SECURE_COOKIE' => 'true',
    'GOOGLE_CLIENT_ID' => '"689451243598-pfpp0384omt30as3lcrrte69ksgb36b3.apps.googleusercontent.com"',
    'GOOGLE_CLIENT_SECRET' => '"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"',
    'GOOGLE_REDIRECT_URI' => '"${APP_URL}/user/social-login/callback/google"',
];

foreach ($replacements as $key => $val) {
    if (preg_match("/^{$key}=.*/m", $envContent)) {
        $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $envContent);
    } else {
        $envContent .= "\n{$key}={$val}";
    }
}

file_put_contents($envFile, trim($envContent) . "\n");
echo " Configured core/.env with DB: {$dbName}, User: {$dbUser}, Host: {$dbHost}\n";

// 2. Connect to MySQL Database
echo " Connecting to MySQL at {$dbHost}:{$dbPort}...\n";
try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    echo " MySQL connection successful!\n";

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `{$dbName}`;");
    echo " Active database set to: `{$dbName}`\n";

    // Check table count
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $tableCount = count($tables);
    echo " Current database table count: {$tableCount}\n";

    // 3. Import production dump if tables are empty or incomplete (< 50 tables)
    if ($tableCount < 50 && file_exists($sqlDump)) {
        echo " Importing official Maarifa23 production database dump...\n";
        
        $sql = file_get_contents($sqlDump);
        
        // Remove comments and split statements cleanly
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        
        // Execute multiline queries
        $pdo->exec($sql);
        
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

        $stmt = $pdo->query("SHOW TABLES");
        $newTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo " Successfully imported " . count($newTables) . " tables with all data, courses, categories, and settings!\n";
    } else {
        echo " Database already populated with {$tableCount} tables.\n";
    }

    // Ensure Google OAuth credentials are set in general_settings
    try {
        $stmt = $pdo->query("SELECT id, socialite_credentials FROM general_settings LIMIT 1");
        $gsRow = $stmt->fetch();
        if ($gsRow) {
            $credentials = json_decode($gsRow['socialite_credentials'] ?? '{}', true) ?: [];
            $credentials['google'] = [
                'client_id' => '689451243598-pfpp0384omt30as3lcrrte69ksgb36b3.apps.googleusercontent.com',
                'client_secret' => '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',
                'status' => 1,
            ];
            $updateStmt = $pdo->prepare("UPDATE general_settings SET socialite_credentials = :creds, updated_at = NOW() WHERE id = :id");
            $updateStmt->execute([
                ':creds' => json_encode($credentials),
                ':id' => $gsRow['id'],
            ]);
            echo " Seeded & enabled Google OAuth credentials in general_settings table.\n";
        }
    } catch (\Throwable $e) {
        echo " Note updating Google OAuth in general_settings: " . $e->getMessage() . "\n";
    }

} catch (PDOException $e) {
    echo " Database notice: " . $e->getMessage() . "\n";
    echo " Please verify MySQL service is running and credentials are valid.\n";
}

echo " Initialization phase finished.\n";
