<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión, como el usuario y el rol.

// Verifica si el usuario ha iniciado sesión y tiene el rol de alumno
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'alumno') {
    header('Location: login.php'); // Si no hay sesión activa o no es alumno, redirige a login
    exit;
}

include 'conexion.php'; // Incluye el archivo que conecta con la base de datos

// Verifica si el DNI del alumno está almacenado en la sesión
if (!isset($_SESSION['dni'])) {
    echo "Error: No se ha encontrado el DNI en la sesión."; // Muestra error si no existe DNI
    exit;
}

// Obtiene el DNI del alumno desde la sesión
$dniAlumno = $_SESSION['dni'];

// Consulta SQL para obtener los datos del alumno
$query = "SELECT dni, nombre, apellido, telefono, fechaNacimiento, seguro, foto_perfil FROM Alumno WHERE dni = :dniAlumno";
$stmt = $miPDO->prepare($query); // Prepara la consulta
$stmt->bindParam(':dniAlumno', $dniAlumno, PDO::PARAM_STR); // Asocia el valor del DNI al parámetro
$stmt->execute(); // Ejecuta la consulta
$datosAlumno = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene los datos como array asociativo

// Si no se encuentran datos, muestra mensaje de error
if (!$datosAlumno) {
    echo "<p>No se encontraron datos para el alumno con DNI: $dniAlumno</p>";
    exit;
}

// Verifica el idioma desde la cookie, por defecto en español
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES';
$errores = []; // Array para almacenar errores del formulario

// Si se ha enviado el formulario por método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoge los datos del formulario
    $dni = $_POST['dni'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
    $seguro = $_POST['seguro'] ?? '';

    // Inicializa el nombre de la imagen con la actual
    $imagenNombre = $datosAlumno['foto_perfil'] ?? 'perfil.defecto.png';

    // Verifica si se ha subido una imagen nueva
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreTmp = $_FILES['imagen']['tmp_name']; // Ruta temporal
        $nombreOriginal = basename($_FILES['imagen']['name']); // Nombre original del archivo
        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION); // Extensión del archivo
        $nuevoNombre = uniqid('perfil_', true) . '.' . $ext; // Genera un nombre único
        $rutaDestino = "../Imagenes/Perfiles/" . $nuevoNombre; // Ruta final

        // Mueve el archivo subido a la carpeta de perfiles
        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            $imagenNombre = $nuevoNombre;
        } else {
            $errores[] = $idioma == 'EN' ? 'Error uploading image.' : 'Error al subir la imagen.';
        }
    }

    // Validación del campo DNI
    if (empty($dni)) {
        $errores[] = $idioma == 'EN' ? 'The DNI field is required.' : 'El campo DNI es obligatorio.';
    } elseif (!preg_match('/^\d{8}[A-Za-z]$/', $dni)) {
        $errores[] = $idioma == 'EN' ? 'The DNI must be 8 digits followed by a letter.' : 'El DNI debe tener 8 números seguidos de una letra.';
    } else {
        // Verifica si el nuevo DNI ya está registrado por otro alumno
        $query = "SELECT COUNT(*) FROM Alumno WHERE dni = :dni";
        $stmt = $miPDO->prepare($query);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count > 0 && $dni != $datosAlumno['dni']) {
            $errores[] = $idioma == 'EN' ? "The DNI is already registered for another student." : "El DNI ya está registrado en otro alumno.";
        }
    }

    // Validación del campo nombre
    if (empty($nombre)) {
        $errores[] = $idioma == 'EN' ? 'The Name field is required.' : 'El campo Nombre es obligatorio.';
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/", $nombre)) {
        $errores[] = $idioma == 'EN' ? 'The Name must only contain letters.' : 'El Nombre solo debe contener letras.';
    }

    // Validación del campo apellido
    if (empty($apellido)) {
        $errores[] = $idioma == 'EN' ? 'The Surname field is required.' : 'El campo Apellido es obligatorio.';
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/", $apellido)) {
        $errores[] = $idioma == 'EN' ? 'The Surname must only contain letters.' : 'El Apellido solo debe contener letras.';
    }

    // Validación del campo teléfono
    if (empty($telefono)) {
        $errores[] = $idioma == 'EN' ? 'The Phone field is required.' : 'El campo Teléfono es obligatorio.';
    } elseif (!preg_match('/^\d{9}$/', $telefono)) {
        $errores[] = $idioma == 'EN' ? 'The Phone must be 9 digits.' : 'El Teléfono debe contener 9 dígitos.';
    }

    // Validación del campo fecha de nacimiento
    if (empty($fechaNacimiento)) {
        $errores[] = $idioma == 'EN' ? 'The Date of Birth field is required.' : 'El campo Fecha de nacimiento es obligatorio.';
    }

    // Validación del campo seguro
    if (empty($seguro)) {
        $errores[] = $idioma == 'EN' ? 'The Insurance field is required.' : 'El campo Seguro es obligatorio.';
    } elseif (!preg_match('/^[A-Za-z]\d{9}$/', $seguro)) {
        $errores[] = $idioma == 'EN' ? 'The Insurance must start with a letter followed by 9 digits.' : 'El Seguro debe empezar con una letra seguida de 9 números.';
    }

    // Si no hay errores, actualiza los datos del alumno en la base de datos
    if (empty($errores)) {
        $query = "UPDATE Alumno SET dni = :dni, nombre = :nombre, apellido = :apellido, telefono = :telefono, fechaNacimiento = :fechaNacimiento, seguro = :seguro, foto_perfil = :foto WHERE dni = :dniAlumno";
        $stmt = $miPDO->prepare($query);
        $stmt->execute([
            ':dni' => $dni,
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':telefono' => $telefono,
            ':fechaNacimiento' => $fechaNacimiento,
            ':seguro' => $seguro,
            ':foto' => $imagenNombre,
            ':dniAlumno' => $dniAlumno
        ]);

        $_SESSION['dni'] = $dni; // Actualiza el DNI en la sesión si fue modificado
        $_SESSION['mensaje_exito'] = $idioma == 'EN' ? 'Data successfully updated' : 'Datos modificados con éxito';
        header("Location: modificar_datos_alumno.php"); // Redirige a la misma página
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es"> <!-- Define el idioma principal del documento como español -->
<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Hace que la web sea responsive -->
    <title><?php echo $idioma == 'EN' ? 'Modify Data - Student' : 'Modificar Datos - Alumno'; ?></title> <!-- Título dinámico según el idioma -->
    <link rel="stylesheet" href="../Estilos/modificar_datos.css?v=1.0"> <!-- Enlace al archivo CSS para estilos -->
    <style>
        .imagen-preview {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .preview-img {
            width: 120px;
            height: 120px;
            border-radius: 50%; /* Forma circular */
            object-fit: cover; /* Recorta la imagen para que no se deforme */
            border: 2px solid #aaa;
        }
    </style> <!-- Estilos adicionales para la vista previa de la imagen -->
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica"> <!-- Logo de la empresa -->
        </div>
        <h1><?php echo $idioma == 'EN' ? 'Horse Riding - Modify Student Data' : 'Hípica - Modificar Datos del Alumno'; ?></h1> <!-- Encabezado principal con soporte para idiomas -->
    </header>

    <main class="modificar-container"> <!-- Contenedor principal del formulario -->
        <h2>
            <?php 
                // Saludo dinámico con el nombre del alumno
                echo $idioma == 'EN' 
                    ? 'Modify your data, ' . htmlspecialchars($datosAlumno['nombre']) 
                    : 'Modifica tus datos, ' . htmlspecialchars($datosAlumno['nombre']); 
            ?>
        </h2>

        <?php if (!empty($errores)): ?> <!-- Si hay errores, se muestran aquí -->
            <div class="error-container">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo $error; ?></li> <!-- Lista de errores del formulario -->
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje_exito'])): ?> <!-- Si hay un mensaje de éxito -->
            <script>alert('<?php echo $_SESSION['mensaje_exito']; ?>');</script> <!-- Muestra alerta -->
            <?php unset($_SESSION['mensaje_exito']); ?> <!-- Borra el mensaje de la sesión -->
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data"> <!-- Formulario para modificar datos y subir imagen -->
            <div class="imagen-preview">
                <img id="preview" class="preview-img" src="../Imagenes/Perfiles/<?php echo $datosAlumno['foto_perfil'] ?? 'perfil.defecto.png'; ?>" alt="Preview"> <!-- Vista previa de la imagen -->
                <div>
                    <label for="imagen"><strong><?php echo $idioma == 'EN' ? 'Profile Image' : 'Imagen de Perfil'; ?></strong></label><br>
                    <input type="file" id="imagen" name="imagen" accept="image/*" onchange="mostrarPreview(event)"> <!-- Input para cargar una nueva imagen -->
                </div>
            </div>

            <!-- Campo DNI -->
            <label for="dni"><?php echo $idioma == 'EN' ? 'DNI' : 'DNI:'; ?></label>
            <input type="text" name="dni" value="<?php echo htmlspecialchars($datosAlumno['dni']); ?>">

            <!-- Campo Nombre -->
            <label for="nombre"><?php echo $idioma == 'EN' ? 'Name' : 'Nombre:'; ?></label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($datosAlumno['nombre']); ?>">

            <!-- Campo Apellido -->
            <label for="apellido"><?php echo $idioma == 'EN' ? 'Last Name' : 'Apellido:'; ?></label>
            <input type="text" name="apellido" value="<?php echo htmlspecialchars($datosAlumno['apellido']); ?>">

            <!-- Campo Teléfono -->
            <label for="telefono"><?php echo $idioma == 'EN' ? 'Phone' : 'Teléfono:'; ?></label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($datosAlumno['telefono']); ?>">

            <!-- Campo Fecha de Nacimiento -->
            <label for="fechaNacimiento"><?php echo $idioma == 'EN' ? 'Date of Birth' : 'Fecha de Nacimiento:'; ?></label>
            <input type="date" name="fechaNacimiento" value="<?php echo htmlspecialchars($datosAlumno['fechaNacimiento']); ?>">

            <!-- Campo Seguro -->
            <label for="seguro"><?php echo $idioma == 'EN' ? 'Insurance' : 'Seguro:'; ?></label>
            <input type="text" name="seguro" value="<?php echo htmlspecialchars($datosAlumno['seguro']); ?>">

            <!-- Botones de acción -->
            <div class="buttons-container">
                <button type="submit"><?php echo $idioma == 'EN' ? 'Modify' : 'Modificar'; ?></button>
                <a href="bienvenida.php"><button type="button"><?php echo $idioma == 'ES' ? 'Volver Atrás' : 'Go Back'; ?></button></a>
                <button type="button" onclick="location.href='logout.php'"><?php echo $idioma == 'EN' ? 'Log Out' : 'Cerrar Sesión'; ?></button>
                <a href="preferencias.php"><button type="button"><?php echo $idioma == 'ES' ? 'Preferencias' : 'Preferences'; ?></button></a>
            </div>
        </form>
    </main>

    <!-- Script para mostrar vista previa de la imagen -->
    <script>
        function mostrarPreview(event) {
            const img = document.getElementById('preview'); // Obtiene el elemento de imagen
            img.src = URL.createObjectURL(event.target.files[0]); // Cambia la fuente a la imagen seleccionada
        }
    </script>

    <!-- Footer de la página -->
    <footer>
        <p>
            <?php
            if ($idioma == 'EN') {
                echo "&copy; 2024 Horse riding. All rights reserved.";
                echo "<br><br>Follow us on: <a href='#'>Facebook</a> | <a href='#'>Instagram</a> | <a href='#'>Twitter</a>";
            } else {
                echo "&copy; 2024 Hípica. Todos los derechos reservados.";
                echo "<br><br>Síguenos en: <a href='#'>Facebook</a> | <a href='#'>Instagram</a> | <a href='#'>Twitter</a>";
            }
            ?>
        </p>
    </footer>
</body>
</html>
