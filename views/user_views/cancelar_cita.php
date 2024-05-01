<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../../utils/db_connection.php";

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT * FROM cita WHERE id_paciente IN (SELECT id_paciente FROM paciente WHERE id_usuario = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cancelar Cita</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/cancelar_cita.css">

    </head>
    <body>
    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Cancelar Cita</h2>
        <form action="../../utils/procesar_cancelar_cita.php" method="post">
            <div class="form-group">
                <label for="cita">Seleccione la cita que desea cancelar:</label>
                <select class="form-control" id="cita" name="cita" required>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['numero_cita']; ?>">
                            <?php echo $row['tipo_de_cita'] . ' - ' . date('d/m/Y H:i', strtotime($row['fecha_cita'])); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger">Cancelar Cita</button>
        </form>
    </div>

    <?php include '../footer.php'; ?>

    </body>
    </html>

    <?php
} else {
    echo "<p>No tienes citas programadas.</p>";
}

$stmt->close();
$conn->close();
?>
