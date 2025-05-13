<?php

//Variables de conexión
$hostDB = '127.0.0.1';  // Dirección del servidor MySQL
$nombreDB = 'bdhipica';  // Nombre de la base de datos
$usuarioDB = 'root';     // Usuario de la base de datos
$contraDB = '';          // Contraseña (vacío en XAMPP por defecto)

// Establecer la conexión
$link = "mysql:host=$hostDB;dbname=$nombreDB";

try {
    $miPDO = new PDO($link, $usuarioDB, $contraDB);
    $miPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Establecer el modo de error
    // echo 'Conectado'; // Descomenta si deseas ver el mensaje de conexión exitosa
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();  // Mostrar error en caso de fallo de conexión
}
?>
