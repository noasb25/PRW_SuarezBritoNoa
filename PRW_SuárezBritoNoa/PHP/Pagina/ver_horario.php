<?php
session_start();

// Verificar si la cookie de idioma existe, si no, asignar 'ES' como valor predeterminado
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES';
// Se comprueba si la cookie 'idioma' existe, si no se asigna 'ES' (español) como valor predeterminado.

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
// Se verifica si el usuario está autenticado comprobando si la variable de sesión 'usuario' está definida. Si no lo está, el usuario es redirigido a la página de login.

// Conexión a la base de datos
include 'conexion.php';
// Se incluye el archivo de conexión a la base de datos para que podamos ejecutar consultas.

// Fecha actual
$fecha = date('d-m-Y');
$semana = $idioma == 'ES' ? 'Semana del 19 de mayo de 2025' : 'Week of May 19, 2025';
// Se obtiene la fecha actual en formato 'día-mes-año'. Además, se establece el texto para la semana según el idioma.

// Consulta para obtener los horarios de las clases de salto y doma
$query = "
    SELECT h.diaSemana, h.horaInicio, c.especialidad
    FROM Horario h
    JOIN Clase c ON h.id_clase = c.id_clase
    WHERE c.especialidad IN ('Salto', 'Doma')
    ORDER BY h.diaSemana, h.horaInicio
";
// Se realiza una consulta SQL que obtiene los horarios de las clases de salto y doma, uniéndo las tablas `Horario` y `Clase`. Los resultados se ordenan por día de la semana y hora de inicio.

$stmt = $miPDO->prepare($query);
// Se prepara la consulta para ser ejecutada.

$stmt->execute();
// Se ejecuta la consulta.

$horarios = array();
// Se inicializa un arreglo vacío para almacenar los resultados de los horarios.

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $horarios[$row['diaSemana']][] = [
        'horaInicio' => $row['horaInicio'],
        'especialidad' => $row['especialidad']
    ];
}
// Se recorren los resultados obtenidos de la consulta y se agrupan por día de la semana. El arreglo `$horarios` almacena los horarios y especialidades para cada día.

?>

<!DOCTYPE html>
<html lang="<?php echo $idioma == 'ES' ? 'es' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Estilos/ver_horario.css">
    <title><?php echo $idioma == 'ES' ? 'Horario General de Clases' : 'General Class Schedule'; ?></title>
    <!-- Se define el título de la página dependiendo del idioma seleccionado. -->
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica">
        </div>
        <h1><?php echo $idioma == 'ES' ? 'Hípica - Horario General de Clases de Salto y Doma' : 'Horse riding - General Schedule of Jumping and Dressage Classes'; ?></h1>
        <!-- El título del encabezado cambia según el idioma, indicando que se trata del horario de clases de salto y doma. -->
    </header>

    <main>
        <div class="subrayado"><?php echo $semana; ?></div>
        <!-- Se muestra el título de la semana según el idioma. -->

        <!-- Tabla de horarios -->
        <table border="1">
            <thead>
                <tr>
                    <th><?php echo $idioma == 'ES' ? 'Horario' : 'Schedule'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Lunes' : 'Monday'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Martes' : 'Tuesday'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Miércoles' : 'Wednesday'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Jueves' : 'Thursday'; ?></th>
                    <th><?php echo $idioma == 'ES' ? 'Viernes' : 'Friday'; ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- Cada fila de la tabla representa un intervalo de tiempo para las clases -->

                <!-- 10:00 - 11:00 -->
                <tr>
                    <td>10:00</td>
                    <?php 
                    // Se recorre el arreglo de días de la semana
                    foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes'] as $dia) {
                        $encontrado = false;
                        // Se inicializa una variable para comprobar si se encuentra una clase a esa hora.
                        if (isset($horarios[$dia])) {
                            // Si existen horarios para el día
                            foreach ($horarios[$dia] as $horario) {
                                if ($horario['horaInicio'] == '10:00:00') {
                                    // Si se encuentra una clase a la hora correspondiente
                                    $especialidad = $horario['especialidad'];
                                    if ($idioma == 'EN') {
                                        $especialidad = ($especialidad == 'salto') ? 'jumping' : (($especialidad == 'doma') ? 'dressage' : $especialidad);
                                    }
                                    echo "<td>" . $especialidad . "</td>";  
                                    $encontrado = true;
                                    break;
                                }
                            }
                        }
                        if (!$encontrado) {
                            // Si no se encuentra ninguna clase a esa hora, se muestra un mensaje diciendo que no hay clase.
                            echo "<td>" . ($idioma == 'ES' ? 'No hay clase' : 'No class') . "</td>";
                        }
                    }
                    ?>
                </tr>

                <!-- 11:00 - 12:00 -->
                <tr>
                    <td>11:00</td>
                    <?php 
                    foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes'] as $dia) {
                        $encontrado = false;
                        if (isset($horarios[$dia])) {
                            foreach ($horarios[$dia] as $horario) {
                                if ($horario['horaInicio'] == '11:00:00') {
                                    $especialidad = $horario['especialidad'];
                                    if ($idioma == 'EN') {
                                        $especialidad = ($especialidad == 'salto') ? 'jumping' : (($especialidad == 'doma') ? 'dressage' : $especialidad);
                                    }
                                    echo "<td>" . $especialidad . "</td>";  
                                    $encontrado = true;
                                    break;
                                }
                            }
                        }
                        if (!$encontrado) {
                            echo "<td>" . ($idioma == 'ES' ? 'No hay clase' : 'No class') . "</td>";
                        }
                    }
                    ?>
                </tr>

                <!-- 12:00 - 13:00 -->
                <tr>
                    <td>12:00</td>
                    <?php 
                    foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes'] as $dia) {
                        $encontrado = false;
                        if (isset($horarios[$dia])) {
                            foreach ($horarios[$dia] as $horario) {
                                if ($horario['horaInicio'] == '12:00:00') {
                                    $especialidad = $horario['especialidad'];
                                    if ($idioma == 'EN') {
                                        $especialidad = ($especialidad == 'salto') ? 'jumping' : (($especialidad == 'doma') ? 'dressage' : $especialidad);
                                    }
                                    echo "<td>" . $especialidad . "</td>";  
                                    $encontrado = true;
                                    break;
                                }
                            }
                        }
                        if (!$encontrado) {
                            echo "<td>" . ($idioma == 'ES' ? 'No hay clase' : 'No class') . "</td>";
                        }
                    }
                    ?>
                </tr>

                <!-- 13:00 - 16:00 (Descanso) -->
                <tr>
                    <td colspan="6"><?php echo $idioma == 'ES' ? 'Descanso' : 'Break'; ?></td>
                </tr>

                <!-- 16:00 - 17:00 -->
                <tr>
                    <td>16:00</td>
                    <?php 
                    foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes'] as $dia) {
                        $encontrado = false;
                        if (isset($horarios[$dia])) {
                            foreach ($horarios[$dia] as $horario) {
                                if ($horario['horaInicio'] == '16:00:00') {
                                    $especialidad = $horario['especialidad'];
                                    if ($idioma == 'EN') {
                                        $especialidad = ($especialidad == 'salto') ? 'jumping' : (($especialidad == 'doma') ? 'dressage' : $especialidad);
                                    }
                                    echo "<td>" . $especialidad . "</td>";  
                                    $encontrado = true;
                                    break;
                                }
                            }
                        }
                        if (!$encontrado) {
                            echo "<td>" . ($idioma == 'ES' ? 'No hay clase' : 'No class') . "</td>";
                        }
                    }
                    ?>
                </tr>

                <!-- 17:00 - 18:00 -->
                <tr>
                    <td>17:00</td>
                    <?php 
                    foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes'] as $dia) {
                        $encontrado = false;
                        if (isset($horarios[$dia])) {
                            foreach ($horarios[$dia] as $horario) {
                                if ($horario['horaInicio'] == '17:00:00') {
                                    $especialidad = $horario['especialidad'];
                                    if ($idioma == 'EN') {
                                        $especialidad = ($especialidad == 'salto') ? 'jumping' : (($especialidad == 'doma') ? 'dressage' : $especialidad);
                                    }
                                    echo "<td>" . $especialidad . "</td>";  
                                    $encontrado = true;
                                    break;
                                }
                            }
                        }
                        if (!$encontrado) {
                            echo "<td>" . ($idioma == 'ES' ? 'No hay clase' : 'No class') . "</td>";
                        }
                    }
                    ?>
                </tr>

                <!-- 18:00 - 19:00 -->
                <tr>
                    <td>18:00</td>
                    <?php 
                    foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes'] as $dia) {
                        $encontrado = false;
                        if (isset($horarios[$dia])) {
                            foreach ($horarios[$dia] as $horario) {
                                if ($horario['horaInicio'] == '18:00:00') {
                                    $especialidad = $horario['especialidad'];
                                    if ($idioma == 'EN') {
                                        $especialidad = ($especialidad == 'salto') ? 'jumping' : (($especialidad == 'doma') ? 'dressage' : $especialidad);
                                    }
                                    echo "<td>" . $especialidad . "</td>";  
                                    $encontrado = true;
                                    break;
                                }
                            }
                        }
                        if (!$encontrado) {
                            echo "<td>" . ($idioma == 'ES' ? 'No hay clase' : 'No class') . "</td>";
                        }
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </main>
    
    <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
        <!-- Botones de navegación -->
        <a href="profesores.php"><button type="button"><?php echo $idioma == 'ES' ? 'Volver Atrás' : 'Go Back'; ?></button></a>
        <!-- Botón para volver atrás a la página de profesores. -->
        <a href="preferencias.php"><button type="button"><?php echo $idioma == 'ES' ? 'Preferencias' : 'Preferences'; ?></button></a>
        <!-- Botón para ir a la selección de idioma -->
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
