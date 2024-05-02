<?php

require_once "db_connection.php";

// Función para obtener todos los expedientes médicos de un paciente
function obtenerExpedientes($id_paciente) {
    global $conn;
    $sql = "SELECT * FROM expediente WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    return $stmt->get_result();
}

// Función para agregar un nuevo expediente médico
function agregarExpediente($id_paciente, $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura) {
    global $conn;
    $sql = "INSERT INTO expediente (id_paciente, peso, fecha_consulta, diagnostico, tratamiento, temperatura) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idsssd", $id_paciente, $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura);
    return $stmt->execute();
}

// Función para actualizar un expediente médico existente
function actualizarExpediente($id_expediente, $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura) {
    global $conn;
    $sql = "UPDATE expediente SET peso = ?, fecha_consulta = ?, diagnostico = ?, tratamiento = ?, temperatura = ? WHERE id_expediente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dsssdi", $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura, $id_expediente);
    return $stmt->execute();
}

// Función para eliminar un expediente médico existente
function eliminarExpediente($id_expediente) {
    global $conn;
    $sql = "DELETE FROM expediente WHERE id_expediente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_expediente);
    return $stmt->execute();
}

// Código para manejar las solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Agregar un nuevo expediente médico
    if (isset($_POST['agregar_expediente'])) {
        $id_paciente = $_POST['id_paciente'];
        $peso = $_POST['peso'];
        $fecha_consulta = $_POST['fecha_consulta'];
        $diagnostico = $_POST['diagnostico'];
        $tratamiento = $_POST['tratamiento'];
        $temperatura = $_POST['temperatura'];
        if (agregarExpediente($id_paciente, $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura)) {
            header("Location: expediente.php");
            exit();
        } else {
            echo "Error al agregar el expediente médico.";
        }
    }
    // Actualizar un expediente médico existente
    elseif (isset($_POST['editar_expediente'])) {
        $id_expediente = $_POST['id_expediente'];
        $peso = $_POST['peso'];
        $fecha_consulta = $_POST['fecha_consulta'];
        $diagnostico = $_POST['diagnostico'];
        $tratamiento = $_POST['tratamiento'];
        $temperatura = $_POST['temperatura'];
        if (actualizarExpediente($id_expediente, $peso, $fecha_consulta, $diagnostico, $tratamiento, $temperatura)) {
            header("Location: expediente.php");
            exit();
        } else {
            echo "Error al actualizar el expediente médico.";
        }
    }
    // Eliminar un expediente médico existente
    elseif (isset($_POST['eliminar_expediente'])) {
        $id_expediente = $_POST['id_expediente'];
        if (eliminarExpediente($id_expediente)) {
            header("Location: expediente.php");
            exit();
        } else {
            echo "Error al eliminar el expediente médico.";
        }
    }
}
?>
