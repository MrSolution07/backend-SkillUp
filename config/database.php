<?php
// Render: uses MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE, MYSQL_PORT
// InfinityFree/Local: uses .env with DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT

// #region agent log
$g = function($k) { $v = getenv($k); return $v === false ? 'NOT_SET' : 'SET'; };
error_log('[DEBUG-8eb27b] ' . json_encode([
    'hypothesisId' => 'A',
    'getenv_MYSQL_HOST' => $g('MYSQL_HOST'),
    'getenv_MYSQLHOST' => $g('MYSQLHOST'),
    'getenv_MYSQL_USER' => $g('MYSQL_USER'),
    'getenv_MYSQLUSER' => $g('MYSQLUSER'),
    'getenv_MYSQL_DATABASE' => $g('MYSQL_DATABASE'),
    'getenv_MYSQLDATABASE' => $g('MYSQLDATABASE'),
    'getenv_MYSQL_PASSWORD' => $g('MYSQL_PASSWORD'),
    'getenv_MYSQLPASSWORD' => $g('MYSQLPASSWORD'),
    'env_file_exists' => file_exists(__DIR__ . '/../.env'),
    'php_sapi' => php_sapi_name(),
]));
// #endregion

$servername = getenv('MYSQL_HOST') ?: getenv('MYSQLHOST');
$username   = getenv('MYSQL_USER') ?: getenv('MYSQLUSER');
$password   = getenv('MYSQL_PASSWORD') ?: getenv('MYSQLPASSWORD');
$database   = getenv('MYSQL_DATABASE') ?: getenv('MYSQLDATABASE');
$port       = getenv('MYSQL_PORT') ?: getenv('MYSQLPORT') ?: 3306;

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
    // #region agent log
    error_log('[DEBUG-8eb27b] ' . json_encode([
        'hypothesisId' => 'B',
        'before_die' => true,
        'has_servername' => !empty($servername),
        'has_username' => !empty($username),
        'has_database' => !empty($database),
    ]));
    // #endregion
    die("No database configuration found. Set MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE on Render, or add a .env file for local/InfinityFree.");
}

$conn = new mysqli($servername, $username, $password ?: '', $database, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
