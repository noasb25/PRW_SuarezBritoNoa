<?php
session_start(); // Inicia o reanuda la sesión. Es necesario para poder usar $_SESSION

// Verificar si el usuario ha iniciado sesión y tiene el rol de alumno
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'alumno') {
    // Si no hay sesión o el rol no es 'alumno', redirige al login.php
    header('Location: login.php'); // Redirige a la página de login
    exit; // Detiene la ejecución del script para evitar que continúe cargando la página
}

// Recuperar el nombre del usuario desde la sesión
$nombreUsuario = $_SESSION['usuario']; // El nombre del usuario se almacena en la sesión y se asigna a la variable

// Verificar idioma desde la cookie
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; 
// Verifica si la cookie 'idioma' está definida. Si lo está, lo asigna a la variable $idioma. Si no, se asigna 'ES' como valor por defecto
?>

<!DOCTYPE html>
<html lang="es"> <!-- Define el idioma de la página, en este caso español -->
<head>
    <meta charset="UTF-8"> <!-- Define la codificación de caracteres para la página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Hace que la página sea responsive en dispositivos móviles -->
    <title><?php echo $idioma == 'EN' ? 'Welcome - Student' : 'Bienvenida - Alumno'; ?></title>
    <!-- Título de la página, cambia según el idioma. Si el idioma es 'EN', muestra 'Welcome - Student', de lo contrario, 'Bienvenida - Alumno' -->
    <link rel="stylesheet" href="../Estilos/bienvenida.css?v=1.0"> <!-- Enlaza el archivo de estilo CSS -->
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica"> <!-- Muestra el logo de la página -->
        </div>
        <h1><?php echo $idioma == 'EN' ? 'Horse Riding - Student' : 'Hípica - Alumno'; ?></h1>
        <!-- Muestra el encabezado de la página, cambia según el idioma -->
    </header>

    <main class="bienvenida-container"> <!-- Contenedor principal para el contenido de la página -->
        <h2><?php echo $idioma == 'EN' ? 'Welcome, ' . htmlspecialchars($nombreUsuario) : 'Bienvenido/a, ' . htmlspecialchars($nombreUsuario); ?>!</h2>
        <!-- Muestra un saludo al usuario. Si el idioma es inglés, usa "Welcome", si es español, "Bienvenido/a" -->
        <hr> <!-- Línea horizontal para separar el saludo del resto del contenido -->
        <p><?php echo $idioma == 'EN' ? 'Here you can access your schedule, modify your personal data, and log out.' : 'Aquí puedes acceder a tu horario, modificar tus datos personales y cerrar sesión.'; ?></p>
        <!-- Muestra un texto explicativo. Se adapta según el idioma seleccionado en la cookie -->
        
        <!-- Botones de acción -->
        <div class="actions">
            <button onclick="location.href='ver_horario_alumno.php'"><?php echo $idioma == 'EN' ? 'View Schedule' : 'Ver Horario'; ?></button>
            <!-- Botón para acceder a la página de horarios, cambia el texto según el idioma -->

            <button onclick="location.href='modificar_datos_alumno.php'"><?php echo $idioma == 'EN' ? 'Modify Data' : 'Modificar Datos'; ?></button>
            <!-- Botón para acceder a la página de modificación de datos, cambia el texto según el idioma -->

            <button onclick="location.href='ver_mis_valoraciones.php'"><?php echo $idioma == 'EN' ? 'My Ratings' : 'Mis Valoraciones'; ?></button>
            <!-- Botón para que el alumno vea las valoraciones que le han dejado los profesores -->

            <button onclick="location.href='logout.php'"><?php echo $idioma == 'EN' ? 'Log Out' : 'Cerrar Sesión'; ?></button>
            <!-- Botón para cerrar sesión y redirigir a 'logout.php', cambia el texto según el idioma -->

            <a href="preferencias.php"><button type="button"><?php echo $idioma == 'ES' ? 'Preferencias' : 'Preferences'; ?></button></a>
            <!-- Botón para redirigir a la selección de idioma -->
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>
            <?php
                if (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN') {
                    // Si la cookie de idioma está definida y es 'EN' (inglés)
                    echo "&copy; 2024 Horse riding. All rights reserved."; // Muestra el mensaje de derechos de autor en inglés
                    echo "<br><br>Follow us on: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                    // Muestra enlaces de redes sociales en inglés
                } else {
                    // Si el idioma es español (predeterminado si no está definida la cookie)
                    echo "&copy; 2024 Hípica. Todos los derechos reservados."; // Muestra el mensaje de derechos de autor en español
                    echo "<br><br>Síguenos en: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                    // Muestra enlaces de redes sociales en español
                }
            ?>
        </p>
    </footer>

</body>
</html>
