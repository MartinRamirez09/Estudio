<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecomerce');

// Configuración general
define('SITE_URL', 'http://localhost/ecomerce/index.php'); // Ajusta según tu configuración
define('CURRENCY', '$');

// Configuración de sesión
ini_set('session.cookie_lifetime', 60 * 60 * 24); // 24 horas
ini_set('session.gc_maxlifetime', 60 * 60 * 24); // 24 horas

?>
