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

function obtenerOperacionPorNumero($numero_operacion)
{
    global $conn;

    $sql = "SELECT * FROM operacion WHERE numero_operacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numero_operacion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

// Verificar si se ha enviado un ID de operación válido
if (isset($_GET['numero_operacion']) && !empty($_GET['numero_operacion'])) {
    $numero_operacion = $_GET['numero_operacion'];

    // Obtener los detalles de la operación a editar
    $operacion = obtenerOperacionPorNumero($numero_operacion);

    // Verificar si la operación existe
    if (!$operacion) {
        // Si no se encuentra la operación, redirigir
        header("Location: lista_operaciones.php");
        exit();
    }
} else {
    // Si no se proporciona un ID de operación válido, redirigir
    header("Location: lista_operaciones.php");
    exit();
}

// Procesar el formulario de edición si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['id_paciente'];
    $fecha_operacion = $_POST['fecha_operacion'];
    $descripcion = $_POST['descripcion'];

    // Actualizar operación existente
    actualizarOperacion($numero_operacion, $id_paciente, $fecha_operacion, $descripcion);

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
    <title>Editar Operación</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../header.php'; ?>

    <div class="container mt-5" style="background-color: rgba(255, 255, 255, 0.90); border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
        <h2 class="mb-4">Editar Operación</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"] . '?numero_operacion=' . $numero_operacion; ?>" method="post">
            <div class="form-group">
                <label for="id_paciente">ID del Paciente:</label>
                <input type="text" class="form-control" id="id_paciente" name="id_paciente"
                    value="<?php echo $operacion['id_paciente']; ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_operacion">Fecha de Operación:</label>
                <input type="datetime-local" class="form-control" id="fecha_operacion" name="fecha_operacion"
                    value="<?php echo date('Y-m-d\TH:i', strtotime($operacion['fecha_operacion'])); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                    required><?php echo $operacion['descripcion']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>

</html>