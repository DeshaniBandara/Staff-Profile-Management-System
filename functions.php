<?php
// Sanitize input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Check if admin is logged in
function check_login() {
    session_start();
    if(!isset($_SESSION['admin'])) {
        redirect('../login.php');
    }
}
?>