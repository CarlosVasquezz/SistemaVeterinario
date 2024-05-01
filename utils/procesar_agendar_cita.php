<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/user_views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['paciente'];
    $fecha_cita = $_POST['fecha'];
    $tipo_cita = $_POST['tipo_cita'];
    $descripcion = $_POST['descripcion'];


    require_once "db_connection.php";

    $sql = "INSERT INTO cita (id_paciente, fecha_cita, tipo_de_cita, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_paciente, $fecha_cita, $tipo_cita, $descripcion);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "La cita se ha agendado correctamente.";
    } else {
        $_SESSION['error'] = "Error al agendar la cita. Por favor, inténtalo de nuevo.";
    }

    $stmt->close();
    $conn->close();

    // Redirigir al usuario de vuelta al formulario de agendar cita
    header("Location: ../views/admin_views/dashboard_admin.php");
    exit();
} else {
    // Si se intenta acceder directamente a este script sin enviar el formulario, redirigir al inicio
    header("Location: ../views/admin_views/dashboard_admin.php");
    exit();
}
?>
