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

require_once "operacion.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['numero_operacion'])) {
    $numero_operacion = $_GET['numero_operacion'];

    // Eliminar la operación
    eliminarOperacion($numero_operacion);

    // Redirigir a la lista de operaciones
    header("Location: ../views/admin_views/lista_operaciones.php");
    exit();
} else {
    // Si no se proporciona un número de operación válido, redirigir a la lista de operaciones
    header("Location: ../views/admin_views/lista_operaciones.php");
    exit();
}
?>
