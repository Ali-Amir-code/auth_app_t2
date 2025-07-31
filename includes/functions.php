<?php

// Sanitize input
define('ROOT_PATH', dirname(__DIR__));
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Redirect utility
function redirect($url) {
    header("Location: $url");
    exit;
}

// Check login status
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Protect pages
function ensure_logged_in() {
    if (!is_logged_in()) {
        redirect('index.php');
    }
}
?>
