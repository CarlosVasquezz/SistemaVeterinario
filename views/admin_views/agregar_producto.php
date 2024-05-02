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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];

    // Llamar a la función para insertar un nuevo producto
    insertarProducto($nombre, $tipo, $cantidad, $proveedor);

    header("Location: gestionar_productos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include '../header.php'; ?>

<div class="container mt-5" style="background-color: rgba(255, 255, 255, 0.4); border-radius: 10px; padding: 20px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px); max-width: 600px;">
    <h2 class="text-center mb-4">Agregar Nuevo Producto</h2>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="medicina">Medicina</option>
                        <option value="comida">Comida</option>
                        <option value="accesorio">Accesorio</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <div class="form-group">
                    <label for="proveedor">Proveedor:</label>
                    <input type="text" class="form-control" id="proveedor" name="proveedor" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
