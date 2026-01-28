<?php
session_start();
require_once __DIR__ . '/../services/AnalyseAI.php';

$message = trim($_POST['message'] ?? '');

if ($message === '') {
    echo "Je vous écoute 👂";
    exit;
}

// 🧠 Mémoire conversationnelle
$_SESSION['conversation'][] = [
    'role' => 'user',
    'content' => $message
];

// 🤖 Réponse IA
$response = AnalyseAI::chat($message, $_SESSION['conversation']);

// Sauvegarde réponse IA
$_SESSION['conversation'][] = [
    'role' => 'ai',
    'content' => strip_tags($response)
];

echo $response;
?>