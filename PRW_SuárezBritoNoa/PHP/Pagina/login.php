<?php
session_start(); // Inicia la sesión, permite usar variables globales $_SESSION

include 'conexion.php'; // Incluye el archivo de conexión a la base de datos, que contiene la configuración de la base de datos y la conexión.


// Verificar idioma desde la cookie
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; 
// Verifica si la cookie 'idioma' está configurada, si está, toma su valor (puede ser 'ES' o 'EN'), si no, establece el valor predeterminado como 'ES' (español).

// Si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verifica si el formulario ha sido enviado mediante el método POST
    $dni = trim($_POST['dni']); // Obtiene el DNI del formulario, y usa 'trim' para eliminar espacios en blanco al inicio y al final.
    $error = null; // Inicializa la variable $error como nula. 

    if (empty($dni)) { // Verifica si el campo DNI está vacío
    // Si está vacío, asigna un mensaje de error en función del idioma.
    $error = $idioma == 'EN' ? "Please enter your ID." : "Por favor, introduzca su DNI.";
    } else { // Si el campo DNI no está vacío
        try {
            // Consulta para verificar si el DNI pertenece a un profesor
            $query_profesor = "SELECT id_profesor, nombre FROM Profesor WHERE dni = :dni"; 
            // Realiza una consulta a la tabla 'Profesor' buscando por el DNI proporcionado.
            $result_profesor = $miPDO->prepare($query_profesor); 
            // Prepara la consulta para ser ejecutada.
            $result_profesor->execute(['dni' => $dni]); 
            // Ejecuta la consulta pasando el DNI como parámetro.
            $profesor = $result_profesor->fetch(PDO::FETCH_ASSOC); 
            // Obtiene los datos del profesor en formato asociativo (clave => valor).

            if ($profesor) { // Si existe un profesor con el DNI proporcionado
                $_SESSION['usuario'] = $profesor['nombre']; 
                // Guarda el nombre del profesor en la sesión.
                $_SESSION['dni'] = $dni; 
                // Guarda el DNI en la sesión.
                $_SESSION['rol'] = 'profesor'; 
                // Establece el rol como 'profesor' en la sesión.
                header('Location: profesores.php'); 
                // Redirige a la página 'profesores.php' si el usuario es un profesor.
                exit; 
                // Termina la ejecución del código para evitar continuar con el script.
            }

            // Si no es un profesor, verifica si es un alumno
            $query_alumno = "SELECT nombre FROM Alumno WHERE dni = :dni"; 
            // Realiza una consulta a la tabla 'Alumno' buscando por el DNI proporcionado.
            $result_alumno = $miPDO->prepare($query_alumno); 
            // Prepara la consulta para la ejecución.
            $result_alumno->execute(['dni' => $dni]); 
            // Ejecuta la consulta pasando el DNI como parámetro.
            $alumno = $result_alumno->fetch(PDO::FETCH_ASSOC); 
            // Obtiene los datos del alumno.

            if ($alumno) { // Si existe un alumno con el DNI proporcionado
                $_SESSION['usuario'] = $alumno['nombre']; 
                // Guarda el nombre del alumno en la sesión.
                $_SESSION['dni'] = $dni; 
                // Guarda el DNI en la sesión.
                $_SESSION['rol'] = 'alumno'; 
                // Establece el rol como 'alumno' en la sesión.
                header('Location: bienvenida.php'); 
                // Redirige a la página 'bienvenida.php' si el usuario es un alumno.
                exit; 
                // Termina la ejecución del código.
            }

            // Si el DNI no está en ninguna tabla
            $error = $idioma == 'EN' ? "The entered ID is not registered." : "El DNI ingresado no está registrado.";
            // Si no se encuentra el DNI en la base de datos, asigna un mensaje de error.
        } catch (PDOException $e) { 
            // Si ocurre un error al ejecutar la consulta, se captura la excepción.
            $error = "Error al iniciar sesión: " . htmlspecialchars($e->getMessage()); 
            // Muestra un mensaje de error, sanitizando cualquier contenido del error para evitar inyecciones de código.
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es"> 
<!-- Define el idioma del documento HTML (español por defecto). -->
<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <!-- Configura el viewport para que la página sea adaptable en dispositivos móviles. -->
    <title><?php echo $idioma == 'ES' ? 'Iniciar Sesión - Hípica' : 'Login - Hippodrome'; ?></title> 
    <!-- Define el título de la página, cambiando el texto dependiendo del idioma seleccionado. -->
    <link rel="stylesheet" href="../Estilos/login.css?v=1.0"> 
    <!-- Enlaza el archivo CSS para aplicar los estilos a la página. -->
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica"> 
            <!-- Muestra el logo de la página. -->
        </div>
        <h1><?php echo $idioma == 'ES' ? 'Hípica - Iniciar Sesión' : 'Horse Riding - Login'; ?></h1>
        <!-- Título principal de la página, cambia dependiendo del idioma. -->
    </header>

    <main class="login-container">
        <h2><?php echo $idioma == 'ES' ? 'Accede a tu cuenta' : 'Access your account'; ?></h2> 
        <!-- Subtítulo de la página, cambia dependiendo del idioma. -->
        <hr>
        <?php if (isset($error)): ?> 
        <!-- Si existe un mensaje de error, lo muestra en la página. -->
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <!-- Muestra el mensaje de error, si existe, en color rojo. -->

        <form action="" method="POST">
            <!-- Formulario que envía los datos de inicio de sesión por POST. -->
            <label for="dni"><?php echo $idioma == 'ES' ? 'DNI' : 'ID'; ?>:</label>
            <!-- Etiqueta para el campo DNI, cambia dependiendo del idioma -->
            <input type="text" id="dni" name="dni" placeholder="<?php echo $idioma == 'ES' ? 'Introduce tu DNI' : 'Enter your ID'; ?>">
            <!-- Campo de entrada para el DNI del usuario, con texto de sugerencia dependiendo del idioma. -->
            
            <div class="buttons-container">
                <!-- Contenedor de los botones -->
                <button type="submit"><?php echo $idioma == 'ES' ? 'Ingresar' : 'Login'; ?></button>
                <!-- Botón para enviar el formulario, cambia el texto dependiendo del idioma. -->
                <a href="usuario.php"><button type="button"><?php echo $idioma == 'ES' ? 'Volver Atrás' : 'Go Back'; ?></button></a>
                <!-- Botón para volver atrás, se usa un enlace para redirigir sin enviar el formulario. -->
                <a href="preferencias.php"><button type="button"><?php echo $idioma == 'ES' ? 'Preferencias' : 'Preferences'; ?></button></a>
                <!-- Botón para redirigir a la selección de idioma -->
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer>
        <p>
            <?php
                // Pie de página que cambia dependiendo del idioma del usuario.
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
