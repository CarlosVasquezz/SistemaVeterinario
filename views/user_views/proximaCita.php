<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../../utils/db_connection.php";

$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener la próxima cita del usuario
$sql = "SELECT p.nombre AS nombre_paciente, c.fecha_cita, c.tipo_de_cita, c.descripcion AS descripcion_cita
        FROM paciente p
        JOIN cita c ON p.id_paciente = c.id_paciente
        WHERE p.id_usuario = ? AND c.fecha_cita > NOW()
        ORDER BY c.fecha_cita ASC
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Cerrar la consulta
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Próxima Cita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/cancelar_cita.css">

</head>

<body>

    <?php include '../header.php'; ?> 

    <div class="container mt-5">
        <h2 class="text-center mb-4">Próxima Cita</h2>
        <?php if ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Paciente: <?php echo $row['nombre_paciente']; ?></h5>
                    <p class="card-text">Fecha de la cita: <?php echo $row['fecha_cita']; ?></p>
                    <p class="card-text">Tipo de cita: <?php echo $row['tipo_de_cita']; ?></p>
                    <p class="card-text">Descripción: <?php echo $row['descripcion_cita']; ?></p>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center">No hay citas próximas programadas.</p>
        <?php endif; ?>
    </div>

    <?php include '../footer.php'; ?> 
</body>

</html>
