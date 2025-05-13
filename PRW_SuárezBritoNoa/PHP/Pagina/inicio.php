<?php
// Incluir el archivo de conexión
include 'conexion.php'; // Se incluye el archivo 'conexion.php' que  contiene la conexión a la base de datos.

// Verificar idioma desde la cookie
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; 
// Aquí se verifica si la cookie 'idioma' está establecida. Si está, se toma su valor, y si no, se asigna 'ES' (español) por defecto.
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma == 'EN' ? 'en' : 'es'; ?>">
<!-- Establece el idioma del documento HTML. Si el idioma es inglés ('EN'), se pone 'en', de lo contrario, 'es' -->
<head>
    <meta charset="UTF-8"> <!-- Define la codificación de caracteres para la página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <!-- Establece las configuraciones para una página adaptable a diferentes dispositivos -->
    <title><?php echo $idioma == 'EN' ? 'Hípica - Home' : 'Hípica - Inicio'; ?></title>
    <!-- El título de la página cambia según el idioma. Si es inglés, será 'Hípica - Home', de lo contrario, 'Hípica - Inicio' -->
    <link rel="stylesheet" href="../Estilos/inicio.css"> <!-- Enlaza el archivo de estilo CSS que define la apariencia de la página -->
</head>
<body>

    <header>
        <!-- Sección de encabezado -->
        <div class="slideshow">
            <!-- Contenedor de la galería de imágenes -->
            <img src="../Imagenes/imagen1.png" alt="Imagen 1" class="slide"> <!-- Imagen 1 -->
            <img src="../Imagenes/imagen2.png" alt="Imagen 2" class="slide"> <!-- Imagen 2 -->
            <img src="../Imagenes/imagen3.png" alt="Imagen 3" class="slide"> <!-- Imagen 3 -->
        </div>
        <div class="logo">
            <!-- Logo de la página -->
            <img src="../Imagenes/logo.png" alt="Logo Hípica"> <!-- Imagen del logo de la hípica -->
        </div>
        <h1><?php echo $idioma == 'EN' ? 'Welcome to the Equestrian Center' : 'Bienvenidos al Centro Ecuestre'; ?></h1>
        <!-- Título principal. Si el idioma es inglés, se muestra 'Welcome to the Equestrian Center', si no, 'Bienvenidos al Centro Ecuestre' -->
        <div class="user-icon">
            <!-- Icono de usuario -->
            <a href="usuario.php"><img src="../Imagenes/usuario.png" alt="Icono Usuario"></a>
            <!-- Enlace al perfil del usuario -->
        </div>
    </header>

    <nav>
        <!-- Barra de navegación -->
        <ul>
            <li><a href="#sobre-nosotros"><?php echo $idioma == 'EN' ? 'About Us' : 'Sobre Nosotros'; ?></a></li>
            <!-- Enlace a la sección "Sobre Nosotros", cambia el texto dependiendo del idioma -->
            <li><a href="#horario"><?php echo $idioma == 'EN' ? 'Schedule' : 'Horario'; ?></a></li>
            <!-- Enlace a la sección "Horario", cambia el texto dependiendo del idioma -->
            <li><a href="#servicios"><?php echo $idioma == 'EN' ? 'Services' : 'Servicios'; ?></a></li>
            <!-- Enlace a la sección "Servicios", cambia el texto dependiendo del idioma -->
            <li><a href="#ubicacion"><?php echo $idioma == 'EN' ? 'Location' : 'Ubicación'; ?></a></li>
            <!-- Enlace a la sección "Ubicación", cambia el texto dependiendo del idioma -->
            <li><a href="#contacto"><?php echo $idioma == 'EN' ? 'Contact' : 'Contacto'; ?></a></li>
            <!-- Enlace a la sección "Contacto", cambia el texto dependiendo del idioma -->
            <li><a href="preferencias.php"><?php echo $idioma == 'EN' ? 'Preferences' : 'Preferencias'; ?></a></li>
            <!-- Enlace a la página de "Preferencias", cambia el texto dependiendo del idioma -->
        </ul>
    </nav>

    <main>
        <!-- Contenido principal -->
        <!-- Información Sobre Nosotros -->
        <section id="sobre-nosotros">
            <h2><?php echo $idioma == 'EN' ? 'About Us' : 'Sobre Nosotros'; ?></h2>
            <!-- Título de la sección, cambia según el idioma -->
            <p>
                <?php echo $idioma == 'EN' ? 'Welcome to our equestrian center, a place where the passion for horses and learning to ride come together.' : 'Bienvenidos a nuestra hípica, un lugar donde la pasión por los caballos y el aprendizaje en la equitación se unen.'; ?>
            </p>
            <!-- Descripción de la hípica, cambia dependiendo del idioma -->
        </section>

        <!-- Horario -->
        <section id="horario">
            <h2><?php echo $idioma == 'EN' ? 'Opening Hours' : 'Horario de Atención'; ?></h2>
            <!-- Título de la sección, cambia según el idioma -->
            <p><?php echo $idioma == 'EN' ? 'Monday to Friday: 10:00 AM - 12:00 AM // 16:00 PM - 18:00 PM' : 'Lunes a Viernes: 10:00 AM - 12:00 AM // 16:00 PM - 18:00 PM'; ?></p>
            <!-- Horario de atención, cambia dependiendo del idioma -->
            <p><?php echo $idioma == 'EN' ? 'Saturdays and Sundays: Closed' : 'Sábados y Domingos: Cerrado'; ?></p>
            <!-- Horario de fin de semana, cambia dependiendo del idioma -->
        </section>

        <!-- Servicios -->
        <section id="servicios">
            <h2><?php echo $idioma == 'EN' ? 'Our Services' : 'Nuestros Servicios'; ?></h2>
            <!-- Título de la sección, cambia según el idioma -->
            <div class="servicios-grid">
                <!-- Contenedor de servicios -->
                <div class="servicio">
                    <!-- Servicio 1: Clases de equitación -->
                    <img src="../Imagenes/clases.equitacion.png" alt="Clases de Equitación">
                    <h3><?php echo $idioma == 'EN' ? 'Horseback Riding Lessons' : 'Clases de Equitación'; ?></h3>
                    <p><?php echo $idioma == 'EN' ? 'We offer horseback riding lessons for all levels, from beginners to advanced.' : 'Ofrecemos clases de equitación para todos los niveles, desde principiantes hasta avanzados.'; ?></p>
                </div>
                <div class="servicio">
                    <!-- Servicio 2: Rutas y paseos a caballo -->
                    <img src="../Imagenes/rutas.paseos.png" alt="Rutas y Paseos a Caballo">
                    <h3><?php echo $idioma == 'EN' ? 'Horseback Riding Trails' : 'Rutas y Paseos a Caballo'; ?></h3>
                    <p><?php echo $idioma == 'EN' ? 'Enjoy a unique experience with our horseback riding trails, ideal for connecting with nature.' : 'Disfruta de una experiencia única con nuestras rutas y paseos a caballo, ideales para conectar con la naturaleza.'; ?></p>
                </div>
                <div class="servicio">
                    <!-- Servicio 3: Alquiler de caballos -->
                    <img src="../Imagenes/alquiler.caballos.png" alt="Alquiler de Caballo">
                    <h3><?php echo $idioma == 'EN' ? 'Horse Rental' : 'Alquiler de Caballo'; ?></h3>
                    <p><?php echo $idioma == 'EN' ? 'We offer horse rental services for those who wish to experience riding independently.' : 'Ofrecemos servicio de alquiler de caballos para aquellos que deseen disfrutar de una experiencia independiente bajo supervisión.'; ?></p>
                </div>
                <div class="servicio">
                    <!-- Servicio 4: Otros servicios -->
                    <img src="../Imagenes/otros.servicios.png" alt="Otros Servicios">
                    <h3><?php echo $idioma == 'EN' ? 'Other Services' : 'Otros Servicios'; ?></h3>
                    <p><?php echo $idioma == 'EN' ? 'In addition to lessons and trails, we offer specialized training and horse care services.' : 'Además de las clases y paseos, contamos con servicios adicionales como entrenamiento especializado y servicios de cuidado de caballos.'; ?></p>
                </div>
            </div>
        </section>

        <!-- Ubicación -->
        <section id="ubicacion">
            <h2><?php echo $idioma == 'EN' ? 'Location' : 'Ubicación'; ?></h2>
            <!-- Título de la sección, cambia según el idioma -->
            <p><?php echo $idioma == 'EN' ? 'We are located at Calle Islas Malvinas, 3 35106 San Bartolomé de Tirajana Las Palmas.' : 'Nos encontramos en Calle Islas Malvinas, 3 35106 San Bartolomé de Tirajana Las Palmas.'; ?></p>
            <!-- Dirección-->
            <div class="mapa">
                <iframe src="https://www.google.com/maps/embed?pb=!1m25!1m12!1m3!1d112956.23101404359!2d-15.714214566815693!3d27.78260306501381!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m10!3e6!4m4!1s0xc4082851a586fc1%3A0xd5f37529129474c7!3m2!1d27.7826275!2d-15.6318139!4m3!3m2!1d27.782667699999998!2d-15.6317364!5e0!3m2!1sen!2ses!4v1731699142452!5m2!1sen!2ses" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>

        <!-- Contacto -->
        <section id="contacto">
            <h2><?php echo $idioma == 'EN' ? 'Contact Us' : 'Contáctanos'; ?></h2>
            <!-- Título de la sección, cambia según el idioma -->
            <p><?php echo $idioma == 'EN' ? 'Phone: +34 616 41 83 64' : 'Teléfono: +34 616 41 83 64'; ?></p>
            <!-- Teléfono de contacto -->
            <p><?php echo $idioma == 'EN' ? 'Email: ecuest@hipica.com' : 'Email: ecuest@hipica.com'; ?></p>
            <!-- Correo electrónico de contacto -->
        </section>

    </main>

    <!-- Footer -->
    <footer>
        <p>
            <?php
                // Footer que cambia según el idioma de la cookie
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

    <script src="../JS/inicio.js"></script>
    <!-- Enlaza el archivo JavaScript para el funcionamiento de la página (como el carrusel de imágenes) -->

</body>
</html>