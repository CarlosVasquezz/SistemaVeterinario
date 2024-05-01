<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['paciente'];
    $fecha_vacuna = $_POST['fecha'];
    $nombre_vacuna = $_POST['nombre_vacuna']; 
    $tipo_vacuna = $_POST['tipo_vacuna']; 

    require_once "db_connection.php";

    $sql = "INSERT INTO vacuna (id_paciente, fecha_vacuna, nombre_vacuna, tipo_vacuna) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_paciente, $fecha_vacuna, $nombre_vacuna, $tipo_vacuna);

    if ($stmt->execute()) {
        header("Location: ../views/admin_views/ver_vacunas.php");
        exit();
    } else {
        echo "Error al agregar la vacuna. Por favor, inténtalo de nuevo.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../views/admin_views/agregar_vacuna.php");
    exit();
}
?>
