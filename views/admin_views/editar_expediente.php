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

// Obtener la ID del paciente y del expediente seleccionados
if (!empty($_GET['id_paciente']) && !empty($_GET['id_expediente'])) {
    $id_paciente = $_GET['id_paciente'];
    $id_expediente = $_GET['id_expediente'];

    // Obtener los datos del paciente
    $sql_paciente = "SELECT p.id_paciente, p.nombre, p.edad, p.genero, p.animal, 
                            p.foto_nombre, p.foto_tipo, p.foto_contenido,
                            u.nombre AS dueño
                     FROM paciente p
                     LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
                     WHERE p.id_paciente = ?";
    $stmt_paciente = $conn->prepare($sql_paciente);
    $stmt_paciente->bind_param("i", $id_paciente);
    $stmt_paciente->execute();
    $result_paciente = $stmt_paciente->get_result()->fetch_assoc();
    $stmt_paciente->close();

    // Obtener los datos del expediente médico seleccionado
    $sql_expediente = "SELECT peso, fecha_consulta, diagnostico, tratamiento, temperatura
                      FROM expediente
                      WHERE id_expediente = ? AND id_paciente = ?";
    $stmt_expediente = $conn->prepare($sql_expediente);
    $stmt_expediente->bind_param("ii", $id_expediente, $id_paciente);
    $stmt_expediente->execute();
    $result_expediente = $stmt_expediente->get_result()->fetch_assoc();
    $stmt_expediente->close();

    // Verificar si se han enviado datos para editar
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar los datos del formulario de edición
        $peso = $_POST['peso'];
        $fecha_consulta = $_POST['fecha_consulta'];
        $diagnostico = $_POST['diagnostico'];
        $tratamiento = $_POST['tratamiento'];
        $temperatura = $_POST['temperatura'];

        // Actualizar los datos del expediente médico en la base de datos
        $sql_actualizar = "UPDATE expediente
                           SET peso = ?, fecha_consulta = ?, diagnostico = ?, tratamiento = ?, temperatura = ?
                           WHERE id_expediente = ? AND id_paciente = ?";
        $stmt_actualizar = $conn->prepare($sql_actualizar);
        $stmt_actualizar->bind_param("ssssiii", $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura, $id_expediente, $id_paciente);

        if ($stmt_actualizar->execute()) {
            // Redirigir de nuevo a la página de visualización del expediente individual del paciente
            header("Location: ver_expediente_individual.php?id_paciente=" . $id_paciente);
            exit();
        } else {
            echo "Error al actualizar el expediente médico.";
        }

        // Cerrar la consulta
        $stmt_actualizar->close();
    }
} else {
    echo "ID de paciente o expediente médico no especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Expediente</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container mt-5"
        style="background-color: rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 20px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px);">
        <h2 class="text-center mb-4">Editar Expediente de <?php echo $result_paciente['nombre']; ?></h2>
        <form
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id_paciente=" . $id_paciente . "&id_expediente=" . $id_expediente; ?>"
            method="post">
            <div class="form-group">
                <label for="peso">Peso:</label>
                <input type="text" class="form-control" id="peso" name="peso"
                    value="<?php echo $result_expediente['peso']; ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_consulta">Fecha de Consulta:</label>
                <input type="date" class="form-control" id="fecha_consulta" name="fecha_consulta"
                    value="<?php echo $result_expediente['fecha_consulta']; ?>" required>
            </div>
            <div class="form-group">
                <label for="diagnostico">Diagnóstico:</label>
                <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3"
                    required><?php echo $result_expediente['diagnostico']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="tratamiento">Tratamiento:</label>
                <textarea class="form-control" id="tratamiento" name="tratamiento" rows="3"
                    required><?php echo $result_expediente['tratamiento']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="temperatura">Temperatura:</label>
                <input type="text" class="form-control" id="temperatura" name="temperatura"
                    value="<?php echo $result_expediente['temperatura']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>


    <?php include '../footer.php'; ?>

</body>

</html>