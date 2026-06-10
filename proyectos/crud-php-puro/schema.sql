-- Base de datos del CRUD de tareas en PHP puro.
-- Importar:  mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS curso_tareas
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE curso_tareas;

CREATE TABLE IF NOT EXISTS tareas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titulo      VARCHAR(150) NOT NULL,
    completada  TINYINT(1)   NOT NULL DEFAULT 0,
    creada_en   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO tareas (titulo, completada) VALUES
    ('Aprender PHP desde cero', 1),
    ('Entender POO y Composer', 1),
    ('Dominar CodeIgniter 3', 0),
    ('Construir mi propio CRUD', 0);
