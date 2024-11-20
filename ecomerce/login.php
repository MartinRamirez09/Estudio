<?php
session_start(); // Inicia la sesión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Establecer la conexión a la base de datos
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparar la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del usuario
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($password, $email['password'])) {
            // La contraseña es correcta, iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php"); // Redirigir al dashboard o página de inicio
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <div id="mensaje-login" class="mensaje"></div>
        <form id="loginForm">
            <div class="form-group">
                <label for="loginEmail">Email:</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Contraseña:</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
            <a href="register.php" class="register-link">¿No tienes una cuenta? Regístrate aquí</a>
        </form>
    </div>
    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Evitar envío del formulario
            window.location.href = "index.php"; // Redirigir al enlace deseado
        });
    </script>
</body>
</html>
