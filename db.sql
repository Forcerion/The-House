-- Base de données The House - The Grid
CREATE DATABASE IF NOT EXISTS `the_grid`;
USE `the_grid`;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Compte admin : The_House_Admin / 2017
INSERT INTO admins (username, password) VALUES 
('The_House_Admin', '$2y$10$tRqjAFRHAu7YsnVgpYzK8.GMcoNXDb8LmbgJs5Xx0/zyCzO4j2MR2');

CREATE TABLE pilotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE resultats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pilote_id INT NOT NULL,
    position INT NOT NULL,
    FOREIGN KEY (pilote_id) REFERENCES pilotes(id)
);