-- Create the database
CREATE DATABASE IF NOT EXISTS mobile_forensics_db;
USE mobile_forensics_db;

-- Create teams table
CREATE TABLE IF NOT EXISTS teams (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create team_members table
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id VARCHAR(50),
    name VARCHAR(100) NOT NULL,
    access_key VARCHAR(100) NOT NULL,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id)
);

-- Create progress table
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

-- Insert the Cyber Alpha Army team
INSERT INTO teams (id, name) VALUES ('cyber_alpha_army', 'Cyber Alpha Army');

-- Insert team members
INSERT INTO team_members (team_id, name, access_key) VALUES
('cyber_alpha_army', 'Ayush Kumar Barnwal (Leader)', 'ayush_kumar_barnwal_leader_2024_key'),
('cyber_alpha_army', 'Yogesh Kumar', 'yogesh_kumar_2024_key'),
('cyber_alpha_army', 'Ashutosh Kumar', 'ashutosh_kumar_2024_key'),
('cyber_alpha_army', 'Ayush Kumar', 'ayush_kumar_2024_key'); 