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

// Verificar si se ha proporcionado un ID de vacuna válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: registro_vacunas.php");
    exit();
}

// Incluir la conexión a la base de datos
require_once "../../utils/db_connection.php";

// Obtener el ID de la vacuna desde la URL
$id_vacuna = $_GET['id'];

// Consulta para obtener la información de la vacuna
$sql = "SELECT * FROM vacuna WHERE id_vacuna = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_vacuna);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se ha encontrado la vacuna
if ($result->num_rows === 0) {
    header("Location: registro_vacunas.php");
    exit();
}

// Obtener los datos de la vacuna
$vacuna = $result->fetch_assoc();

// Verificar si se ha enviado una solicitud POST para editar la vacuna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_vacuna'])) {
    $nombre_vacuna = $_POST['nombre_vacuna'];
    $tipo_vacuna = $_POST['tipo_vacuna'];
    $fecha_vacuna = $_POST['fecha_vacuna'];

    // Consulta para actualizar la vacuna
    $sql_update = "UPDATE vacuna SET nombre_vacuna = ?, tipo_vacuna = ?, fecha_vacuna = ? WHERE id_vacuna = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssi", $nombre_vacuna, $tipo_vacuna, $fecha_vacuna, $id_vacuna);
    $stmt->execute();

    // Redirigir al usuario de vuelta a la página de registro de vacunas después de editar la vacuna
    header("Location: ver_vacunas.php");
    exit();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vacuna</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-form {
            background-color: rgba(255, 255, 255, 0.5); 
            backdrop-filter: blur(10px); 
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-form">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Editar Vacuna</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_vacuna; ?>" method="post">
                            <div class="form-group">
                                <label for="nombre_vacuna">Nombre de la Vacuna:</label>
                                <input type="text" class="form-control" id="nombre_vacuna" name="nombre_vacuna" value="<?php echo $vacuna['nombre_vacuna']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="tipo_vacuna">Tipo de Vacuna:</label>
                                <input type="text" class="form-control" id="tipo_vacuna" name="tipo_vacuna" value="<?php echo $vacuna['tipo_vacuna']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_vacuna">Fecha de Vacunación:</label>
                                <input type="datetime-local" class="form-control" id="fecha_vacuna" name="fecha_vacuna" value="<?php echo $vacuna['fecha_vacuna']; ?>" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="editar_vacuna" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
