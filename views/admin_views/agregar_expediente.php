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

require_once "../../utils/db_connection.php";

// Obtener la lista de todos los pacientes con sus especies y dueños
$sql_pacientes = "SELECT p.id_paciente, p.nombre, p.edad, p.genero, p.animal, u.nombre AS dueño
                  FROM paciente p
                  LEFT JOIN usuario u ON p.id_usuario = u.id_usuario";
$result_pacientes = $conn->query($sql_pacientes);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['paciente'];
    $peso = $_POST['peso'];
    $fecha_consulta = $_POST['fecha_consulta'];
    $diagnostico = $_POST['diagnostico'];
    $tratamiento = $_POST['tratamiento'];
    $temperatura = $_POST['temperatura'];

    // Insertar los datos del expediente en la base de datos
    $sql_insert_expediente = "INSERT INTO expediente (id_paciente, peso, fecha_consulta, diagnostico, tratamiento, temperatura) 
                                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert_expediente);
    $stmt->bind_param("idsssd", $id_paciente, $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura);

    if ($stmt->execute()) {
        header("Location: ver_expedienteadmin.php");
        exit();
    } else {
        echo "Error al agregar el expediente. Por favor, inténtalo de nuevo.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Expediente</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container mt-5" style="background-color: rgba(255, 255, 255, 0.5); border-radius: 10px; padding: 20px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px);">
    <h2 class="text-center mb-4">Agregar Expediente</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="paciente">Seleccionar Paciente:</label>
            <select class="form-control" id="paciente" name="paciente">
                <?php while ($row = $result_pacientes->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_paciente']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="peso">Peso:</label>
            <input type="number" step="0.01" class="form-control" id="peso" name="peso" required>
        </div>
        <div class="form-group">
            <label for="fecha_consulta">Fecha de Consulta:</label>
            <input type="datetime-local" class="form-control" id="fecha_consulta" name="fecha_consulta" required>
        </div>
        <div class="form-group">
            <label for="diagnostico">Diagnóstico:</label>
            <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="tratamiento">Tratamiento:</label>
            <textarea class="form-control" id="tratamiento" name="tratamiento" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="temperatura">Temperatura:</label>
            <input type="number" step="0.01" class="form-control" id="temperatura" name="temperatura" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Expediente</button>
    </form>
</div>


    <?php include '../footer.php'; ?>

    <script>
        // Función para obtener y mostrar automáticamente la especie y el dueño al seleccionar un paciente
        document.getElementById('paciente').addEventListener('change', function() {
            var pacienteId = this.value;
            var pacientes = <?php echo json_encode($result_pacientes->fetch_all(MYSQLI_ASSOC)); ?>;
            var paciente = pacientes.find(p => p.id_paciente == pacienteId);
            document.getElementById('especie').value = paciente.animal;
            document.getElementById('dueño').value = paciente.dueño;
        });
    </script>

</body>

</html>
