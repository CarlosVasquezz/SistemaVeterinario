<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['paciente'];
    $fecha_cita = $_POST['fecha'];
    $tipo_cita = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];


    require_once "db_connection.php";

    $sql = "INSERT INTO cita (id_paciente, fecha_cita, tipo_de_cita, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_paciente, $fecha_cita, $tipo_cita, $descripcion);

    if ($stmt->execute()) {
        header("Location: ../views/user_views/dashboard_usuario.php");
        exit();
    } else {
        echo "Error al agendar la cita. Por favor, inténtalo de nuevo.";
    }

    $stmt->close();
    $conn->close();
}
?>
