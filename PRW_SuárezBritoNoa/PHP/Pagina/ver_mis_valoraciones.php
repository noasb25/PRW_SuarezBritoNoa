<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión.

// Verifica si el usuario ha iniciado sesión y si su rol es "alumno"
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'alumno') {
    header('Location: login.php'); // Si no es un alumno autenticado, redirige al login.
    exit; // Detiene la ejecución.
}

$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; // Verifica la cookie del idioma, si no existe, asigna 'ES'.

include 'conexion.php'; // Conecta con la base de datos.

// Se obtiene el DNI del alumno desde la sesión
$dniAlumno = $_SESSION['dni'];

// Se consulta el ID del alumno usando su DNI
$stmt = $miPDO->prepare("SELECT id_alumno FROM Alumno WHERE dni = :dni");
$stmt->execute([':dni' => $dniAlumno]);
$id_alumno = $stmt->fetchColumn(); // Guarda el ID del alumno.

// Se inicializa el mensaje de error
$mensaje_error = '';
if (!$id_alumno) {
    // Si no se encontró el alumno, se prepara un mensaje de error
    $mensaje_error = $idioma == 'ES' ? 'Alumno no encontrado.' : 'Student not found.';
} else {
    // Si se encontró el alumno, se consultan las valoraciones que ha recibido
    $query = "
        SELECT p.nombre AS nombre_profesor, p.apellido AS apellido_profesor,
               v.puntuacion, v.comentario, DATE_FORMAT(v.fecha, '%d-%m-%Y') AS fecha
        FROM Valoracion v
        JOIN Profesor p ON v.id_profesor = p.id_profesor
        WHERE v.id_alumno = :id_alumno
        ORDER BY v.fecha DESC
    ";
    $stmt = $miPDO->prepare($query);
    $stmt->execute([':id_alumno' => $id_alumno]);
    $valoraciones = $stmt->fetchAll(PDO::FETCH_ASSOC); // Se almacenan todas las valoraciones.
}
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma == 'ES' ? 'es' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo $idioma == 'ES' ? 'Mis Valoraciones' : 'My Ratings'; ?></title>
    <link rel="stylesheet" href="../Estilos/ver_alumnos.css?v=1.0">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica">
        </div>
        <h1><?php echo $idioma == 'ES' ? 'Valoraciones Recibidas' : 'Received Ratings'; ?></h1>
    </header>

    <main>
        <h2><?php echo $idioma == 'ES' ? 'Comentarios de Profesores' : 'Teacher Comments'; ?></h2>
        
        <!-- Si hay mensaje de error, se muestra -->
        <?php if (!empty($mensaje_error)): ?>
            <p><?php echo htmlspecialchars($mensaje_error); ?></p>
        
        <!-- Si hay valoraciones, se muestran en tabla -->
        <?php elseif (count($valoraciones) > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th><?php echo $idioma == 'ES' ? 'Profesor' : 'Teacher'; ?></th>
                        <th><?php echo $idioma == 'ES' ? 'Puntuación' : 'Rating'; ?></th>
                        <th><?php echo $idioma == 'ES' ? 'Comentario' : 'Comment'; ?></th>
                        <th><?php echo $idioma == 'ES' ? 'Fecha' : 'Date'; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($valoraciones as $valoracion): ?>
                        <tr>
                            <!-- Muestra nombre y apellido del profesor -->
                            <td><?php echo htmlspecialchars($valoracion['nombre_profesor'] . ' ' . $valoracion['apellido_profesor']); ?></td>
                            
                            <!-- Muestra puntuación en forma de estrellas -->
                            <td>
                                <?php
                                $p = (int)$valoracion['puntuacion'];
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $p ? '★' : '☆';
                                }
                                ?>
                            </td>

                            <!-- Muestra el comentario del profesor -->
                            <td><?php echo htmlspecialchars($valoracion['comentario']); ?></td>
                            
                            <!-- Muestra la fecha de la valoración -->
                            <td><?php echo htmlspecialchars($valoracion['fecha']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <!-- Si no hay valoraciones se muestra un mensaje -->
        <?php else: ?>
            <p><?php echo $idioma == 'ES' ? 'Todavía no tienes valoraciones.' : 'You have no ratings yet.'; ?></p>
        <?php endif; ?>
    </main>

    <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px; margin-bottom: 40px;">
        <a href="bienvenida.php"><button type="button"><?php echo $idioma == 'ES' ? 'Volver Atrás' : 'Go Back'; ?></button></a>
        <a href="preferencias.php"><button><?php echo $idioma == 'EN' ? 'Preferences' : 'Preferencias'; ?></button></a>
    </div>

    <!-- Footer -->
    <footer>
        <p>
            <?php
                if ($idioma === 'EN') {
                    echo "&copy; 2024 Horse riding. All rights reserved.";
                    echo "<br><br>Follow us on: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                } else {
                    echo "&copy; 2024 Hípica. Todos los derechos reservados.";
                    echo "<br><br>Síguenos en: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                }
            ?>
        </p>
    </footer>
</body>
</html>
