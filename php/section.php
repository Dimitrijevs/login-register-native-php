<?php
session_start();
require 'db.php';

function getSections($parentId = null) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM sections WHERE parent_id IS :parent_id');
    $stmt->bindValue(':parent_id', $parentId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addSection($parentId, $title, $description) {
    global $db;
    $stmt = $db->prepare('INSERT INTO sections (parent_id, title, description) VALUES (:parent_id, :title, :description)');
    $stmt->bindValue(':parent_id', $parentId, PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->execute();
}

function updateSection($id, $parentId, $title, $description) {
    global $db;
    $stmt = $db->prepare('UPDATE sections SET title = :title, parent_id = :parentId, description = :description WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':parentId', $parentId, PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->execute();
}

function deleteSection($id) {
    global $db;
    $stmt = $db->prepare('DELETE FROM sections WHERE id = :id OR parent_id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}


// call methods
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['addSection'])) {
    $parentId = $_POST['parent_id'] ? $_POST['parent_id'] : null;
    $title = $_POST['title'];
    $description = $_POST['description'];

    addSection($parentId, $title, $description);

    $_SESSION['message'] = 'Section added successfully!';
    header('Location: /views/profile.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['updateSection'])) {
    $id = $_POST['id'];
    $parentId = $_POST['parent_id'] ? $_POST['parent_id'] : null;
    $title = $_POST['title'];
    $description = $_POST['description'];

    updateSection($id, $parentId, $title, $description);

    $_SESSION['message'] = 'Section updated successfully!';
    header('Location: /views/profile.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['deleteSection'])) {
    $id = $_GET['deleteSection'];

    deleteSection($id);

    $_SESSION['message'] = 'Section deleted successfully!';
    header('Location: /views/profile.php');
    die();
}
