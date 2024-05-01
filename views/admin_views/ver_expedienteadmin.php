<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../../utils/db_connection.php";

$sql_mascotas = "SELECT id_paciente, nombre FROM paciente";
$result_mascotas = $conn->query($sql_mascotas);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paciente'])) {
    $id_paciente = $_POST['paciente'];

    $sql_expediente = "SELECT p.nombre AS nombre_paciente, p.edad AS edad_paciente, p.genero AS genero_paciente, p.animal AS especie_paciente, 
                              c.fecha_cita, c.tipo_de_cita, c.descripcion AS descripcion_cita,
                              v.fecha_vacuna
                       FROM paciente p
                       LEFT JOIN cita c ON p.id_paciente = c.id_paciente
                       LEFT JOIN vacuna v ON p.id_paciente = v.id_paciente
                       WHERE p.id_paciente = ?
                       ORDER BY c.fecha_cita DESC, v.fecha_vacuna DESC";

    $stmt = $conn->prepare($sql_expediente);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $result_expediente = $stmt->get_result();
} else {
    $result_expediente = null;
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente Médico</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <?php include '../header.php'; ?> 

    <div class="container mt-5">
        <h2 class="text-center mb-4">Expediente Médico</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="paciente">Seleccionar Mascota:</label>
                <select class="form-control" id="paciente" name="paciente">
                    <?php while ($row = $result_mascotas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_paciente']; ?>"><?php echo $row['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mostrar Expediente</button>
        </form>
        <hr>
        <?php if ($result_expediente && $result_expediente->num_rows > 0) : ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre Paciente</th>
                            <th>Edad</th>
                            <th>Género</th>
                            <th>Especie</th>
                            <th>Fecha Cita</th>
                            <th>Tipo de Cita</th>
                            <th>Descripción Cita</th>
                            <th>Fecha Vacuna</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_expediente->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['nombre_paciente']; ?></td>
                                <td><?php echo $row['edad_paciente']; ?></td>
                                <td><?php echo $row['genero_paciente']; ?></td>
                                <td><?php echo $row['especie_paciente']; ?></td>
                                <td><?php echo $row['fecha_cita']; ?></td>
                                <td><?php echo $row['tipo_de_cita']; ?></td>
                                <td><?php echo $row['descripcion_cita']; ?></td>
                                <td><?php echo $row['fecha_vacuna']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paciente'])) : ?>
            <div class="alert alert-warning" role="alert">
                No se encontró información para el paciente seleccionado.
            </div>
        <?php endif; ?>
    </div>

    <?php include '../footer.php'; ?> 
</body>

</html>
