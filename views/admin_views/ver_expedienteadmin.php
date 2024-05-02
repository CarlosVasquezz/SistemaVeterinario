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

// Obtener la lista de todos los pacientes con información de dueño, especie y fecha de la última consulta
$sql_pacientes = "SELECT p.id_paciente, p.nombre AS nombre_paciente, p.animal AS especie_paciente, 
                        CONCAT(u.nombre, ' ', u.apellido) AS dueño_paciente,
                        MAX(c.fecha_cita) AS ultima_consulta
                    FROM paciente p
                    JOIN usuario u ON p.id_usuario = u.id_usuario
                    LEFT JOIN cita c ON p.id_paciente = c.id_paciente
                    GROUP BY p.id_paciente";
$result_pacientes = $conn->query($sql_pacientes);


// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Expediente</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Expedientes Médicos</h2>

        <a href="agregar_expediente.php" class="btn btn-primary mb-3">Agregar Expediente</a>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre Paciente</th>
                        <th>Especie</th>
                        <th>Dueño</th>
                        <th>Última Consulta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_pacientes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nombre_paciente']; ?></td>
                            <td><?php echo $row['especie_paciente']; ?></td>
                            <td><?php echo $row['dueño_paciente']; ?></td>
                            <td><?php echo $row['ultima_consulta']; ?></td>
                            <td>
                                <a href="ver_expediente_individual.php?id_paciente=<?php echo $row['id_paciente']; ?>"
                                    class="btn btn-info btn-sm">Ver Expediente</a>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../footer.php'; ?>

</body>

</html>