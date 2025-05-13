<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión, se va al login
    header('Location: login.php');
    exit;
}
// Se verifica si el usuario está autenticado al comprobar si la variable de sesión 'usuario' está definida. Si no está definida, el usuario es redirigido a la página de login.

// Conexión a la base de datos
include 'conexion.php';
// Se incluye el archivo de conexión a la base de datos para que se puedan realizar las consultas.

// Fecha actual
$fecha = date('d-m-Y');
// Se obtiene la fecha actual en formato 'día-mes-año' utilizando la función `date()`. Esta variable no se usa más adelante en el código, pero podría ser útil para mostrarla en la página si se desea.

// Verificar idioma desde la cookie
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; // Si no hay cookie, predeterminado a español
// Se verifica si existe una cookie llamada 'idioma'. Si existe, se asigna su valor a la variable $idioma. Si no existe, se asigna 'ES' (español) por defecto.

// Consulta para obtener los horarios de las clases de salto y doma
$query = "
    SELECT h.diaSemana, h.horaInicio, c.especialidad
    FROM Horario h
    JOIN Clase c ON h.id_clase = c.id_clase
    WHERE c.especialidad IN ('Salto', 'Doma')
    ORDER BY h.diaSemana, h.horaInicio
";
// Se realiza una consulta SQL que une las tablas `Horario` y `Clase` para obtener los horarios de las clases de salto y doma. Se filtra por las especialidades 'Salto' y 'Doma' y se ordena por día de la semana y hora de inicio.

$stmt = $miPDO->prepare($query);
// Se prepara la consulta utilizando la conexión a la base de datos.

$stmt->execute();
// Se ejecuta la consulta preparada.

$horarios = array();
// Se inicializa un arreglo vacío para almacenar los resultados de los horarios.

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Se recorren los resultados de la consulta utilizando un bucle `while` que obtiene cada fila de resultados.
    $horarios[$row['diaSemana']][] = [
        'horaInicio' => $row['horaInicio'],
        'especialidad' => $row['especialidad']
    ];
}
// Se agrupan los horarios por día de la semana en el arreglo `$horarios`. Cada día de la semana tendrá un array de clases, con su hora de inicio y especialidad.

?>

<!DOCTYPE html>
<html lang="<?php echo $idioma == 'EN' ? 'en' : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Estilos/ver_horario.css?v=1.0">
    <title><?php echo $idioma == 'EN' ? 'General Class Schedule' : 'Horario General de Clases'; ?></title>
    <!-- El título de la página cambia según el idioma seleccionado. -->
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica">
        </div>
        <h1><?php echo $idioma == 'EN' ? 'Horse riding - General Class Schedule for Show Jumping and Dressage' : 'Hípica - Horario General de Clases de Salto y Doma'; ?></h1>
        <!-- El título del encabezado también cambia según el idioma. -->
    </header>

    <main>
        <div class="subrayado">
            <?php echo $idioma == 'EN' ? 'Week of May 19, 2025' : 'Semana del 19 de mayo de 2025'; ?>
        </div>
        <!-- Aquí se muestra el título de la semana en función del idioma. -->
        
        <!-- Tabla de horarios -->
        <table border="1">
            <thead>
                <tr>
                    <th><?php echo $idioma == 'EN' ? 'Schedule' : 'Horario'; ?></th>
                    <th><?php echo $idioma == 'EN' ? 'Monday' : 'Lunes'; ?></th>
                    <th><?php echo $idioma == 'EN' ? 'Tuesday' : 'Martes'; ?></th>
                    <th><?php echo $idioma == 'EN' ? 'Wednesday' : 'Miércoles'; ?></th>
                    <th><?php echo $idioma == 'EN' ? 'Thursday' : 'Jueves'; ?></th>
                    <th><?php echo $idioma == 'EN' ? 'Friday' : 'Viernes'; ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- 10:00 - 11:00 -->
                <tr>
                    <td>10:00</td>
                    <?php 
                    foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes'] as $dia) {
                        $encontrado = false;
                        if (isset($horarios[$dia])) {
                            foreach ($horarios[$dia] as $horario) {
                                if ($horario['horaInicio'] == '10:00:00') {
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
                            echo "<td>" . ($idioma == 'EN' ? 'No class' : 'No hay clase') . "</td>";
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
                            echo "<td>" . ($idioma == 'EN' ? 'No class' : 'No hay clase') . "</td>";
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
                            echo "<td>" . ($idioma == 'EN' ? 'No class' : 'No hay clase') . "</td>";
                        }
                    }
                    ?>
                </tr>

                <!-- 13:00 - 16:00 (Descanso) -->
                <tr>
                    <td colspan="6"><?php echo $idioma == 'EN' ? 'Break' : 'Descanso'; ?></td>
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
                            echo "<td>" . ($idioma == 'EN' ? 'No class' : 'No hay clase') . "</td>";
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
                            echo "<td>" . ($idioma == 'EN' ? 'No class' : 'No hay clase') . "</td>";
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
                            echo "<td>" . ($idioma == 'EN' ? 'No class' : 'No hay clase') . "</td>";
                        }
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </main>

    <!-- Botones de acción -->
    <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
        <a href="bienvenida.php"><button type="button"><?php echo $idioma == 'ES' ? 'Volver Atrás' : 'Go Back'; ?></button></a>
        <a href="preferencias.php"><button type="button"><?php echo $idioma == 'ES' ? 'Preferencias' : 'Preferences'; ?></button></a>
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
