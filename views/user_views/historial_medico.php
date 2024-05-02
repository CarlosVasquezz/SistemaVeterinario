<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir la conexión a la base de datos
require_once "../../utils/db_connection.php";

// Recuperar el ID del usuario actual
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener el historial médico del usuario
$sql = "SELECT p.nombre AS nombre_paciente, p.edad AS edad_paciente, p.genero AS genero_paciente, p.animal AS especie_paciente, 
               c.fecha_cita, c.tipo_de_cita, c.descripcion AS descripcion_cita,
               co.fecha_compra, pr.nombre_producto, co.cantidad AS cantidad_comprada
        FROM paciente p
        LEFT JOIN cita c ON p.id_paciente = c.id_paciente
        LEFT JOIN compra co ON p.id_paciente = co.id_usuario
        LEFT JOIN producto pr ON co.id_producto = pr.id_producto
        WHERE p.id_usuario = ?
        ORDER BY c.fecha_cita DESC, co.fecha_compra DESC";

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
    <title>Historial Médico</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/dashboard.css">

</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Historial Médico</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
'                        <thead>
                            <tr>
                                <th>Nombre Paciente</th>
                                <th>Edad</th>
                                <th>Género</th>
                                <th>Especie</th>
                                <th>Fecha Cita</th>
                                <th>Tipo de Cita</th>
                                <th>Descripción Cita</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['nombre_paciente']; ?></td>
                                    <td><?php echo $row['edad_paciente']; ?></td>
                                    <td><?php echo $row['genero_paciente']; ?></td>
                                    <td><?php echo $row['especie_paciente']; ?></td>
                                    <td><?php echo $row['fecha_cita']; ?></td>
                                    <td><?php echo $row['tipo_de_cita']; ?></td>
                                    <td><?php echo $row['descripcion_cita']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>

</body>

</html>