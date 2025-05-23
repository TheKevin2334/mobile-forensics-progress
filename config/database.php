<?php
// Load environment variables
$env_file = file_get_contents(__DIR__ . '/../.env');
$env_vars = parse_ini_string($env_file);

// Database configuration
$db_host = $env_vars['DB_HOST'] ?? 'localhost';
$db_user = $env_vars['DB_USER'] ?? 'root';
$db_pass = $env_vars['DB_PASS'] ?? '';
$db_name = $env_vars['DB_NAME'] ?? 'mobile_forensics_db';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create tables if they don't exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS teams (
        id VARCHAR(50) PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS team_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        team_id VARCHAR(50),
        name VARCHAR(100) NOT NULL,
        access_key VARCHAR(100) NOT NULL,
        last_login TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (team_id) REFERENCES teams(id)
    );

    CREATE TABLE IF NOT EXISTS progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        team_id VARCHAR(50),
        member_id INT,
        progress_percentage INT NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (team_id) REFERENCES teams(id),
        FOREIGN KEY (member_id) REFERENCES team_members(id)
    );
");

// Insert Cyber Alpha Army team if not exists
$stmt = $pdo->prepare("INSERT IGNORE INTO teams (id, name) VALUES (?, ?)");
$stmt->execute(['cyber_alpha_army', 'Cyber Alpha Army']);

// Insert team members if they don't exist
$members = [
    [
        'name' => $env_vars['TEAM_MEMBER_1_NAME'] ?? 'Ayush Kumar Barnwal (Leader)',
        'key' => $env_vars['TEAM_MEMBER_1_KEY'] ?? 'ayush_kumar_barnwal_leader_2024_key'
    ],
    [
        'name' => $env_vars['TEAM_MEMBER_2_NAME'] ?? 'Yogesh Kumar',
        'key' => $env_vars['TEAM_MEMBER_2_KEY'] ?? 'yogesh_kumar_2024_key'
    ],
    [
        'name' => $env_vars['TEAM_MEMBER_3_NAME'] ?? 'Ashutosh Kumar',
        'key' => $env_vars['TEAM_MEMBER_3_KEY'] ?? 'ashutosh_kumar_2024_key'
    ],
    [
        'name' => $env_vars['TEAM_MEMBER_4_NAME'] ?? 'Ayush Kumar',
        'key' => $env_vars['TEAM_MEMBER_4_KEY'] ?? 'ayush_kumar_2024_key'
    ]
];

$stmt = $pdo->prepare("INSERT IGNORE INTO team_members (team_id, name, access_key) VALUES (?, ?, ?)");
foreach ($members as $member) {
    $stmt->execute(['cyber_alpha_army', $member['name'], $member['key']]);
} 