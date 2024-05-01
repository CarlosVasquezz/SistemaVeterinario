<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/cancelar_cita.css">

</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Registrar Compra</h2>
        <form action="../../utils/procesar_compra.php" method="POST">
            <div class="form-group">
                <label for="producto">Producto:</label>
                <select class="form-control" id="producto" name="producto">
                    <?php
                    require_once "../../utils/db_connection.php";

                    $sql = "SELECT id_producto, nombre_producto FROM producto";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id_producto'] . "'>" . $row['nombre_producto'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Compra</button>
        </form>
    </div>

    <?php include '../footer.php'; ?>

</body>

</html>
