<?php
session_start(); // Inicia la sesión para poder acceder a las variables de sesión del usuario.

// Verificar si el usuario ha iniciado sesión y tiene el rol de profesor
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header('Location: login.php'); // Si el usuario no está autenticado o no es un profesor, redirige a la página de login.
    exit; // Detiene la ejecución del código para evitar que el usuario acceda a la página sin permisos.
}

// Conexión con la base de datos
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos para poder interactuar con ella.

// Verificar que el DNI esté disponible en la sesión
if (!isset($_SESSION['dni'])) {
    echo "Error: No se ha encontrado el DNI en la sesión."; // Si no se encuentra el DNI en la sesión, muestra un mensaje de error.
    exit; // Detiene la ejecución si el DNI no está disponible.
}

$dniProfesor = $_SESSION['dni']; // Obtiene el DNI del profesor desde la sesión para usarlo en las consultas.

// Obtener los datos actuales del profesor
$query = "SELECT dni, nombre, apellido, telefono, fechaContratacion, salario FROM Profesor WHERE dni = :dniProfesor";
$stmt = $miPDO->prepare($query); 
// Prepara la consulta SQL para obtener los datos del profesor, buscando por el DNI.

$stmt->bindParam(':dniProfesor', $dniProfesor, PDO::PARAM_STR); 
// Asocia el valor de $dniProfesor al parámetro de la consulta SQL.

$stmt->execute(); // Ejecuta la consulta para obtener los datos del profesor.

$datosProfesor = $stmt->fetch(PDO::FETCH_ASSOC); 
// Almacena los resultados de la consulta en un arreglo asociativo.

// Verificar si la consulta ha devuelto resultados
if (!$datosProfesor) { 
    echo "<p>No se encontraron datos para el profesor con DNI: $dniProfesor</p>"; 
    // Si no se encuentran datos del profesor en la base de datos, muestra un mensaje de error.
    exit; 
    // Detiene la ejecución del script si no se encontraron los datos.
}

// Inicializar un array de errores
$errores = []; 
// Crea un array vacío para almacenar los posibles errores que surjan durante la validación del formulario.

// Verificar idioma desde la cookie
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; 
// Verifica si la cookie 'idioma' está configurada y toma su valor. Si no, se asigna 'ES' por defecto (español).

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    // Si el formulario se envía mediante el método POST, entra en este bloque para procesar los datos.

    // Recuperamos los datos del formulario y los asignamos a variables, eliminando los espacios en blanco.
    $dni = trim($_POST['dni']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $fechaContratacion = trim($_POST['fechaContratacion']);
    $salario = trim($_POST['salario']);

    // Validaciones de los campos del formulario
    if (empty($dni) || !preg_match('/^\d{8}[A-Za-z]$/', $dni)) {
        $errores[] = $idioma == 'EN' ? 'The DNI is required and must be valid (8 digits and a letter).' : 'El DNI es obligatorio y debe ser válido (8 números seguidos de una letra).';
    }

    if (empty($nombre)) {
        $errores[] = $idioma == 'EN' ? 'The Name field is required.' : 'El campo Nombre es obligatorio.';
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/", $nombre)) {
        $errores[] = $idioma == 'EN' ? 'The Name must only contain letters.' : 'El Nombre solo debe contener letras.';
    }

    if (empty($apellido)) {
        $errores[] = $idioma == 'EN' ? 'The Surname field is required.' : 'El campo Apellido es obligatorio.';
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/", $apellido)) {
        $errores[] = $idioma == 'EN' ? 'The Surname must only contain letters.' : 'El Apellido solo debe contener letras.';
    }

    if (empty($telefono)) {
        $errores[] = $idioma == 'EN' ? 'The Phone field is required.' : 'El campo Teléfono es obligatorio.';
    } elseif (!preg_match('/^\d{9}$/', $telefono)) {
        $errores[] = $idioma == 'EN' ? 'The Phone must be 9 digits.' : 'El Teléfono debe contener 9 dígitos.';
    }

    if (empty($fechaContratacion)) {
        $errores[] = $idioma == 'EN' ? 'The Hiring Date field is required.' : 'El campo Fecha de Contratación es obligatorio.';
    }

    if (empty($salario)) {
        $errores[] = $idioma == 'EN' ? 'The Salary field is required.' : 'El campo Salario es obligatorio.';
    } elseif (!is_numeric($salario) || $salario < 0) {
        $errores[] = $idioma == 'EN' ? 'The Salary must be a positive number.' : 'El Salario debe ser un número positivo.';
    }

    // Si no hay errores, actualizar los datos del profesor en la base de datos
    if (empty($errores)) {
        // Si el array de errores está vacío, significa que todos los campos son válidos, por lo que se procede a la actualización.

        $query = "UPDATE Profesor SET nombre = :nombre, apellido = :apellido, telefono = :telefono, fechaContratacion = :fechaContratacion, salario = :salario WHERE dni = :dniProfesor";
        // Prepara la consulta SQL para actualizar los datos del profesor, utilizando el DNI como identificador.

        $stmt = $miPDO->prepare($query); // Prepara la consulta SQL.
        $stmt->bindParam(':dniProfesor', $dniProfesor, PDO::PARAM_STR); 
        // Asocia el DNI del profesor a la consulta.
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR); 
        // Asocia el nombre a la consulta.
        $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR); 
        // Asocia el apellido a la consulta.
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR); 
        // Asocia el teléfono a la consulta.
        $stmt->bindParam(':fechaContratacion', $fechaContratacion, PDO::PARAM_STR); 
        // Asocia la fecha de contratación a la consulta.
        $stmt->bindParam(':salario', $salario, PDO::PARAM_STR); 
        // Asocia el salario a la consulta.

        try {
            $stmt->execute(); // Ejecuta la consulta de actualización en la base de datos.
            // Confirmación de modificación exitosa
            $mensaje_exito = $idioma == 'EN' ? 'Data successfully updated' : 'Datos modificados con éxito';
            echo "<script>alert('$mensaje_exito');</script>"; 
            // Muestra un mensaje de alerta indicando que los datos han sido actualizados correctamente.
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); 
            // Si ocurre un error durante la ejecución de la consulta, muestra el mensaje de error.
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $idioma == 'EN' ? 'Edit Professor Data' : 'Modificar Datos - Profesor'; ?></title>
    <link rel="stylesheet" href="../Estilos/modificar_datos.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica">
        </div>
        <h1><?php echo $idioma == 'EN' ? 'Horse Riding - Modify teacher data' : 'Hípica - Modificar Datos del Profesor'; ?></h1>
    </header>

    <main class="modificar-container">
        <h2><?php echo $idioma == 'EN' ? 'Modify your data, ' . htmlspecialchars($datosProfesor['nombre']) : 'Modifica tus datos, ' . htmlspecialchars($datosProfesor['nombre']); ?></h2>

        <!-- Mostrar errores si los hay -->
        <?php if (!empty($errores)): ?>
            <div class="error-container">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li> 
                        <!-- Muestra los errores de validación, si los hay -->
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="dni"><?php echo $idioma == 'EN' ? 'DNI' : 'DNI:'; ?></label>
            <input type="text" name="dni" value="<?php echo htmlspecialchars($datosProfesor['dni']); ?>">

            <label for="nombre"><?php echo $idioma == 'EN' ? 'Name' : 'Nombre:'; ?></label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($datosProfesor['nombre']); ?>">

            <label for="apellido"><?php echo $idioma == 'EN' ? 'Surname' : 'Apellido:'; ?></label>
            <input type="text" name="apellido" value="<?php echo htmlspecialchars($datosProfesor['apellido']); ?>">

            <label for="telefono"><?php echo $idioma == 'EN' ? 'Phone' : 'Teléfono:'; ?></label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($datosProfesor['telefono']); ?>">

            <label for="fechaContratacion"><?php echo $idioma == 'EN' ? 'Hiring Date' : 'Fecha de Contratación:'; ?></label>
            <input type="date" name="fechaContratacion" value="<?php echo htmlspecialchars($datosProfesor['fechaContratacion']); ?>">

            <label for="salario"><?php echo $idioma == 'EN' ? 'Salary' : 'Salario:'; ?></label>
            <input type="number" name="salario" value="<?php echo htmlspecialchars($datosProfesor['salario']); ?>" step="0.01">

            <div class="buttons-container">
                <button type="submit"><?php echo $idioma == 'EN' ? 'Modify' : 'Modificar'; ?></button>
                <a href="profesores.php"><button type="button"><?php echo $idioma == 'ES' ? 'Volver Atrás' : 'Go Back'; ?></button></a>
                <button type="button" onclick="location.href='logout.php'"><?php echo $idioma == 'EN' ? 'Log Out' : 'Cerrar Sesión'; ?></button>
                <a href="preferencias.php"><button type="button"><?php echo $idioma == 'ES' ? 'Preferencias' : 'Preferences'; ?></button></a>
            </div>
        </form>
    </main>

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
