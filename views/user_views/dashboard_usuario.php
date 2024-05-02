<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir la conexión a la base de datos
require_once "../../utils/db_connection.php";

// Verificar si se ha enviado un ID de mascota válido para eliminar
if (isset($_GET['eliminar_id']) && !empty($_GET['eliminar_id'])) {
    $id_paciente = $_GET['eliminar_id'];

    // Consulta para eliminar la mascota de la base de datos
    $sql = "DELETE FROM paciente WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();

    // Manejar cualquier error o resultado de la consulta
    if ($stmt->affected_rows > 0) {
        // La mascota se eliminó correctamente
        $mensaje = "La mascota se eliminó correctamente.";
    } else {
        // No se pudo eliminar la mascota
        $mensaje = "Hubo un problema al intentar eliminar la mascota. Por favor, inténtalo de nuevo.";
    }

    // Cerrar la consulta
    $stmt->close();
}

// Consulta para obtener la información de las mascotas del usuario
$sql = "SELECT * FROM paciente WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$result = $stmt->get_result();

// Cerrar la consulta y la conexión a la base de datos
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1KPuLlRA43s74gkfw7y66YlpXDn9l0BLJVYpWzxBgPTzr5RbhRyfY4MROnyX/QEt0UuizJpHDJ1zqELhATf14A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

    </style>
</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="registro_mascota">Registrar nueva mascota</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="agenda_citas.php">Agendar Cita</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cancelar_cita.php">Cancelar Cita</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registrar_compra.php">Registrar Compra</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="historial_medico.php">Historial Médico</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="proximaCita">Próximas Citas</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 mt-4">
                <h2 class="text-center mb-4"
                    style="background-color: rgba(255, 255, 255, 0.98); border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
                    Lista de Mascotas</h2>
                <div class="row">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <?php if (!empty($row['foto_contenido'])): ?>
                                    <img src="data:<?php echo $row['foto_tipo']; ?>;base64,<?php echo base64_encode($row['foto_contenido']); ?>"
                                        class="card-img-top" alt="Foto de <?php echo $row['nombre']; ?>">
                                <?php else: ?>
                                    <div class="card-img-top no-image"></div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $row['nombre']; ?>
                                    </h5>
                                    <p class="card-text">Edad:
                                        <?php echo $row['edad']; ?>
                                    </p>
                                    <p class="card-text">Género:
                                        <?php echo $row['genero']; ?>
                                    </p>
                                    <p class="card-text">Especie:
                                        <?php echo $row['animal']; ?>
                                    </p>
                                    <div class="btn-group" role="group">
                                        <!-- Botón de editar con ícono de lápiz -->
                                        <a href="editar_mascota.php?id=<?php echo $row['id_paciente']; ?>"
                                            class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
                                        <!-- Botón de eliminar con ícono de papelera -->
                                        <a href="?eliminar_id=<?php echo $row['id_paciente']; ?>" class="btn btn-danger"><i
                                                class="fas fa-trash-alt"></i> Eliminar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </main>

        </div>
    </div>
    <?php include '../footer.php'; ?>

</body>

</html>