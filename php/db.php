<?php
require 'config.php';

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        fullname TEXT NOT NULL,
        username TEXT NOT NULL,
        password TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS sections (
        id INTEGER PRIMARY KEY,
        parent_id INTEGER,
        title TEXT NOT NULL,
        description TEXT,
        FOREIGN KEY (parent_id) REFERENCES sections(id)
    )");
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}
