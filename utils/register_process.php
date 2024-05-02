<?php
// Incluir la conexión a la base de datos y otras configuraciones necesarias
require_once 'db_connection.php';

// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol']; // El rol se establece automáticamente como "usuario"

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuario (nombre, apellido, email, contrasena, rol) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $email, $hashed_password, $rol);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // El usuario se ha registrado correctamente
        // Iniciar sesión
        session_start();
        $_SESSION['id_usuario'] = $stmt->insert_id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['rol'] = $rol;

        // Redirigir a la página correspondiente según el rol
        if ($rol === 'admin') {
            header("Location: ../views/admin_views/dashboard_admin.php");
        } else {
            header("Location: ../views/user_views/dashboard_usuario.php");
        }
        exit();
    } else {
        // Error al registrar el usuario
        echo "Error al registrar el usuario.";
    }

    // Cerrar la consulta y la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // Si el formulario no se envió por el método POST, redirigir a la página de registro
    header("Location: views/register.php");
    exit();
}
?>
