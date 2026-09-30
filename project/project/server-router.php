<?php
// Local development only: lets PHP's built-in server serve real files directly and send
// everything else to Laravel. Used by start-local.bat. Not needed on real hosting.
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}
require __DIR__ . '/index.php';
