<?php
session_start();

// Verificar si la cookie de idioma existe, si no, asignar 'ES' como valor predeterminado
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES';
// Esta línea verifica si la cookie 'idioma' está presente. Si existe, se asigna su valor a la variable $idioma. Si no existe, se asigna 'ES' por defecto, es decir, español.

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Si no hay sesión de usuario, redirigir al login
    exit;
}
// Aquí se verifica si el usuario está autenticado al comprobar si la sesión tiene un valor para 'usuario'. Si no está autenticado, se redirige a la página de login.

// Conexión a la base de datos
include 'conexion.php';
// Aquí se incluye el archivo de conexión a la base de datos para poder ejecutar consultas.

// Inicializar los errores
$errores = [];
// Se crea un array vacío para almacenar errores que puedan surgir durante el procesamiento del formulario.

// Procesar el formulario para borrar un alumno
if (isset($_POST['borrar_usuario'])) {
    // Verifica si se ha enviado el formulario de borrado de un alumno.

    $dni_a_borrar = trim($_POST['dni_alumno']);
    // Se recoge el DNI del alumno a borrar desde el formulario. 'trim()' elimina espacios adicionales.

    // Validación: Verificar si el DNI está vacío
    if (empty($dni_a_borrar)) {
        // Si el DNI está vacío, se agrega un error al array $errores.
        $errores[] = $idioma == 'ES' ? "Por favor, ingrese un DNI para borrar el alumno." : "Please enter a DNI to delete the student.";
    } else {
        try {
            // Eliminar las dependencias y el alumno
            $query_nivelespecialidad = "DELETE FROM nivelespecialidad WHERE id_alumno IN (SELECT id_alumno FROM Alumno WHERE dni = :dni)";
            $stmt_nivelespecialidad = $miPDO->prepare($query_nivelespecialidad);
            $stmt_nivelespecialidad->bindParam(':dni', $dni_a_borrar, PDO::PARAM_STR);
            $stmt_nivelespecialidad->execute();

            $query_clasealumno = "DELETE FROM clasealumno WHERE id_alumno IN (SELECT id_alumno FROM Alumno WHERE dni = :dni)";
            $stmt_clasealumno = $miPDO->prepare($query_clasealumno);
            $stmt_clasealumno->bindParam(':dni', $dni_a_borrar, PDO::PARAM_STR);
            $stmt_clasealumno->execute();

            $query = "DELETE FROM Alumno WHERE dni = :dni";
            $stmt = $miPDO->prepare($query);
            $stmt->bindParam(':dni', $dni_a_borrar, PDO::PARAM_STR);
            $stmt->execute();

            $mensaje_borrado = $idioma == 'ES' ? "Alumno con DNI '$dni_a_borrar' eliminado con éxito." : "Student with DNI '$dni_a_borrar' deleted successfully.";
        } catch (PDOException $e) {
            $errores[] = $idioma == 'ES' ? "Error al borrar el alumno: " . $e->getMessage() : "Error deleting the student: " . $e->getMessage();
        }
    }
}

// Consulta para obtener todos los alumnos con sus IDs y posibles valoraciones
$query = "
    SELECT a.id_alumno, a.dni, a.nombre, a.apellido, a.telefono, a.fechaNacimiento, a.seguro, a.foto_perfil, v.puntuacion, v.comentario
    FROM Alumno a
    LEFT JOIN Valoracion v ON a.id_alumno = v.id_alumno AND v.id_profesor = (SELECT id_profesor FROM Profesor WHERE dni = :dni_profesor)
    ORDER BY a.apellido, a.nombre
";
$stmt = $miPDO->prepare($query);
$stmt->bindParam(':dni_profesor', $_SESSION['dni'], PDO::PARAM_STR);
$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $idioma == 'ES' ? 'Ver Todos los Alumnos' : 'View All Students'; ?></title>
    <link rel="stylesheet" href="../Estilos/ver_alumnos.css?v=1.0">
    <script src="../JS/valoraciones.js" defer></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica">
        </div>
        <h1><?php echo $idioma == 'ES' ? 'Hípica - Ver Todos los Alumnos' : 'Horse Riding - View All Students'; ?></h1>
    </header>

    <main>
        <h2><?php echo $idioma == 'ES' ? 'Lista de Alumnos' : 'List of Students'; ?></h2>

        <table border="1">
            <thead> <!-- Encabezado de la tabla -->
                <tr>
                    <th><?php echo $idioma == 'ES' ? 'Foto' : 'Photo'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'DNI' : 'DNI'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Nombre' : 'Name'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Apellido' : 'Last Name'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Teléfono' : 'Phone'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Fecha de Nacimiento' : 'Date of Birth'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Seguro' : 'Insurance'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Valoración' : 'Rating'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Comentario' : 'Comment'; ?></th> <!-- Nueva columna -->
                </tr>
            </thead>
            <tbody> <!-- Cuerpo de la tabla donde se listan los alumnos -->
                <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td>
                        <img src="../Imagenes/Perfiles/<?php echo htmlspecialchars($alumno['foto_perfil'] ?? 'perfil.defecto.png'); ?>" alt="Foto" width="60" height="60" style="border-radius: 50%; object-fit: cover;">
                    </td>
                    <td><?php echo htmlspecialchars($alumno['dni']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['fechaNacimiento']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['seguro']); ?></td>
                    <td>
                        <!-- Muestra la valoracion por estrellas -->
                        <div class="rating" data-alumno-id="<?php echo $alumno['id_alumno']; ?>">
                            <?php
                            $puntuacion = (int)$alumno['puntuacion'];
                            for ($i = 1; $i <= 5; $i++) {
                                $estrella = $i <= $puntuacion ? '★' : '☆';
                                echo "<span class='star'>" . $estrella . "</span>";
                            }
                            ?>
                        </div>
                    </td>
                    <td>
                        <textarea class="comentario"
                                data-alumno-id="<?php echo $alumno['id_alumno']; ?>"
                                placeholder="<?php echo $idioma == 'EN' ? 'Add a comment' : 'Añade un comentario'; ?>"><?php echo htmlspecialchars($alumno['comentario'] ?? ''); ?></textarea>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <div class="borrar-alumno">
            <h3><?php echo $idioma == 'ES' ? '¿Te gustaría borrar algún alumno?' : 'Would you like to delete a student?'; ?></h3>
            <form action="ver_alumnos.php" method="POST">
                <label for="dni_alumno"><?php echo $idioma == 'ES' ? 'DNI del alumno a borrar:' : 'DNI of the student to delete:'; ?></label>
                <input type="text" id="dni_alumno" name="dni_alumno">
                <?php if (!empty($errores)): ?>
                    <div class="error-container">
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <button type="submit" name="borrar_usuario"><?php echo $idioma == 'ES' ? 'Borrar Alumno' : 'Delete Student'; ?></button>
            </form>
            <?php if (isset($mensaje_borrado)) echo "<p>$mensaje_borrado</p>"; ?>
        </div>
    </main>

    <div class="botones-inferiores" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
        <a href="profesores.php"><button type="button"><?php echo $idioma == 'ES' ? 'Volver Atrás' : 'Go Back'; ?></button></a>
        <a href="preferencias.php"><button><?php echo $idioma == 'EN' ? 'Preferences' : 'Preferencias'; ?></button></a>
    </div>

    <!-- Footer -->
    <footer>
        <p>
            <?php
                if (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN') {
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
