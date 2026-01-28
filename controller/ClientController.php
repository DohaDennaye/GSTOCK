<?php
require_once '../models/Client.php';

// Liste
$clients = Client::getAll();

// Ajouter
if (isset($_POST['add'])) {
    Client::create($_POST);
    header('Location: client.php');
}

// Edit
$client = Client::find($_GET['id']);

// Update
if (isset($_POST['update'])) {
    Client::update($_GET['id'], $_POST);
    header('Location: client.php');
}

// Delete
if (isset($_GET['delete'])) {
    Client::delete($_GET['delete']);
    header('Location: client.php');
}
?>
