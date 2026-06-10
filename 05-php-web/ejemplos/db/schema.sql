-- Base de datos de ejemplo para el módulo 05 (y reutilizada en el proyecto CRUD).
-- Impórtala en MAMP con phpMyAdmin, o por consola:
--   mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS curso
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE curso;

CREATE TABLE IF NOT EXISTS usuarios (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(100)        NOT NULL,
    email      VARCHAR(150) UNIQUE NOT NULL,
    creado_en  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO usuarios (nombre, email) VALUES
    ('Jorge Herraiz', 'jorge@example.com'),
    ('Ana López',     'ana@example.com'),
    ('Luis Pérez',    'luis@example.com')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);
