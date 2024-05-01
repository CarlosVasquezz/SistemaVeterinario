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

// Incluir la conexión a la base de datos
require_once "../../utils/db_connection.php";

// Consulta para obtener los nombres de los pacientes
$sql = "SELECT id_paciente, nombre FROM paciente";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Vacuna</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>

    <?php include '../header.php'; ?>
    <div class="container mt-5">
        <div class="card shadow" style="background-color: rgba(255, 255, 255, 0.4);">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Agregar Vacuna</h2>
                <form action="../../utils/procesar_agregar_vacuna.php" method="post">
                    <div class="form-group">
                        <label for="paciente">Paciente:</label>
                        <select class="form-control" id="paciente" name="paciente" required>
                            <option value="">Seleccione un paciente</option>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo $row['id_paciente']; ?>"><?php echo $row['nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha de Vacunación:</label>
                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre_vacuna">Nombre de la Vacuna:</label>
                        <input type="text" class="form-control" id="nombre_vacuna" name="nombre_vacuna" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_vacuna">Tipo de Vacuna:</label>
                        <input type="text" class="form-control" id="tipo_vacuna" name="tipo_vacuna" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Agregar Vacuna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>

</body>

</html>