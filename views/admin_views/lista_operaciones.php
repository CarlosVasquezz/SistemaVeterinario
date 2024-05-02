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

require_once "../../utils/operacion.php";

// Obtener todas las operaciones con los nombres de los pacientes
$operaciones = obtenerNombresPacientes();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Operaciones</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container mt-5">
    <h2 class="mb-4">Lista de Operaciones</h2>
    <a href="crear_operacion.php" class="btn btn-success mb-4">Agregar Nueva Operación</a>
    <div class="table-responsive" style="background-color: rgba(255, 255, 255, 0.90); border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número de Operación</th>
                    <th>Nombre del Paciente</th>
                    <th>Fecha de Operación</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operaciones as $operacion): ?>
                    <tr>
                        <td><?php echo $operacion['numero_operacion']; ?></td>
                        <td><?php echo $operacion['nombre_paciente']; ?></td>
                        <td><?php echo $operacion['fecha_operacion']; ?></td>
                        <td><?php echo $operacion['descripcion']; ?></td>
                        <td>
                            <a href="editar_operacion.php?numero_operacion=<?php echo $operacion['numero_operacion']; ?>"
                                class="btn btn-primary btn-sm">Editar</a>
                            <a href="../../utils/eliminar_operacion.php?numero_operacion=<?php echo $operacion['numero_operacion']; ?>"
                                class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>

</html>