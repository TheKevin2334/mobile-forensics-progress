<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!isset($_SESSION['team_id']) || !isset($_SESSION['member_id'])) {
    header('Location: login.php');
    exit();
}

// Get team and member information
try {
    $stmt = $pdo->prepare("
        SELECT tm.*, t.name as team_name, t.created_at as team_created_at
        FROM team_members tm 
        JOIN teams t ON tm.team_id = t.id 
        WHERE tm.id = ? AND tm.team_id = ?
    ");
    $stmt->execute([$_SESSION['member_id'], $_SESSION['team_id']]);
    $member_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $member_info = ['name' => 'Unknown Member', 'team_name' => 'Unknown Team'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Forensics Progress Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mobile Forensics Progress</a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="memberDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($member_info['name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="memberDropdown">
                        <li><div class="dropdown-item-text">
                            <strong>Team:</strong> <?php echo htmlspecialchars($member_info['team_name']); ?>
                        </div></li>
                        <li><div class="dropdown-item-text">
                            <strong>Member ID:</strong> <?php echo htmlspecialchars($_SESSION['member_id']); ?>
                        </div></li>
                        <li><div class="dropdown-item-text">
                            <strong>Last Login:</strong> <?php echo $member_info['last_login'] ? date('M d, Y H:i', strtotime($member_info['last_login'])) : 'First login'; ?>
                        </div></li>
                        <li><div class="dropdown-item-text">
                            <strong>Team Created:</strong> <?php echo date('M d, Y', strtotime($member_info['team_created_at'])); ?>
                        </div></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Progress</h4>
                    </div>
                    <div class="card-body">
                        <form action="update_progress.php" method="POST">
                            <div class="mb-3">
                                <label for="progress" class="form-label">Progress Percentage</label>
                                <input type="number" class="form-control" id="progress" name="progress" min="0" max="100" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Progress</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Team Progress</h4>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div id="progress-history">
                            <!-- Progress history will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Teams Progress</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Team Name</th>
                                        <th>Progress</th>
                                        <th>Last Update</th>
                                    </tr>
                                </thead>
                                <tbody id="teams-progress">
                                    <!-- Teams progress will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 