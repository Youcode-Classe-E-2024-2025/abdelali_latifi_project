-- Active: 1735588422303@@127.0.0.1@3306
CREATE DATABASE project ;

use project ;

-- Table des utilisateurs
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('member', 'guest') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des projets
CREATE TABLE projects (
    project_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    created_by INT REFERENCES users(user_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des membres du projet
CREATE TABLE project_members (
    project_member_id SERIAL PRIMARY KEY,
    project_id INT REFERENCES projects(project_id) ON DELETE CASCADE,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    role ENUM('owner', 'collaborator') DEFAULT 'collaborator',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (project_id, user_id)
);

-- Table des tâches
CREATE TABLE tasks (
    task_id SERIAL PRIMARY KEY,
    project_id INT REFERENCES projects(project_id) ON DELETE CASCADE,
    assigned_to INT REFERENCES users(user_id),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('todo', 'in_progress', 'completed') DEFAULT 'todo',
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des statuts de tâches (historique des mises à jour)
CREATE TABLE task_status_updates (
    status_update_id SERIAL PRIMARY KEY,
    task_id INT REFERENCES tasks(task_id) ON DELETE CASCADE,
    updated_by INT REFERENCES users(user_id),
    old_status ENUM('todo', 'in_progress', 'completed'),
    new_status ENUM('todo', 'in_progress', 'completed'),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Remplir la table users
INSERT INTO users (name, email, password_hash, role) VALUES
('Alice Johnson', 'alice@example.com', 'hashed_password_1', 'member'),
('Bob Smith', 'bob@example.com', 'hashed_password_2', 'guest'),
('Charlie Davis', 'charlie@example.com', 'hashed_password_3', 'member'),
('Dana Lee', 'dana@example.com', 'hashed_password_4', 'guest');

-- Remplir la table projects
INSERT INTO projects (name, description, is_public, created_by) VALUES
('Project Alpha', 'This is the first project.', TRUE, 1),
('Project Beta', 'This is a private project.', FALSE, 1),
('Project Gamma', 'A collaborative project.', TRUE, 3);

-- Remplir la table project_members
INSERT INTO project_members (project_id, user_id, role) VALUES
(1, 1, 'owner'),
(1, 2, 'collaborator'),
(2, 1, 'owner'),
(3, 3, 'owner'),
(3, 4, 'collaborator');

-- Remplir la table tasks
INSERT INTO tasks (project_id, assigned_to, title, description, status, due_date) VALUES
(1, 1, 'Design the homepage', 'Create the initial design for the homepage.', 'in_progress', '2024-12-31'),
(1, 2, 'Write project documentation', 'Draft the documentation for Project Alpha.', 'todo', '2024-12-28'),
(2, 1, 'Develop user authentication', 'Implement user login and registration.', 'todo', '2025-01-15'),
(3, 4, 'Set up the database', 'Design and configure the database schema.', 'in_progress', '2025-01-10');

-- Remplir la table task_status_updates
INSERT INTO task_status_updates (task_id, updated_by, old_status, new_status) VALUES
(1, 1, 'todo', 'in_progress'),
(4, 4, 'todo', 'in_progress');



SELECT DISTINCT role FROM users;

UPDATE users SET role = 'member' WHERE role = 'guest';

ALTER TABLE users MODIFY role ENUM('member', 'admin') DEFAULT 'member';

ALTER TABLE users
MODIFY role ENUM('member', 'admin', 'team_member') DEFAULT 'member';


UPDATE Users
SET role = 'guest'
WHERE role = 'member_team';


UPDATE Users SET role = 'guest' WHERE role = 'member_team';

SELECT * FROM Users where role = 'member_team';

use project ;

CREATE TABLE membership_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);













