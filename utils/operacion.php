<?php
require_once "db_connection.php";

function obtenerNombresPacientes() {
    global $conn;

    $sql = "SELECT o.numero_operacion, o.fecha_operacion, o.descripcion, p.nombre AS nombre_paciente
            FROM operacion o
            INNER JOIN paciente p ON o.id_paciente = p.id_paciente
            ORDER BY o.numero_operacion";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $operaciones = array();

        while ($row = $result->fetch_assoc()) {
            $operaciones[] = $row;
        }

        return $operaciones;
    } else {
        return array();
    }
}

function crearOperacion($id_paciente, $fecha_operacion, $descripcion) {
    global $conn;
    $sql = "INSERT INTO operacion (id_paciente, fecha_operacion, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id_paciente, $fecha_operacion, $descripcion);
    $stmt->execute();
    $stmt->close();
}

function obtenerOperaciones() {
    global $conn;
    $sql = "SELECT * FROM operacion";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function actualizarOperacion($numero_operacion, $id_paciente, $fecha_operacion, $descripcion) {
    global $conn;
    $sql = "UPDATE operacion SET id_paciente = ?, fecha_operacion = ?, descripcion = ? WHERE numero_operacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $id_paciente, $fecha_operacion, $descripcion, $numero_operacion);
    $stmt->execute();
    $stmt->close();
}

function eliminarOperacion($numero_operacion) {
    global $conn;
    $sql = "DELETE FROM operacion WHERE numero_operacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numero_operacion);
    $stmt->execute();
    $stmt->close();
}



function obtenerPacientes() {
    global $conn;

    $sql = "SELECT id_paciente, nombre FROM paciente";
    $result = $conn->query($sql);

    $pacientes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pacientes[] = $row;
        }
    }

    return $pacientes;
}

?>
