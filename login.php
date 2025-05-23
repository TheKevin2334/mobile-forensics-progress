<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $access_key = $_POST['access_key'] ?? '';
    
    try {
        // Check for team member with this access key
        $stmt = $pdo->prepare("
            SELECT tm.*, t.name as team_name 
            FROM team_members tm 
            JOIN teams t ON tm.team_id = t.id 
            WHERE tm.access_key = ?
        ");
        $stmt->execute([$access_key]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($member) {
            // Update last login time
            $updateStmt = $pdo->prepare("UPDATE team_members SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $updateStmt->execute([$member['id']]);
            
            // Set session variables
            $_SESSION['team_id'] = $member['team_id'];
            $_SESSION['team_name'] = $member['team_name'];
            $_SESSION['member_id'] = $member['id'];
            $_SESSION['member_name'] = $member['name'];
            
            header('Location: index.php');
            exit();
        } else {
            $error = "Invalid access key!";
        }
    } catch (PDOException $e) {
        $error = "Database error occurred!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mobile Forensics Progress Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .team-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Mobile Forensics Progress Tracker</h3>
                    <div class="team-info">
                        <h5>Cyber Alpha Army</h5>
                        <p class="mb-0">Team Member Login</p>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="access_key" class="form-label">Your Access Key</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control" id="access_key" name="access_key" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 