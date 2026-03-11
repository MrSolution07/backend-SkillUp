<?php
$env = function($mysqlKeys, $dbKey) {
    foreach ((array)$mysqlKeys as $k) {
        $v = getenv($k);
        if ($v !== false && $v !== '') return $v;
        $v = $_SERVER[$k] ?? $_SERVER['REDIRECT_' . $k] ?? null;
        if ($v !== null && $v !== '') return $v;
    }
    $v = getenv($dbKey);
    if ($v !== false && $v !== '') return $v;
    return $_SERVER[$dbKey] ?? $_SERVER['REDIRECT_' . $dbKey] ?? null;
};

$servername = $env(['MYSQL_HOST','MYSQLHOST'], 'DB_HOST');
$username   = $env(['MYSQL_USER','MYSQLUSER'], 'DB_USERNAME');
$password   = $env(['MYSQL_PASSWORD','MYSQLPASSWORD'], 'DB_PASSWORD');
$database   = $env(['MYSQL_DATABASE','MYSQLDATABASE'], 'DB_DATABASE');
$port       = $env(['MYSQL_PORT','MYSQLPORT'], 'DB_PORT') ?: 3306;

// Fallback: render-env.ini (written by docker-entrypoint.sh from Render env vars)
$renderEnvFile = __DIR__ . '/render-env.ini';
if ((!$servername || !$username || !$database) && file_exists($renderEnvFile)) {
    $r = @parse_ini_file($renderEnvFile);
    if ($r) {
        $servername = $servername ?: ($r['MYSQL_HOST'] ?? null);
        $username   = $username   ?: ($r['MYSQL_USER'] ?? null);
        $password   = $password   ?: ($r['MYSQL_PASSWORD'] ?? null);
        $database   = $database   ?: ($r['MYSQL_DATABASE'] ?? null);
        $port       = $port       ?: ($r['MYSQL_PORT'] ?? 3306);
    }
}

// Fallback to .env file (InfinityFree, local development)
if (!$servername || !$username || !$database) {
    $env = [];
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
        $servername = $servername ?: ($env['DB_HOST'] ?? null);
        $username   = $username   ?: ($env['DB_USERNAME'] ?? null);
        $password   = $password   ?: ($env['DB_PASSWORD'] ?? null);
        $database   = $database   ?: ($env['DB_DATABASE'] ?? null);
        $port       = $port       ?: ($env['DB_PORT'] ?? 3306);
    }
}

if (!$servername || !$username || !$database) {
    die("No database configuration found. Set MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE on Render, or add a .env file for local/InfinityFree.");
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($servername, $username, $password ?: '', $database, (int)$port);
if ($conn->connect_error) {
    $msg = $conn->connect_error;
    if (strpos($msg, 'getaddrinfo') !== false || strpos($msg, 'No address associated with hostname') !== false) {
        $msg = 'Database host unreachable. InfinityFree MySQL does not allow remote connections. Use Railway, PlanetScale, or another host that supports remote MySQL.';
    }
    die("Connection failed: " . $msg);
}
?>
