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

// Verificar si se ha enviado una solicitud POST para eliminar un usuario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar"])) {
    $id = $_POST["id"];
    eliminarUsuario($id);
    // Después de eliminar, redirigir a la misma página para actualizar la lista de usuarios
    header("Location: lista_usuarios.php");
    exit();
}

// Obtener la lista de usuarios
$usuarios = obtenerUsuarios();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<?php include '../header.php'; ?>

<body>
    <div class="container mt-5"
        style="background-color: rgba(255, 255, 255, 0.4); border-radius: 10px; padding: 20px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px);">
        <h2 class="text-center mb-4">Lista de Usuarios</h2>
        <a href="agregar_usuario.php" class="btn btn-primary mb-3">Agregar Usuario</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = mysqli_fetch_assoc($usuarios)) { ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['apellido']; ?></td>
                        <td><?php echo $usuario['email']; ?></td>
                        <td><?php echo $usuario['rol']; ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>"
                                class="btn btn-primary btn-sm">Editar</a>
                            <!-- Formulario para enviar la solicitud de eliminación -->
                            <form action="" method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $usuario['id_usuario']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" name="eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>