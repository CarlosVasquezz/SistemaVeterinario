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

// Verificar si se ha enviado una solicitud POST para eliminar la vacuna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_vacuna'])) {
    $id_vacuna = $_POST['id_vacuna'];

    // Consulta para eliminar la vacuna
    $sql_delete = "DELETE FROM vacuna WHERE id_vacuna = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id_vacuna);
    $stmt->execute();

    // Redirigir al usuario de vuelta a la página de registro de vacunas después de eliminar la vacuna
    header("Location: ver_vacunas.php");
    exit();
}

// Consulta para obtener el registro de vacunas del usuario
$sql = "SELECT v.id_vacuna, v.fecha_vacuna, v.nombre_vacuna, v.tipo_vacuna, p.nombre AS nombre_paciente 
        FROM vacuna v
        JOIN paciente p ON v.id_paciente = p.id_paciente";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vacunas</title>
    <link rel="stylesheet" href="../../css/styles2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>

    <?php include '../header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Registro de Vacunas</h2>
        <a href="agregar_vacuna.php" class="btn btn-primary mb-3">Agregar Vacuna</a>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Vacuna</th>
                        <th>Fecha de Vacunación</th>
                        <th>Nombre de la Vacuna</th> <!-- Nueva columna -->
                        <th>Tipo de Vacuna</th> <!-- Nueva columna -->
                        <th>Nombre del Paciente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_vacuna']; ?></td>
                            <td><?php echo $row['fecha_vacuna']; ?></td>
                            <td><?php echo $row['nombre_vacuna']; ?></td> <!-- Mostrar nombre de la vacuna -->
                            <td><?php echo $row['tipo_vacuna']; ?></td> <!-- Mostrar tipo de vacuna -->
                            <td><?php echo $row['nombre_paciente']; ?></td>
                            <td>
                                <!-- Botón para editar -->
                                <a href="editar_vacuna.php?id=<?php echo $row['id_vacuna']; ?>"
                                    class="btn btn-info btn-sm">Editar</a>

                                <!-- Botón para eliminar (con confirmación) -->
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                                    style="display: inline-block;">
                                    <input type="hidden" name="id_vacuna" value="<?php echo $row['id_vacuna']; ?>">
                                    <button type="submit" name="eliminar_vacuna" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta vacuna?')">Eliminar</button>
                                </form>
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