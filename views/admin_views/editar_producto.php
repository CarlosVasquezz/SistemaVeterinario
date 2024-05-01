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

require_once "../../utils/db_connection.php";
require_once "../../utils/productos.php";


// Verificar si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];

    // Preparar la consulta SQL para actualizar el producto
    $sql = "UPDATE producto SET nombre_producto=?, tipo=?, cantidad=?, proveedor=? WHERE id_producto=?";
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt) {
        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param("ssisi", $nombre, $tipo, $cantidad, $proveedor, $id);
        if ($stmt->execute()) {
            header("Location: gestionar_productos.php");
            exit();
        } else {
            echo "Error al actualizar el producto. Por favor, inténtalo de nuevo.";
        }
    } else {
        echo "Error en la preparación de la consulta.";
    }

    // Cerrar la consulta preparada
    $stmt->close();
} else {
    // Obtener el ID del producto de la URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consulta SQL para obtener los detalles del producto
        $sql = "SELECT * FROM producto WHERE id_producto=?";
        $stmt = $conn->prepare($sql);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular el parámetro y ejecutar la consulta
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            // Verificar si se encontraron resultados
            if ($resultado->num_rows == 1) {
                $producto = $resultado->fetch_assoc();
            } else {
                echo "No se encontró el producto.";
                exit();
            }
        } else {
            echo "Error en la preparación de la consulta.";
            exit();
        }

        // Cerrar la consulta preparada
        $stmt->close();
    } else {
        echo "ID del producto no especificado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Producto</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">
            <div class="form-group">
                <label for="nombre">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                    value="<?php echo $producto['nombre_producto']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="medicina" <?php echo ($producto['tipo'] == 'medicina') ? 'selected' : ''; ?>>Medicina
                    </option>
                    <option value="comida" <?php echo ($producto['tipo'] == 'comida') ? 'selected' : ''; ?>>Comida
                    </option>
                    <option value="accesorio" <?php echo ($producto['tipo'] == 'accesorio') ? 'selected' : ''; ?>>
                        Accesorio</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad"
                    value="<?php echo $producto['cantidad']; ?>" required>
            </div>
            <div class="form-group">
                <label for="proveedor">Proveedor</label>
                <input type="text" class="form-control" id="proveedor" name="proveedor"
                    value="<?php echo $producto['proveedor']; ?>" required>
            </div>
            <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
            <a href="gestionar_productos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>

</html>