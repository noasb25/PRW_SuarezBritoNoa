<?php
session_start(); // Inicia la sesión para poder acceder a las variables almacenadas, como el usuario y su rol.

include 'conexion.php'; // Incluye el archivo de conexión para poder interactuar con la base de datos.

// Asegurarse de que el usuario es un profesor
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    http_response_code(403); // Devuelve el código de estado 403 (Prohibido)
    echo "Acceso denegado."; // Muestra un mensaje de acceso denegado
    exit; // Detiene la ejecución del script
}

$dni_profesor = $_SESSION['dni']; // Obtiene el DNI del profesor desde la sesión
$dni_alumno = $_POST['dni_alumno'] ?? null; // Obtiene el DNI del alumno desde la petición POST
$valoracion = $_POST['valoracion'] ?? null; // Obtiene la valoración enviada (número de 1 a 5)

// Validar datos recibidos
if (!$dni_alumno || !$valoracion || $valoracion < 1 || $valoracion > 5) {
    http_response_code(400); // Devuelve código de error 400 (Solicitud incorrecta)
    echo "Datos inválidos."; // Muestra mensaje de error
    exit; // Detiene la ejecución
}

try {
    // Obtener el ID del profesor a partir de su DNI
    $stmtProf = $miPDO->prepare("SELECT id_profesor FROM Profesor WHERE dni = :dni");
    $stmtProf->execute(['dni' => $dni_profesor]); // Ejecuta la consulta pasando el DNI del profesor
    $id_profesor = $stmtProf->fetchColumn(); // Obtiene el resultado (id_profesor)

    // Obtener el ID del alumno a partir de su DNI
    $stmtAlu = $miPDO->prepare("SELECT id_alumno FROM Alumno WHERE dni = :dni");
    $stmtAlu->execute(['dni' => $dni_alumno]); // Ejecuta la consulta pasando el DNI del alumno
    $id_alumno = $stmtAlu->fetchColumn(); // Obtiene el resultado (id_alumno)

    // Validar que se hayan encontrado ambos IDs
    if (!$id_profesor || !$id_alumno) {
        throw new Exception("No se encontraron IDs válidos."); // Lanza una excepción si alguno no se encuentra
    }

    // Insertar o actualizar la valoración
    $stmtVal = $miPDO->prepare("
        INSERT INTO Valoracion (id_profesor, id_alumno, valoracion) 
        VALUES (:id_profesor, :id_alumno, :valoracion)
        ON DUPLICATE KEY UPDATE valoracion = :valoracion
    ");
    $stmtVal->execute([
        'id_profesor' => $id_profesor, // Asocia el ID del profesor
        'id_alumno' => $id_alumno,     // Asocia el ID del alumno
        'valoracion' => $valoracion   // Asocia la puntuación (valoración)
    ]);

    echo "Valoración guardada"; // Muestra mensaje de éxito
} catch (Exception $e) {
    http_response_code(500); // Código de error interno del servidor
    echo "Error: " . $e->getMessage(); // Muestra el mensaje de error
}
?>
