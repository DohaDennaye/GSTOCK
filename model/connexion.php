<?php
$conn = new mysqli("localhost", "root", "", "gestion_stock_dclick");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>