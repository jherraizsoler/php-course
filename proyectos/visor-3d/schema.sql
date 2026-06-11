-- Base de datos del Visor 3D / volumétrico.
-- Catálogo de modelos subidos (los archivos viven en uploads/, aquí va el índice).
-- Importar:  mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS curso_3d
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE curso_3d;

CREATE TABLE IF NOT EXISTS modelos (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(150) NOT NULL,
    descripcion  VARCHAR(500) NULL,
    fuente       VARCHAR(255) NULL,              -- URL de origen / atribución del modelo
    -- malla (Three.js): stl, glb, gltf, obj, fbx  ·  volumétrico/científico (vtk.js): vtp, vti, vtk
    formato      VARCHAR(8)   NOT NULL,
    -- sector profesional: ver proyectos/visor-3d/sectores.php (dental, cirugia, radiologia, …)
    sector       VARCHAR(32)  NOT NULL DEFAULT 'otro',
    archivo      VARCHAR(255) NOT NULL,          -- nombre seguro guardado en uploads/
    nombre_orig  VARCHAR(255) NOT NULL,          -- nombre original que subió el usuario
    bytes        INT UNSIGNED NOT NULL DEFAULT 0,
    subido_en    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Migración idempotente: BBDD creadas con versiones antiguas (ENUM) pasan a VARCHAR.
ALTER TABLE modelos MODIFY COLUMN sector  VARCHAR(32) NOT NULL DEFAULT 'otro';
ALTER TABLE modelos MODIFY COLUMN formato VARCHAR(8)  NOT NULL;

-- Añadir columna 'fuente' si no existe (MySQL no soporta ADD COLUMN IF NOT EXISTS).
SET @add_fuente := (SELECT IF(COUNT(*) = 0,
    'ALTER TABLE modelos ADD COLUMN fuente VARCHAR(255) NULL AFTER descripcion',
    'SELECT 1') FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'modelos' AND COLUMN_NAME = 'fuente');
PREPARE st FROM @add_fuente; EXECUTE st; DEALLOCATE PREPARE st;

-- Modelos de ejemplo para que el catálogo no arranque vacío (los archivos viajan en uploads/).
-- Patrón idempotente: solo se insertan si no existe ya esa fila (por nombre de archivo).
INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Cubo de ejemplo' AS nombre,
    'Malla STL mínima (ASCII) para probar el visor de superficie con Three.js.' AS descripcion,
    'stl' AS formato, 'impresion' AS sector,
    'cubo-ejemplo.stl' AS archivo, 'cubo-ejemplo.stl' AS nombre_orig, 1493 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'cubo-ejemplo.stl');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Tetraedro (PolyData)' AS nombre,
    'Malla poligonal VTP (XML) con un escalar por punto — visor científico vtk.js.' AS descripcion,
    'vtp' AS formato, 'ingenieria' AS sector,
    'ejemplo-tetraedro.vtp' AS archivo, 'ejemplo-tetraedro.vtp' AS nombre_orig, 735 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'ejemplo-tetraedro.vtp');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Volumen radial (ImageData)' AS nombre,
    'Volumen VTI 9×9×9 con un blob radial — volume rendering con vtk.js.' AS descripcion,
    'vti' AS formato, 'radiologia' AS sector,
    'ejemplo-volumen.vti' AS archivo, 'ejemplo-volumen.vti' AS nombre_orig, 2745 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'ejemplo-volumen.vti');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Tetraedro legacy (.vtk)' AS nombre,
    'PolyData en formato VTK legacy (ASCII) — visor científico vtk.js.' AS descripcion,
    'vtk' AS formato, 'geologia' AS sector,
    'ejemplo-legacy.vtk' AS archivo, 'ejemplo-legacy.vtk' AS nombre_orig, 211 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'ejemplo-legacy.vtk');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Triángulo glTF (embebido)' AS nombre,
    'glTF 2.0 autocontenido (buffer en data URI) — malla con Three.js.' AS descripcion,
    'gltf' AS formato, 'otro' AS sector,
    'ejemplo-triangulo.gltf' AS archivo, 'ejemplo-triangulo.gltf' AS nombre_orig, 751 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'ejemplo-triangulo.gltf');

-- Modelos dentales reales de ejemplo (sector odontología) — escaneos STL de prótesis.
INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Base de dentadura maxilar' AS nombre,
    'Base protésica del maxilar superior (escaneo STL).' AS descripcion,
    'stl' AS formato, 'dental' AS sector,
    'Maxillary_Denture_Base.stl' AS archivo, 'Maxillary_Denture_Base.stl' AS nombre_orig, 13421584 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'Maxillary_Denture_Base.stl');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Dientes anteriores' AS nombre,
    'Grupo de dientes anteriores para prótesis (STL).' AS descripcion,
    'stl' AS formato, 'dental' AS sector,
    'Anterior_Teeth.stl' AS archivo, 'Anterior_Teeth.stl' AS nombre_orig, 2780184 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'Anterior_Teeth.stl');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Dientes posteriores (1)' AS nombre,
    'Grupo de dientes posteriores, cuadrante 1 (STL).' AS descripcion,
    'stl' AS formato, 'dental' AS sector,
    'Posterior_Teeth_1.stl' AS archivo, 'Posterior_Teeth_1.stl' AS nombre_orig, 2404584 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'Posterior_Teeth_1.stl');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Dientes posteriores (2)' AS nombre,
    'Grupo de dientes posteriores, cuadrante 2 (STL).' AS descripcion,
    'stl' AS formato, 'dental' AS sector,
    'Posterior_Teeth_2.stl' AS archivo, 'Posterior_Teeth_2.stl' AS nombre_orig, 2419084 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'Posterior_Teeth_2.stl');

-- Modelos vasculares reales de ejemplo (sector cirugía) — de VascularModel.com.
-- (Los archivos .vtp grandes >100 MB NO se versionan: GitHub los rechaza; quedan locales.)
INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Carótida (volumen)' AS nombre,
    'Volumen de TAC de la arteria carótida (convertido de .vtk binario a .vti) — volume rendering con vtk.js.' AS descripcion,
    'vti' AS formato, 'cirugia' AS sector,
    'carotid.vti' AS archivo, 'carotid.vti' AS nombre_orig, 602981 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'carotid.vti');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Modelo vascular 0157' AS nombre,
    'Modelo arterial de VascularModel (VTP) — superficie con vtk.js.' AS descripcion,
    'vtp' AS formato, 'cirugia' AS sector,
    '0157_0000.vtp' AS archivo, '0157_0000.vtp' AS nombre_orig, 7123879 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = '0157_0000.vtp');

INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Aorta pre-stent (STL)' AS nombre,
    'Aorta antes del stent, convertida a STL en 3D Slicer — visor de malla Three.js.' AS descripcion,
    'stl' AS formato, 'cirugia' AS sector,
    'AS1_SU0308_prestent.stl' AS archivo, 'AS1_SU0308_prestent.stl' AS nombre_orig, 6997484 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'AS1_SU0308_prestent.stl');

-- Arquitectura: Basílica del Pilar esquemática (modelo propio generado por código).
INSERT INTO modelos (nombre, descripcion, formato, sector, archivo, nombre_orig, bytes)
SELECT * FROM (SELECT
    'Basílica del Pilar (esquemática)' AS nombre,
    'Modelo volumétrico esquemático de la Basílica del Pilar (Zaragoza): cuerpo rectangular, 4 torres en las esquinas, cimborrio central y cupulines. Generado por código, no es una reconstrucción exacta.' AS descripcion,
    'stl' AS formato, 'arquitectura' AS sector,
    'basilica-pilar.stl' AS archivo, 'basilica-pilar.stl' AS nombre_orig, 2761320 AS bytes
) AS seed WHERE NOT EXISTS (SELECT 1 FROM modelos WHERE archivo = 'basilica-pilar.stl');

-- Añadir columna 'thumbnail' si no existe (miniatura generada en el navegador).
SET @add_thumbnail := (SELECT IF(COUNT(*) = 0,
    'ALTER TABLE modelos ADD COLUMN thumbnail VARCHAR(255) NULL AFTER subido_en',
    'SELECT 1') FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'modelos' AND COLUMN_NAME = 'thumbnail');
PREPARE st3 FROM @add_thumbnail; EXECUTE st3; DEALLOCATE PREPARE st3;

-- Atribución (fuente) de los modelos de ejemplo. Idempotente.
UPDATE modelos SET fuente = 'https://www.thingiverse.com/thing:3587989'
    WHERE archivo IN ('Maxillary_Denture_Base.stl','Anterior_Teeth.stl','Posterior_Teeth_1.stl','Posterior_Teeth_2.stl')
      AND (fuente IS NULL OR fuente = '');
UPDATE modelos SET fuente = 'https://www.vascularmodel.com/dataset.html#0'
    WHERE archivo IN ('carotid.vti','0157_0000.vtp','AS1_SU0308_prestent.stl','0089_H_PULM_ALGS_3D_RIGID.vtp','AS1_SU0308_prestent.vtp')
      AND (fuente IS NULL OR fuente = '');
