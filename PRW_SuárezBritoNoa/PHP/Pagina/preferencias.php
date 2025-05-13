<?php
session_start(); // Inicia la sesión para poder acceder a las variables de sesión.

// Si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si se ha enviado el formulario mediante POST.

    // Guardar el idioma en una cookie
    if (isset($_POST['idioma'])) {
        $idioma = $_POST['idioma']; // Recupera el valor del idioma seleccionado en el formulario.
        
        setcookie('idioma', $idioma, time() + 3600 * 24 * 30, '/'); 
        // Establece una cookie llamada 'idioma' con el valor seleccionado. 
        // La cookie expira en 30 días (3600 segundos * 24 horas * 30 días).

        // Redirigir a la página anterior si se ha enviado, si no, ir a inicio.php
        $paginaAnterior = isset($_POST['pagina']) ? $_POST['pagina'] : 'inicio.php';

        header("Location: $paginaAnterior"); 
        // Después de guardar la cookie, redirige al usuario a la página anterior para aplicar el idioma seleccionado.

        exit; // Detiene la ejecución para evitar que se ejecute más código después de la redirección.
    }
}

// Si se entra por GET, se intenta obtener la página anterior mediante HTTP_REFERER
$referer = basename($_SERVER['HTTP_REFERER'] ?? 'inicio.php');
// basename() elimina el path, dejando solo el nombre del archivo (ej: 'login.php')
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'Select Your Language' : 'Selecciona tu idioma'; ?></title>
    <!-- Establece el título de la página según el idioma de la cookie. Si el idioma es inglés, se mostrará "Select Your Language", 
    y si es español, se mostrará "Selecciona tu idioma". -->
    <link rel="stylesheet" href="../Estilos/preferencias.css?v=1.0">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica" style="width: 50px; height: auto; margin-right: 10px;">
        </div>
        <h1><?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'Horse riding - Select Your Language' : 'Hípica - Selecciona tu idioma'; ?></h1>
        <!-- Muestra el encabezado con un título que varía dependiendo del idioma. -->
    </header>
    
    <main>
        <div class="preferencias-container">
            <form action="" method="POST">
                <h2><?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'Select Language:' : 'Selecciona el idioma:'; ?></h2>
                <!-- Título del formulario para seleccionar el idioma. Cambia según el idioma de la cookie. -->

                <hr> <!-- Línea horizontal separadora. -->
                
                <select name="idioma" id="idioma">
                    <!-- El selector para elegir el idioma. Se pueden elegir entre español (ES) e inglés (EN). -->
                    <option value="ES" <?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'ES' ? 'selected' : ''; ?>>
                        <?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'Spanish' : 'Español'; ?>
                    </option>
                    
                    <option value="EN" <?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'selected' : ''; ?>>
                        <?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'English' : 'Inglés'; ?>
                    </option>
                </select>

                <br> <!-- Salto de línea para separar el selector de los botones. -->

                <input type="hidden" name="pagina" value="<?php echo $referer; ?>">
                <!-- Campo oculto para guardar la página desde la que se llegó -->

                <button type="submit"><?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'Save Preference' : 'Guardar Preferencia'; ?></button>
                <!-- Botón para guardar la preferencia de idioma. El texto cambia según el idioma de la cookie. -->
                
                <a href="<?php echo $referer; ?>"><button type="button"><?php echo isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN' ? 'Go Back' : 'Volver Atrás'; ?></button></a>
                <!-- Botón para regresar a la página anterior. -->
            </form>
        </div>
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
