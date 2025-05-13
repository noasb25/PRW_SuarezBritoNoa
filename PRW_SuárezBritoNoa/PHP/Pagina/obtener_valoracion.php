<?php
session_start(); // Inicia la sesión para acceder a variables como $_SESSION

include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

// Verifica si el usuario ha iniciado sesión y tiene el rol de profesor
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    http_response_code(403); // Devuelve código de estado 403 (Prohibido)
    echo "Acceso denegado."; // Muestra mensaje de acceso denegado
    exit; // Termina el script para evitar continuar con la ejecución
}

$dni_profesor = $_SESSION['dni']; // Obtiene el DNI del profesor desde la sesión
$dni_alumno = $_GET['dni_alumno'] ?? null; // Obtiene el DNI del alumno desde la URL (GET). Si no existe, asigna null

// Si no se ha proporcionado el DNI del alumno
if (!$dni_alumno) {
    http_response_code(400); // Devuelve código de estado 400 (Solicitud incorrecta)
    echo "Falta DNI del alumno."; // Muestra mensaje de error
    exit; // Termina el script
}

try {
    // Obtener el ID del profesor a partir de su DNI
    $stmtProf = $miPDO->prepare("SELECT id_profesor FROM Profesor WHERE dni = :dni");
    $stmtProf->execute(['dni' => $dni_profesor]);
    $id_profesor = $stmtProf->fetchColumn(); // Extrae el ID del profesor

    // Obtener el ID del alumno a partir de su DNI
    $stmtAlu = $miPDO->prepare("SELECT id_alumno FROM Alumno WHERE dni = :dni");
    $stmtAlu->execute(['dni' => $dni_alumno]);
    $id_alumno = $stmtAlu->fetchColumn(); // Extrae el ID del alumno

    // Obtener la valoración registrada entre este profesor y este alumno
    $stmtVal = $miPDO->prepare("
        SELECT valoracion FROM Valoracion
        WHERE id_profesor = :id_profesor AND id_alumno = :id_alumno
    ");
    $stmtVal->execute([
        'id_profesor' => $id_profesor,
        'id_alumno' => $id_alumno
    ]);

    $valoracion = $stmtVal->fetchColumn(); // Extrae el valor numérico de la valoración
    echo $valoracion ?: 0; // Si hay valoración la muestra, si no, muestra 0

} catch (Exception $e) {
    http_response_code(500); // Devuelve código de error interno del servidor
    echo "Error: " . $e->getMessage(); // Muestra mensaje con el error capturado
}
