<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Conexión a la base de datos del CRUD (curso → tabla usuarios).
| Ajustada a este MAMP: MySQL en el puerto 8889, usuario/clave root.
| Importa el esquema desde ../../../05-php-web/ejemplos/db/schema.sql
| -------------------------------------------------------------------
*/

$active_group = 'default';
$query_builder = true;

// Host/puerto/usuario/clave desde variables de entorno (Docker) con fallback a MAMP.
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_PORT = (int) (getenv('DB_PORT') ?: 8889);
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root';

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $DB_HOST,
	'username' => $DB_USER,
	'password' => $DB_PASS,
	'database' => 'curso',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => false,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => false,
	'cachedir' => '',
	'char_set' => 'utf8mb4',
	'dbcollat' => 'utf8mb4_general_ci',
	'swap_pre' => '',
	'encrypt' => false,
	'compress' => false,
	'stricton' => false,
	'failover' => array(),
	'save_queries' => true,
	'port' => $DB_PORT,
);
