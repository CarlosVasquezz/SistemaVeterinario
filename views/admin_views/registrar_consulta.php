<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

require_once "../../utils/db_connection.php";

// Consulta para obtener la lista de todos los pacientes
$sql = "SELECT * FROM paciente";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita Veterinaria</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/styles.css">
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
                        <h1 class="card-title text-center mb-4">Agendar Cita Veterinaria</h1>
                        <form action="../../utils/procesar_agendar_cita.php" method="post">
                            <div class="form-group">
                                <label for="paciente">Selecciona el paciente:</label>
                                <select class="form-control" name="paciente" id="paciente">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_paciente']; ?>">
                                            <?php echo $row['nombre']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha">Fecha de la Cita:</label>
                                <input type="datetime-local" class="form-control" id="fecha" name="fecha">
                            </div>

                            <div class="form-group">
                                <label for="tipo_cita">Tipo de Cita:</label>
                                <input type="text" class="form-control" id="tipo_cita" name="tipo_cita">
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Agendar Cita</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>