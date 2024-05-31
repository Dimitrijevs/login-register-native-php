<?php
session_start();
require 'db.php';

function login($username, $password)
{
    $username = trim($username);
    $password = trim($password);

    global $db;

    $stmt = $db->prepare("SELECT id, fullname, username, password FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['message'] = 'Login successful!';
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: ../views/profile.php');

        die();
    }

    $_SESSION['message'] = 'Invalid username or password!';
    header('Location: /index.php');
    die();
}

function logout()
{
    session_destroy();
    header('Location: /index.php');
    die();
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['message'] = 'You must be logged in to access this page!';
        header('Location: /index.php');
        exit();
    }
}


// call methods
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login($_POST['username'], $_POST['password']);
}

if (isset($_GET['logout'])) {
    logout();
}