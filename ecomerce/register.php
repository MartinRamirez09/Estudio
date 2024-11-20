<?php
// Configuración de conexión a la base de datos
$servername = "localhost"; // Cambia si es necesario
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña de la base de datos
$dbname = "ecomerce"; // Nombre de la base de datos

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuarioCreado = false; // Variable para controlar si el usuario fue creado

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la contraseña
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Insertar datos en la tabla usuarios
    $sql = "INSERT INTO usuarios (nombre, email, password, direccion, telefono) VALUES ('$nombre', '$email', '$password', '$direccion', '$telefono')";

    if ($conn->query($sql) === TRUE) {
        $usuarioCreado = true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/register.css">
    <style>
        /* Esconde el popup por defecto */
        #popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="direccion">Direccion:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="telefono">Telefono:</label>
                <input type="text" id="telefono" name="telefono" required>
            </div>
            <button type="submit">Registrarse</button>
        </form>
    </div>

    <!-- Popup -->
    <div class="popup" id="popup">
        <h3>¡Éxito!</h3>
        <p>Usuario creado exitosamente.</p>
    </div>

    <?php if ($usuarioCreado): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const popup = document.getElementById('popup');
                popup.style.display = 'block';
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 3000); // Espera 3 segundos antes de redirigir
            });
        </script>
    <?php endif; ?>
</body>
</html>
