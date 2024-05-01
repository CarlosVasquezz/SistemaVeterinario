<?php

require_once "db_connection.php";

// Función para obtener todos los productos de la base de datos
function obtenerProductos() {
    global $conn;
    $query = "SELECT * FROM producto";
    $result = $conn->query($query);
    return $result;
}

// Función para insertar un nuevo producto en la base de datos
function insertarProducto($nombre, $tipo, $cantidad, $proveedor) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO producto (nombre_producto, tipo, cantidad, proveedor) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $nombre, $tipo, $cantidad, $proveedor);
    $stmt->execute();
    $stmt->close();
}

// Función para actualizar un producto en la base de datos
function actualizarProducto($id, $nombre, $tipo, $cantidad, $proveedor) {
    global $conn;
    $stmt = $conn->prepare("UPDATE producto SET nombre_producto=?, tipo=?, cantidad=?, proveedor=? WHERE id_producto=?");
    $stmt->bind_param("ssisi", $nombre, $tipo, $cantidad, $proveedor, $id);
    $stmt->execute();
    $stmt->close();
}

// Función para eliminar un producto de la base de datos
function eliminarProducto($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM producto WHERE id_producto=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Verificar si se ha enviado el formulario de agregar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];
    insertarProducto($nombre, $tipo, $cantidad, $proveedor);
    header("Location: gestion_productos.php");
    exit();
}

// Verificar si se ha enviado el formulario de actualizar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];
    actualizarProducto($id, $nombre, $tipo, $cantidad, $proveedor);
    header("Location: gestionar_productos.php");
    exit();
}

// Verificar si se ha enviado la solicitud para eliminar un producto
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    eliminarProducto($id);
    header("Location: ../views/admin_views/gestionar_productos.php");
    exit();
}

?>
