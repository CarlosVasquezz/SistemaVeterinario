<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../viwes/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cita'])) {
        $id_cita = $_POST['cita'];

        require_once "db_connection.php";

        $sql = "DELETE FROM cita WHERE numero_cita = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_cita);

        if ($stmt->execute()) {
            header("Location: ../views/user_views/dashboard_usuario.php");
            exit();
        } else {
            echo "Error al cancelar la cita. Por favor, inténtalo de nuevo.";
        }

        $stmt->close();
        $conn->close();
    } else {
        // Si no se recibió el ID de la cita a cancelar, redirigir al usuario al inicio
        header("Location: ../views/user_views/dashboard_usuario.php");
        exit();
    }
} else {
    // Si se intenta acceder directamente a este script sin enviar el formulario, redirigir al inicio
    header("Location: ../views/user_views/dashboard_usuario.php");
    exit();
}
?>
