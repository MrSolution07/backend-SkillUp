<?php
// Temporary diagnostic - DELETE after debugging
header('Content-Type: application/json');
$keys = ['MYSQL_HOST','MYSQL_USER','MYSQL_PASSWORD','MYSQL_DATABASE','MYSQL_PORT'];
$result = [];
foreach ($keys as $k) {
    $result[$k] = [
        'getenv' => getenv($k) !== false ? 'SET' : 'NOT_SET',
        'SERVER' => isset($_SERVER[$k]) ? 'SET' : 'NOT_SET',
        'ENV'    => isset($_ENV[$k]) ? 'SET' : 'NOT_SET',
    ];
}
$result['render_env_ini'] = file_exists(__DIR__ . '/config/render-env.ini') ? 'EXISTS' : 'MISSING';
$result['dot_env'] = file_exists(__DIR__ . '/.env') ? 'EXISTS' : 'MISSING';
$result['php_sapi'] = php_sapi_name();

// Check if entrypoint ran by looking for render-env.ini content
if (file_exists(__DIR__ . '/config/render-env.ini')) {
    $result['render_env_content'] = file_get_contents(__DIR__ . '/config/render-env.ini');
}

// Also check shell env via printenv (to see if Render injected anything MYSQL-related)
$shellEnv = shell_exec('printenv 2>&1') ?: '';
$mysqlLines = [];
foreach (explode("\n", $shellEnv) as $line) {
    if (stripos($line, 'MYSQL') !== false) {
        // Mask passwords
        if (stripos($line, 'PASSWORD') !== false) {
            $mysqlLines[] = preg_replace('/=.+/', '=***MASKED***', $line);
        } else {
            $mysqlLines[] = $line;
        }
    }
}
$result['shell_mysql_vars'] = $mysqlLines ?: 'NONE_FOUND';

echo json_encode($result, JSON_PRETTY_PRINT);
?>
