<?php
session_start(); // Inicia la sesión para poder acceder a las variables de sesión.


// Verificar si la cookie de idioma existe, si no, asignar 'ES' como valor predeterminado
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES';
// Si existe la cookie 'idioma', se asigna su valor; si no, se asigna 'ES' como idioma predeterminado.


// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header('Location: login.php'); // Si el usuario no está autenticado o no tiene el rol de 'profesor', redirige al login.
    exit; // Detiene la ejecución del script para evitar que el resto del código se ejecute.
}

// Recuperar el nombre del usuario desde la sesión
$nombreUsuario = $_SESSION['usuario']; // Obtiene el nombre del usuario desde la variable de sesión 'usuario' (asumido que se inició sesión correctamente).
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma == 'ES' ? 'es' : 'en'; ?>">
<!-- El atributo lang se establece según el idioma guardado en la cookie: 'es' para español y 'en' para inglés. -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $idioma == 'ES' ? 'Profesores - Hípica' : 'Teachers - Equestrian Center'; ?></title>
    <!-- El título de la página cambia según el idioma. Si la cookie 'idioma' es 'ES', el título será 'Profesores - Hípica', 
    si es 'EN', el título será 'Teachers - Equestrian Center'. -->
    <link rel="stylesheet" href="../Estilos/profesores.css?v=1.0">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica">
        </div>
        <h1><?php echo $idioma == 'ES' ? 'Hípica - Profesores' : 'Horse Riding - Teachers'; ?></h1>
        <!-- El encabezado muestra el nombre del sitio web con el título adecuado según el idioma seleccionado. -->
    </header>

    <main class="profesores-container">
        <h2><?php echo $idioma == 'ES' ? "Bienvenido/a, " : "Welcome, "; ?><?php echo htmlspecialchars($nombreUsuario); ?></h2>
        <!-- Se muestra el saludo al usuario. Si el idioma es 'ES', se muestra 'Bienvenido/a', 
        si es 'EN', se muestra 'Welcome'. El nombre del usuario se obtiene de la sesión y se muestra de forma segura con htmlspecialchars. -->

        <hr> <!-- Línea horizontal separadora. -->
        
        <p><?php echo $idioma == 'ES' ? "Aquí puedes gestionar tus clases, revisar a tus alumnos y acceder a herramientas específicas." : "Here you can manage your classes, review your students, and access specific tools."; ?></p>
        <!-- Un párrafo con una breve descripción de las funcionalidades de la página. El contenido cambia dependiendo del idioma. -->

        <!-- Botones de acción -->
        <div class="actions">
            <button onclick="location.href='ver_horario.php'"><?php echo $idioma == 'ES' ? 'Ver Horario' : 'View Schedule'; ?></button>
            <!-- Botón para ver el horario. El texto del botón cambia según el idioma. -->
            <button onclick="location.href='ver_alumnos.php'"><?php echo $idioma == 'ES' ? 'Ver Todos los Alumnos' : 'View All Students'; ?></button>
            <!-- Botón para ver todos los alumnos. El texto del botón cambia según el idioma. -->
            <button onclick="location.href='modificar_datos.php'"><?php echo $idioma == 'ES' ? 'Modificar Datos' : 'Modify Data'; ?></button>
            <!-- Botón para modificar los datos del profesor. El texto del botón cambia según el idioma. -->
            <button onclick="location.href='logout.php'"><?php echo $idioma == 'ES' ? 'Cerrar Sesión' : 'Log Out'; ?></button>
            <!-- Botón para cerrar sesión. El texto del botón cambia según el idioma. -->
            <a href="preferencias.php"><button type="button"><?php echo $idioma == 'ES' ? 'Preferencias' : 'Preferences'; ?></button></a>
            <!-- Botón para redirigir a la selección de idioma -->
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>
            <?php
                if (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN') {
                    echo "&copy; 2024 Horse riding. All rights reserved.";
                    // Muestra el mensaje de derechos de autor en inglés si la cookie 'idioma' está configurada a 'EN'.
                    echo "<br><br>Follow us on: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                    // Enlaces a redes sociales en inglés.
                } else {
                    echo "&copy; 2024 Hípica. Todos los derechos reservados.";
                    // Muestra el mensaje de derechos de autor en español si la cookie 'idioma' está configurada a 'ES'.
                    echo "<br><br>Síguenos en: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                    // Enlaces a redes sociales en español.
                }
            ?>
        </p>
    </footer>
</body>
</html>
