<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

// Incluir la conexión a la base de datos
require_once "db_connection.php";

// Recuperar los datos del formulario
$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$genero = $_POST['genero'];
$animal = $_POST['animal'];
$id_usuario = $_SESSION['id_usuario'];

// Procesar la imagen si se cargó
if ($_FILES['foto']['size'] > 0) {
    $foto_nombre = $_FILES['foto']['name'];
    $foto_tipo = $_FILES['foto']['type'];
    $foto_contenido = file_get_contents($_FILES['foto']['tmp_name']);

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO paciente (id_usuario, nombre, edad, genero, animal, foto_nombre, foto_tipo, foto_contenido) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isissbbs", $id_usuario, $nombre, $edad, $genero, $animal, $foto_nombre, $foto_tipo, $foto_contenido);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirigir al usuario de regreso al dashboard
    header("Location: ../views/user_views/dashboard_usuario.php");
    exit();
} else {
    // Si no se cargó una foto, redirigir con un mensaje de error
    header("Location: ../views/error.php?mensaje=Debe cargar una foto.");
    exit();
}
?>
