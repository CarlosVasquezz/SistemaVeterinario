<?php
// Incluir archivo de conexión a la base de datos
include 'db_connection.php';

// Verificar si se enviaron datos de inicio de sesión
if(isset($_POST['email'], $_POST['password'])) {
    // Limpiar datos de entrada
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Consulta para verificar las credenciales del usuario
    $sql = "SELECT * FROM usuario WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) {
        // Usuario encontrado, verificar contraseña
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row['contrasena'])) {
            // Contraseña válida, iniciar sesión
            session_start();
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];

            // Redireccionar según el rol del usuario
            if($_SESSION['rol'] == 'admin') {
                header("Location: ../views/admin_views/dashboard_admin.php");
                exit();
            } else {
                header("Location: ../views/user_views/dashboard_usuario.php");
                exit();
            }
        } else {
            // Contraseña incorrecta
            header("Location: ../views/login.php?error=Contraseña incorrecta");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: ../views/login.php?error=Usuario no encontrado");
        exit();
    }
} else {
    // Redirigir si se accede directamente a este script sin enviar datos
    header("Location: ../views/login.php");
    exit();
}
?>
