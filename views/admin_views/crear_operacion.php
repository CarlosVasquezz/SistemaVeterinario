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

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

require_once "../../utils/operacion.php";

// Obtener todos los pacientes para mostrar en el formulario
$pacientes = obtenerPacientes();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['id_paciente'];
    $fecha_operacion = $_POST['fecha_operacion'];
    $descripcion = $_POST['descripcion'];

    // Crear nueva operación
    crearOperacion($id_paciente, $fecha_operacion, $descripcion);

    // Redirigir a la lista de operaciones
    header("Location: lista_operaciones.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Operación</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container mt-5" style="background-color: rgba(255, 255, 255, 0.90); border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
    <h2 class="mb-4">Crear Nueva Operación</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <div class="form-group">
            <label for="id_paciente">Nombre del Paciente:</label>
            <select class="form-control" id="id_paciente" name="id_paciente">
                <?php foreach ($pacientes as $paciente): ?>
                    <option value="<?php echo $paciente['id_paciente']; ?>"><?php echo $paciente['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_operacion">Fecha de Operación:</label>
            <input type="datetime-local" class="form-control" id="fecha_operacion" name="fecha_operacion" required style="background-color: rgba(255, 255, 255, 0.1); border-radius: 5px;">
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required style="background-color: rgba(255, 255, 255, 0.1); border-radius: 5px;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Crear Operación</button>
    </form>
</div>

</body>

</html>