<?php
session_start();

if (!isset ($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_admin.php"); 
    exit();
}

require_once "../../utils/db_connection.php";
require_once "../../utils/productos.php";


// Obtener todos los productos de la base de datos
$productos = obtenerProductos();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Gestión de Productos</h2>

        <!-- Botón para agregar nuevo producto -->
        <div class="text-right mb-3">
            <a href="agregar_producto.php" class="btn btn-success">Agregar Nuevo Producto</a>
        </div>

        <!-- Tabla para mostrar la lista de productos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Producto</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($productos)) { ?>
                    <tr>
                        <td><?php echo $producto['id_producto']; ?></td>
                        <td><?php echo $producto['nombre_producto']; ?></td>
                        <td><?php echo $producto['tipo']; ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td><?php echo $producto['proveedor']; ?></td>
                        <td>
                            <a href="editar_producto.php?id=<?php echo $producto['id_producto']; ?>"
                                class="btn btn-primary btn-sm">Editar</a>
                            <a href="../../utils/productos.php?eliminar=<?php echo $producto['id_producto']; ?>"
                                class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php include '../footer.php'; ?>
</body>

</html>