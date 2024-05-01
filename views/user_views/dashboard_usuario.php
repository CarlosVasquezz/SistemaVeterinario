<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset ($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir la conexión a la base de datos
require_once "../../utils/db_connection.php";

// Recuperar el ID del usuario actual
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener la información de las mascotas del usuario
$sql = "SELECT * FROM paciente WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
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
                <h2 class="text-center mb-4">Lista de Mascotas</h2>
                <div class="row">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
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