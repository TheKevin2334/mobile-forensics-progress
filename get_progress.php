<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['team_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    // Get current team's progress
    $stmt = $pdo->prepare("
        SELECT progress_percentage, description, created_at
        FROM progress
        WHERE team_id = ?
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$_SESSION['team_id']]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get current progress
    $current_progress = 0;
    if (!empty($history)) {
        $current_progress = $history[0]['progress_percentage'];
    }

    // Get all teams' progress
    $stmt = $pdo->prepare("
        SELECT t.name, 
               COALESCE(p.progress_percentage, 0) as progress,
               MAX(p.created_at) as last_update
        FROM teams t
        LEFT JOIN progress p ON t.id = p.team_id
        GROUP BY t.id, t.name
        ORDER BY progress DESC
    ");
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format dates
    foreach ($history as &$item) {
        $item['date'] = date('M d, Y H:i', strtotime($item['created_at']));
    }
    foreach ($teams as &$team) {
        $team['last_update'] = $team['last_update'] ? date('M d, Y H:i', strtotime($team['last_update'])) : 'No updates';
    }

    echo json_encode([
        'current_progress' => $current_progress,
        'history' => $history,
        'teams' => $teams
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
} 