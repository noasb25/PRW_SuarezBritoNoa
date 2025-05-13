<?php
// Verificar idioma desde la cookie
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; // Si no hay cookie, asigna 'ES' como valor predeterminado
// Esta línea verifica si existe una cookie llamada 'idioma'. Si existe, se asigna su valor a la variable $idioma; si no, se asigna 'ES' por defecto.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Establece el título de la página según el idioma -->
    <title><?php echo $idioma == 'EN' ? 'User - Hípica' : 'Usuario - Hípica'; ?></title>
    <!-- Si el idioma es inglés, el título será "User - Hípica", si es español será "Usuario - Hípica" -->
    <link rel="stylesheet" href="../Estilos/usuario.css"> <!-- Se incluye el archivo de estilos CSS -->
</head>
<body>

    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica"> <!-- Imagen del logo de la hípica -->
        </div>
        <!-- Título principal de la página -->
        <h1><?php echo $idioma == 'EN' ? 'Horse riding - Welcome' : 'Hípica - Bienvenidos'; ?></h1>
        <!-- Si el idioma es inglés, el título será "Horse riding - Welcome", si es español será "Hípica - Bienvenidos" -->
    </header>

    <main>
        <section class="welcome-message">
            <!-- Mensaje de bienvenida -->
            <h2><?php echo $idioma == 'EN' ? 'Welcome to our equestrian center' : 'Bienvenido a nuestra hípica'; ?></h2>
            <!-- Si el idioma es inglés, se muestra "Welcome to our equestrian center", si es español se muestra "Bienvenido a nuestra hípica" -->
            <hr> <!-- Línea horizontal que separa los contenidos -->
            <p>
                <?php echo $idioma == 'EN' ? 'We are glad to have you here. At our equestrian center, we promote the passion for horses and learning in a safe and professional environment. Thank you for being part of our family!' : 'Nos alegra mucho tenerte aquí. En nuestra hípica promovemos la pasión por los caballos y el aprendizaje en un entorno seguro y profesional. ¡Gracias por formar parte de nuestra familia!'; ?>
            </p>
            <!-- El párrafo muestra un mensaje en inglés o español dependiendo del valor de la cookie 'idioma' -->
        </section>

        <section class="action-buttons">
            <!-- Botones de acción -->
            <a href="login.php"><button><?php echo $idioma == 'EN' ? 'Log In' : 'Iniciar Sesión'; ?></button></a>
            <!-- Si el idioma es inglés, el botón será "Log In", si es español será "Iniciar Sesión" -->
            <a href="register.php"><button><?php echo $idioma == 'EN' ? 'Register User' : 'Registrar Usuario'; ?></button></a>
            <!-- Si el idioma es inglés, el botón será "Register User", si es español será "Registrar Usuario" -->
            <a href="inicio.php"><button><?php echo $idioma == 'EN' ? 'Go Back' : 'Volver Atrás'; ?></button></a>
            <!-- Si el idioma es inglés, el botón será "Go Back", si es español será "Volver Atrás" -->
            <a href="preferencias.php"><button><?php echo $idioma == 'EN' ? 'Preferences' : 'Preferencias'; ?></button></a>
            <!-- Botón de idioma añadido -->
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>
            <?php
                // Mostrar el pie de página dependiendo del idioma de la cookie
                if (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN') {
                    echo "&copy; 2024 Horse riding. All rights reserved."; // Mensaje en inglés
                    echo "<br><br>Follow us on: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                } else {
                    echo "&copy; 2024 Hípica. Todos los derechos reservados."; // Mensaje en español
                    echo "<br><br>Síguenos en: <a href='#' target='_blank'>Facebook</a> | <a href='#' target='_blank'>Instagram</a> | <a href='#' target='_blank'>Twitter</a>";
                }
            ?>
        </p>
    </footer>
    <!-- El pie de página muestra el texto en inglés o español dependiendo del valor de la cookie 'idioma' -->
</body>
</html>
