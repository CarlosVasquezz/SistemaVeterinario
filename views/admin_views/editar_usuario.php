<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

require_once "../../utils/usuarios.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $rol = $_POST["rol"];

    actualizarUsuario($id, $nombre, $apellido, $email, $rol);

    header("Location: lista_usuarios.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario = obtenerUsuarioPorId($id);
} else {
    header("Location: lista_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../header.php'; ?>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"
                style="background-color: rgba(255, 255, 255, 0.4); border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px);">
                <h2 class="text-center mb-4">Editar Usuario</h2>
                <form action="" method="POST" style="padding: 20px;">
                    <input type="hidden" name="id" value="<?php echo $usuario['id_usuario']; ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="<?php echo $usuario['nombre']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                            value="<?php echo $usuario['apellido']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $usuario['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="admin" <?php if ($usuario['rol'] == 'admin')
                                echo 'selected'; ?>>Administrador
                            </option>
                            <option value="usuario" <?php if ($usuario['rol'] == 'usuario')
                                echo 'selected'; ?>>Usuario
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>