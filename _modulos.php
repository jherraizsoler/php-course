<?php
/**
 * Datos de los módulos del curso. Compartido por index.php y modulo.php.
 * Una sola fuente de verdad para la navegación.
 */

declare(strict_types=1);

return [
    ['slug' => '00-entorno',                 'num' => '00', 'icon' => '🛠️', 'level' => 'Base',     'title' => 'Entorno de trabajo',        'desc' => 'MAMP, PHP CLI, Composer, Git y VS Code. Monta y comprueba tu entorno.'],
    ['slug' => '01-php-fundamentos',          'num' => '01', 'icon' => '🐘', 'level' => 'Base',     'title' => 'Fundamentos de PHP',        'desc' => 'Sintaxis, variables, tipos, operadores y control de flujo.'],
    ['slug' => '02-funciones-arrays-strings', 'num' => '02', 'icon' => '🧩', 'level' => 'Base',     'title' => 'Funciones, arrays y strings','desc' => 'Funciones tipadas, arrays asociativos y manipulación de texto.'],
    ['slug' => '03-poo',                      'num' => '03', 'icon' => '🏛️', 'level' => 'Medio',    'title' => 'POO',                        'desc' => 'Clases, herencia, interfaces, traits y namespaces. La base de CI.'],
    ['slug' => '04-php-avanzado',             'num' => '04', 'icon' => '⚙️', 'level' => 'Medio',    'title' => 'PHP avanzado + Composer',    'desc' => 'Excepciones, Composer y autoload PSR-4. El pegamento moderno.'],
    ['slug' => '05-php-web',                  'num' => '05', 'icon' => '🌐', 'level' => 'Medio',    'title' => 'PHP y la web',               'desc' => 'Formularios, sesiones, PDO + MySQL y seguridad (XSS, SQLi).'],
    ['slug' => '06-buenas-practicas',         'num' => '06', 'icon' => '✨', 'level' => 'Pro',      'title' => 'Buenas prácticas',           'desc' => 'PSR, SOLID, código limpio y testing con PHPUnit.'],
    ['slug' => '07-codeigniter3',             'num' => '07', 'icon' => '🔥', 'level' => 'Pro',      'title' => 'CodeIgniter 3',              'desc' => 'MVC, routing, Query Builder, validación, HMVC y hooks.'],
    ['slug' => '08-librerias-clave',          'num' => '08', 'icon' => '📚', 'level' => 'Pro',      'title' => 'Librerías clave',            'desc' => 'Carbon, Faker, Guzzle, Collections, PHPMailer y TCPDF.'],
    ['slug' => '09-codeigniter4-intro',       'num' => '09', 'icon' => '🚀', 'level' => 'Extra',    'title' => 'Intro a CodeIgniter 4',      'desc' => 'Comparativa CI3 vs CI4, namespaces y migración.'],
    ['slug' => 'proyectos',                   'num' => '🏗️', 'icon' => '🏗️', 'level' => 'Práctica', 'title' => 'Proyectos prácticos',        'desc' => 'CRUD en PHP puro y CRUD en CodeIgniter 3. Junta todo lo aprendido.'],
];
