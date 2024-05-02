<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../../utils/db_connection.php";

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['mascota'];
    $fecha_cita = $_POST['fecha'];
    $tipo_cita = $_POST['tipo'];
    $descripcion = $_POST['descripcion']; 

    // Inserta la nueva cita en la base de datos
    $sql = "INSERT INTO cita (id_paciente, fecha_cita, tipo_de_cita, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_paciente, $fecha_cita, $tipo_cita, $descripcion);

    if ($stmt->execute()) {
        // Redirecciona al usuario al dashboard después de guardar la cita
        header("Location: dashboard_usuario.php"); 
        exit();
    } else {
        // Si hay un error, muestra el mensaje de error
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obtiene la lista de mascotas del usuario actual
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
    <link rel="stylesheet" href="../../css/dashboard.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Agendar Cita</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
    </div>
</div>

<?php include '../footer.php'; ?>

</body>
</html>
