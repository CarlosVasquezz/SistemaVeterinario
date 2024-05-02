<?php
require_once "db_connection.php";

// Función para obtener todos los usuarios de la base de datos
function obtenerUsuarios() {
    global $conn;
    $sql = "SELECT * FROM usuario";
    $resultado = mysqli_query($conn, $sql);
    if (!$resultado) {
        echo "Error al obtener los usuarios: " . mysqli_error($conn);
        exit();
    }
    return $resultado;
}

// Función para obtener un usuario por su ID
function obtenerUsuarioPorId($id) {
    global $conn;
    $sql = "SELECT * FROM usuario WHERE id_usuario = $id";
    $resultado = mysqli_query($conn, $sql);
    if (!$resultado) {
        echo "Error al obtener el usuario: " . mysqli_error($conn);
        exit();
    }
    return mysqli_fetch_assoc($resultado);
}


// Función para actualizar un usuario en la base de datos
function actualizarUsuario($id, $nombre, $apellido, $email, $rol) {
    global $conn;
    $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', email='$email', rol='$rol' WHERE id_usuario=$id";
    $resultado = mysqli_query($conn, $sql);
    if (!$resultado) {
        echo "Error al actualizar el usuario: " . mysqli_error($conn);
        exit();
    }
    return $resultado;
}

// Función para eliminar un usuario de la base de datos
function eliminarUsuario($id) {
    global $conn;
    $sql = "DELETE FROM usuario WHERE id_usuario=$id";
    $resultado = mysqli_query($conn, $sql);
    if (!$resultado) {
        echo "Error al eliminar el usuario: " . mysqli_error($conn);
        exit();
    }
    return $resultado;
}
?>
