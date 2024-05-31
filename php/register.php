<?php
session_start();
require 'db.php';

function register($fullname, $username, $password, $password_confirmation) {
    global $db;

    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    
    if ($password !== $password_confirmation) {
        $_SESSION['message'] = 'Passwords do not match!';

        header('Location: /index.php');

        die();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    if($stmt->execute([':username' => $username]) && $stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['message'] = 'Username already exists!';

        header('Location: /index.php');

        die();
    }

    try {
        $stmt = $db->prepare("INSERT INTO users (username, fullname, password) VALUES (:username, :fullname, :password)");
        $stmt->execute([
            ':username' => $username,
            ':fullname' => $fullname,
            ':password' => $hashed_password
        ]);

        $_SESSION['message'] = 'Registration successful!';

        header('Location: /index.php');
    } catch (Exception $e) {
        $_SESSION['message'] = 'Error: ' . $e->getMessage();

        header('Location: /index.php');

        die();
    }
}

// call methods
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    register($_POST['fullname'], $_POST['username'], $_POST['password'], $_POST['password_confirmation']);
}