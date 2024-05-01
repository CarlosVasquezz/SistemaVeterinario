<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../../utils/db_connection.php";

    $id_paciente = $_POST['mascota'];
    $fecha_cita = $_POST['fecha'];
    $tipo_cita = $_POST['tipo'];
    $descripcion = $_POST['descripcion']; 

    $sql = "INSERT INTO cita (id_paciente, fecha_cita, tipo_de_cita, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_paciente, $fecha_cita, $tipo_cita, $descripcion);

    if ($stmt->execute()) {
        header("Location: dashboard_usuario.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

require_once "../../utils/db_connection.php";
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT id_paciente, nombre FROM paciente WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/cancelar_cita.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Agendar Cita</h2>
    <form action="../../utils/agendar_cita.php" method="post">
        <div class="form-group">
            <label for="mascota">Mascota:</label>
            <select class="form-control" id="mascota" name="mascota">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_paciente']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha">Fecha y Hora:</label>
            <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo de Cita:</label>
            <input type="text" class="form-control" id="tipo" name="tipo" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion">
        </div>
        <button type="submit" class="btn btn-primary">Agendar Cita</button>
    </form>
</div>

<?php include '../footer.php'; ?>

</body>
</html>
