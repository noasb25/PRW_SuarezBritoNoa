<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión.

include 'conexion.php'; // Incluye el archivo de conexión a la base de datos para poder interactuar con la base de datos.

// Inicializar errores
$errores = []; // Inicializa un array vacío para almacenar los posibles errores del formulario.

$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'ES'; // Verifica si existe una cookie de idioma. Si no, se asigna 'ES' como valor predeterminado.

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = trim($_POST['dni']); // Obtiene el valor del campo 'dni' y elimina los espacios en blanco al principio y al final.
    $nombre = trim($_POST['nombre']); // Obtiene el valor del campo 'nombre'.
    $apellido = trim($_POST['apellido']); // Obtiene el valor del campo 'apellido'.
    $telefono = trim($_POST['telefono']); // Obtiene el valor del campo 'telefono'.
    $fechaNacimiento = trim($_POST['fecha_nacimiento']); // Obtiene el valor del campo 'fecha_nacimiento'.
    $seguro = trim($_POST['seguro']); // Obtiene el valor del campo 'seguro'.

    // Procesar imagen
    $imagenNombre = 'perfil.defecto.png'; // Imagen por defecto
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) { // Si se subió una imagen correctamente
        $nombreTmp = $_FILES['imagen']['tmp_name']; // Ruta temporal del archivo
        $nombreOriginal = basename($_FILES['imagen']['name']); // Nombre original del archivo
        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION); // Obtiene la extensión del archivo
        $nuevoNombre = uniqid('perfil_', true) . '.' . $ext; // Genera un nombre único
        $rutaDestino = "../Imagenes/Perfiles/" . $nuevoNombre; // Ruta destino del archivo

        if (move_uploaded_file($nombreTmp, $rutaDestino)) { // Mueve el archivo a su destino
            $imagenNombre = $nuevoNombre; // Actualiza el nombre de la imagen
        } else {
            $errores[] = $idioma == 'EN' ? 'Error uploading image.' : 'Error al subir la imagen.'; // Si falla la subida, añade un error
        }
    }

    // Validaciones de los campos del formulario
    if (empty($dni)) {
        $errores[] = $idioma == 'EN' ? 'The DNI field is required.' : 'El campo DNI es obligatorio.';
    } elseif (!preg_match('/^\d{8}[A-Za-z]$/', $dni)) {
        $errores[] = $idioma == 'EN' ? 'The DNI must have 8 digits followed by a letter.' : 'El DNI debe tener 8 números seguidos de una letra.';
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

    if (empty($fechaNacimiento)) {
        $errores[] = $idioma == 'EN' ? 'The Date of Birth field is required.' : 'El campo Fecha de nacimiento es obligatorio.';
    }

    if (empty($seguro)) {
        $errores[] = $idioma == 'EN' ? 'The Insurance field is required.' : 'El campo Seguro es obligatorio.';
    } elseif (!preg_match('/^[A-Za-z]{1}\d{9}$/', $seguro)) {
        $errores[] = $idioma == 'EN' ? 'Insurance must start with a letter followed by 9 digits.' : 'El Seguro debe comenzar con una letra seguida de 9 dígitos.';
    }

    // Si no hay errores, procesa el registro
    if (empty($errores)) {
        try {
            // Consulta para insertar un nuevo alumno en la base de datos
            $query = "INSERT INTO Alumno (dni, nombre, apellido, telefono, fechaNacimiento, seguro, foto_perfil) 
                      VALUES (:dni, :nombre, :apellido, :telefono, :fechaNacimiento, :seguro, :foto)";
            $result = $miPDO->prepare($query);
            $result->execute([
                ':dni' => $dni,
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':telefono' => $telefono,
                ':fechaNacimiento' => $fechaNacimiento,
                ':seguro' => $seguro,
                ':foto' => $imagenNombre
            ]);

            $mensaje_exito = $idioma == 'EN' ? 'Student registered successfully' : 'Alumno registrado con éxito';
            echo "<script>alert('$mensaje_exito');</script>";
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $errores[] = $idioma == 'EN' ? 'Error registering the student: ' . $e->getMessage() : 'Error al registrar el alumno: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma == 'EN' ? 'en' : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $idioma == 'EN' ? 'Register Student - Equestrian Center' : 'Registrar Alumno - Hípica'; ?></title>
    <link rel="stylesheet" href="../Estilos/register.css">
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
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #aaa;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Imagenes/logo.png" alt="Logo Hípica">
        </div>
        <h1><?php echo $idioma == 'EN' ? 'Horse Riding - Student Registration' : 'Hípica - Registro de Alumnos'; ?></h1>
    </header>

    <main class="register-container">
        <h2><?php echo $idioma == 'EN' ? 'Register New Student' : 'Registrar Nuevo Alumno'; ?></h2>
        <hr>

        <?php if (!empty($errores)): ?>
            <div class="error-container">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="imagen-preview">
                <img src="../Imagenes/Perfiles/perfil.defecto.png" id="preview" class="preview-img" alt="Preview">
                <div>
                    <label for="imagen"><strong><?php echo $idioma == 'EN' ? 'Profile Image' : 'Imagen de Perfil'; ?></strong></label><br>
                    <input type="file" id="imagen" name="imagen" accept="image/*" onchange="mostrarPreview(event)">
                </div>
            </div>

            <label for="dni"><?php echo $idioma == 'EN' ? 'DNI' : 'DNI:'; ?></label>
            <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($dni ?? ''); ?>">

            <label for="nombre"><?php echo $idioma == 'EN' ? 'Name' : 'Nombre:'; ?></label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>">

            <label for="apellido"><?php echo $idioma == 'EN' ? 'Surname' : 'Apellido:'; ?></label>
            <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido ?? ''); ?>">

            <label for="telefono"><?php echo $idioma == 'EN' ? 'Phone' : 'Teléfono:'; ?></label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono ?? ''); ?>">

            <label for="fecha_nacimiento"><?php echo $idioma == 'EN' ? 'Date of Birth' : 'Fecha de Nacimiento:'; ?></label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($fechaNacimiento ?? ''); ?>">

            <label for="seguro"><?php echo $idioma == 'EN' ? 'Insurance' : 'Seguro:'; ?></label>
            <input type="text" id="seguro" name="seguro" value="<?php echo htmlspecialchars($seguro ?? ''); ?>">

            <div class="buttons-container">
                <button type="submit"><?php echo $idioma == 'EN' ? 'Register Student' : 'Registrar Alumno'; ?></button>
                <a href="usuario.php"><button type="button"><?php echo $idioma == 'EN' ? 'Go Back' : 'Volver Atrás'; ?></button></a>
                <a href="preferencias.php"><button type="button"><?php echo $idioma == 'EN' ? 'Preferences' : 'Preferencias'; ?></button></a>
            </div>
        </form>
    </main>

    <script>
        // Función para previsualizar la imagen seleccionada
        function mostrarPreview(event) {
            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

    <!-- Footer -->
    <footer>
        <p>
            <?php
            if (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == 'EN') {
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
