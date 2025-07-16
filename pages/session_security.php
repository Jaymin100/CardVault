<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

$timeout_duration = 1800; // 30 minutes
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: Login.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

// Only redirect to login if $require_login is set and true
if (isset($require_login) && $require_login && !isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}
?>