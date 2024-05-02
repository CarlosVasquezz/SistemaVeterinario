<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

require_once '../../utils/db_connection.php';


// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuario (nombre, apellido, email, contrasena, rol) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $email, $hashed_password, $rol);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // El usuario se ha registrado correctamente
        $mensaje = "Usuario agregado exitosamente.";
        // Redirigir a la página de lista de usuarios
        header("Location: lista_usuarios.php");
        exit();
    } else {
        $mensaje = "Error al agregar usuario. Por favor, inténtalo de nuevo.";
    }

    // Cerrar la consulta
    $stmt->close();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<?php include '../header.php'; ?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6" style="background-color: rgba(255, 255, 255, 0.4); border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px);">
            <h2 class="text-center mb-4">Agregar Usuario</h2>
            <form action="" method="post" style="padding: 20px;">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select class="form-control" id="rol" name="rol" required>
                        <option value="admin">Administrador</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Usuario</button>
            </form>
        </div>
    </div>
</div>

</body>

</html>