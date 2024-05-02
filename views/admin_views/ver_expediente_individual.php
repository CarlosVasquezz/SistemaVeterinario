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

// Obtener el ID del paciente seleccionado
if (!empty($_GET['id_paciente'])) {
    $id_paciente = $_GET['id_paciente'];

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

    // Obtener los expedientes médicos del paciente
    $sql_expedientes = "SELECT id_expediente, peso, fecha_consulta, diagnostico, tratamiento, temperatura
                        FROM expediente
                        WHERE id_paciente = ?";
    $stmt_expedientes = $conn->prepare($sql_expedientes);
    $stmt_expedientes->bind_param("i", $id_paciente);
    $stmt_expedientes->execute();
    $result_expedientes = $stmt_expedientes->get_result();
    $stmt_expedientes->close();
} else {
    echo "ID de paciente no especificado.";
    exit();
}

// Proceso para eliminar un expediente médico
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['id_expediente'])) {
        $id_expediente = $_POST['id_expediente'];

        // Preparar la consulta para eliminar el expediente médico
        $sql_eliminar_expediente = "DELETE FROM expediente WHERE id_expediente = ?";
        $stmt_eliminar_expediente = $conn->prepare($sql_eliminar_expediente);
        $stmt_eliminar_expediente->bind_param("i", $id_expediente);

        // Ejecutar la consulta de eliminación
        if ($stmt_eliminar_expediente->execute()) {
            // Redirigir de nuevo a la página de visualización del expediente individual del paciente
            header("Location: ver_expediente_individual.php?id_paciente=" . $result_paciente['id_paciente']);
            exit();
        } else {
            echo "Error al eliminar el expediente médico.";
        }

        // Cerrar la consulta
        $stmt_eliminar_expediente->close();
    } else {
        echo "ID de expediente no especificado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente Individual</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Expediente de <?php echo $result_paciente['nombre']; ?></h2>

        <div class="card rounded-3 shadow-lg" style="background-color: rgba(255, 255, 255, 0.2);">
            <div class="card-body" style="backdrop-filter: blur(10px);">
                <div class="row">
                    <div class="col-md-4">
                        <?php if (!empty($result_paciente['foto_contenido'])): ?>
                            <img src="data:<?php echo $result_paciente['foto_tipo']; ?>;base64,<?php echo base64_encode($result_paciente['foto_contenido']); ?>"
                                class="img-fluid rounded" alt="Foto de <?php echo $result_paciente['nombre']; ?>"
                                style="max-width: 300px;">
                        <?php else: ?>
                            <p class="text-center">Foto no disponible</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h4 class="card-title" style="font-size: 24px; font-weight: bold;">
                            <?php echo $result_paciente['nombre']; ?>
                        </h4>
                        <p><strong>Dueño:</strong> <span
                                style="font-size: 18px;"><?php echo $result_paciente['dueño']; ?></span></p>
                        <p><strong>Edad:</strong> <span
                                style="font-size: 18px;"><?php echo $result_paciente['edad']; ?></span></p>
                        <p><strong>Género:</strong> <span
                                style="font-size: 18px;"><?php echo $result_paciente['genero']; ?></span></p>
                        <p><strong>Especie:</strong> <span
                                style="font-size: 18px;"><?php echo $result_paciente['animal']; ?></span></p>
                    </div>

                </div>
            </div>
        </div>



        <div class="mt-4">
            <h4>Expedientes Médicos</h4>
            <?php if ($result_expedientes->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha de Consulta</th>
                                <th>Peso</th>
                                <th>Diagnóstico</th>
                                <th>Tratamiento</th>
                                <th>Temperatura</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_expedientes->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['fecha_consulta']; ?></td>
                                    <td><?php echo $row['peso']; ?></td>
                                    <td><?php echo $row['diagnostico']; ?></td>
                                    <td><?php echo $row['tratamiento']; ?></td>
                                    <td><?php echo $row['temperatura']; ?></td>
                                    <td>
                                        <form action="editar_expediente.php" method="get" style="display: inline-block;">
                                            <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
                                            <input type="hidden" name="id_expediente"
                                                value="<?php echo $row['id_expediente']; ?>">
                                            <button type="submit" class="btn btn-primary btn-sm" name="accion"
                                                value="editar">Editar</button>
                                        </form>
                                        <form
                                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id_paciente=" . $result_paciente['id_paciente']; ?>"
                                            method="post" style="display: inline-block;">
                                            <input type="hidden" name="id_expediente"
                                                value="<?php echo $row['id_expediente']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" name="accion" value="eliminar"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar este expediente médico?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No se encontraron expedientes médicos para este paciente.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../footer.php'; ?>

</body>

</html>