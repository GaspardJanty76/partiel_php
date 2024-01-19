CREATE DATABASE IF NOT EXISTS partiel_php;

USE partiel_php;

CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    intitule VARCHAR(255) NOT NULL,
    reponse VARCHAR(255) NOT NULL,
    mauvaise_reponse VARCHAR(255) NOT NULL,
    bonne_reponse VARCHAR(255) NOT NULL,
    tentatives_reussies INT DEFAULT 0 NOT NULL,
    tentatives_totales INT DEFAULT 0 NOT NULL,
    pourcentage_reussite DECIMAL(5,2) DEFAULT 0 NOT NULL,
    suppression TINYINT DEFAULT 0 NOT NULL
);