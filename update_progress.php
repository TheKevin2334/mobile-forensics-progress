<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['team_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $progress = filter_input(INPUT_POST, 'progress', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    
    if ($progress !== false && $progress >= 0 && $progress <= 100 && $description) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO progress (team_id, progress_percentage, description)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$_SESSION['team_id'], $progress, $description]);
            
            header('Location: index.php?success=1');
            exit();
        } catch (PDOException $e) {
            $error = "Failed to update progress: " . $e->getMessage();
        }
    } else {
        $error = "Invalid progress data!";
    }
}

if (isset($error)) {
    header('Location: index.php?error=' . urlencode($error));
    exit();
} 