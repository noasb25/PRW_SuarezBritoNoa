<?php
session_start(); // Inicia la sesión para poder acceder a variables como el rol y el DNI del profesor
include 'conexion.php'; // Incluye la conexión con la base de datos

// Verificar si el usuario tiene el rol de profesor
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'profesor') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']); // Devuelve error si no es profesor
    exit;
}

// Recoge los datos enviados por AJAX mediante POST
$id_alumno = $_POST['id_alumno'] ?? null;        // ID del alumno
$puntuacion = $_POST['puntuacion'] ?? null;      // Puntuación (1 a 5)
$comentario = $_POST['comentario'] ?? null;      // Comentario (texto)

// Valida que se haya recibido al menos el ID del alumno y uno de los campos: puntuación o comentario
if (!$id_alumno || (!isset($puntuacion) && $comentario === null)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']); // Datos insuficientes
    exit;
}

$dniProfesor = $_SESSION['dni']; // Obtiene el DNI del profesor desde la sesión

try {
    // Obtener el ID del profesor a partir del DNI
    $stmt = $miPDO->prepare("SELECT id_profesor FROM Profesor WHERE dni = :dni");
    $stmt->execute([':dni' => $dniProfesor]);
    $id_profesor = $stmt->fetchColumn(); // Extrae el ID del resultado

    // Si no se encuentra el profesor, se detiene la ejecución
    if (!$id_profesor) {
        echo json_encode(['success' => false, 'message' => 'Profesor no encontrado']);
        exit;
    }

    // Verificar si ya existe una valoración para ese alumno por ese profesor
    $stmt = $miPDO->prepare("SELECT * FROM Valoracion WHERE id_profesor = :id_profesor AND id_alumno = :id_alumno");
    $stmt->execute([
        ':id_profesor' => $id_profesor,
        ':id_alumno' => $id_alumno
    ]);

    if ($stmt->rowCount() > 0) {
        // Si existe una valoración previa, se actualiza
        $campos = [];  // Array para almacenar los campos a actualizar
        $params = [    // Parámetros para la consulta
            ':id_profesor' => $id_profesor,
            ':id_alumno' => $id_alumno
        ];

        if (isset($puntuacion)) {
            $campos[] = "puntuacion = :puntuacion";
            $params[':puntuacion'] = $puntuacion;
        }

        if ($comentario !== null) {
            $campos[] = "comentario = :comentario";
            $params[':comentario'] = $comentario;
        }

        $campos[] = "fecha = NOW()"; // Actualiza también la fecha
        $sql = "UPDATE Valoracion SET " . implode(', ', $campos) . " WHERE id_profesor = :id_profesor AND id_alumno = :id_alumno";

        $update = $miPDO->prepare($sql);
        $update->execute($params); // Ejecuta la actualización
    } else {
        // Si no existe valoración, se inserta una nueva
        $insert = $miPDO->prepare("
            INSERT INTO Valoracion (id_alumno, id_profesor, puntuacion, comentario, fecha)
            VALUES (:id_alumno, :id_profesor, :puntuacion, :comentario, NOW())
        ");
        $insert->execute([
            ':id_alumno' => $id_alumno,
            ':id_profesor' => $id_profesor,
            ':puntuacion' => $puntuacion ?? 0, // Si no hay puntuación, se pone 0
            ':comentario' => $comentario
        ]);
    }

    echo json_encode(['success' => true]); // Respuesta exitosa en formato JSON
} catch (PDOException $e) {
    // En caso de error en la base de datos, se captura la excepción y se responde con error
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
