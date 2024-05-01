<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $id_producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];

    require_once "db_connection.php";

    $conn->begin_transaction();

    try {
        $sql_insert = "INSERT INTO compra (id_usuario, id_producto, cantidad, fecha_compra) VALUES (?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $id_usuario, $id_producto, $cantidad);

        $stmt_insert->execute();

        // Actualizar la cantidad disponible del producto
        $sql_update = "UPDATE producto SET cantidad = cantidad - ? WHERE id_producto = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $cantidad, $id_producto);

        // Ejecutar la consulta de actualización
        $stmt_update->execute();

        // Confirmar la transacción
        $conn->commit();

        // Redirigir al usuario al inicio después de registrar la compra
        header("Location: ../views/user_views/dashboard_usuario.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();

        // Mostrar un mensaje de error
        echo "Error al registrar la compra. Por favor, inténtalo de nuevo.";
    }

    // Cerrar las consultas preparadas y la conexión a la base de datos
    $stmt_insert->close();
    $stmt_update->close();
    $conn->close();
} else {
    // Si se intenta acceder directamente a este script sin enviar el formulario, redirigir al inicio
    header("Location: ../views/user_views/dashboard_usuario.php");
    exit();
}
?>
