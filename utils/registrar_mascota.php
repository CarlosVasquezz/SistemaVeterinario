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

// Insertar los datos en la base de datos
$sql = "INSERT INTO paciente (id_usuario, nombre, edad, genero, animal) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isiss", $id_usuario, $nombre, $edad, $genero, $animal);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirigir al usuario de regreso al dashboard
header("Location: ../views/user_views/dashboard_usuario.php");
exit();
?>
