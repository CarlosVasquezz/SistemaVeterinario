<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset ($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir la conexión a la base de datos
require_once "../../utils/db_connection.php";

// Verificar si se ha enviado un ID de mascota válido
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Consulta para obtener la información de la mascota
    $sql = "SELECT * FROM paciente WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $mascota = $result->fetch_assoc();
    } else {
        // Si no se encuentra la mascota, redirigir
        header("Location: dashboard.php");
        exit();
    }
} else {
    // Si no se proporciona un ID de mascota válido, redirigir
    header("Location: dashboard.php");
    exit();
}

// Procesar el formulario de edición si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $animal = $_POST['animal'];
    
    // Procesar la imagen si se cargó
    if ($_FILES['foto']['size'] > 0) {
        $imagen_nombre = $_FILES['foto']['name'];
        $imagen_tmp = $_FILES['foto']['tmp_name'];
        $imagen_tipo = $_FILES['foto']['type'];
        
        // Guardar la imagen en la base de datos
        $imagen_contenido = file_get_contents($imagen_tmp);
        
        // Actualizar la mascota en la base de datos con la imagen
        $sql = "UPDATE paciente SET nombre = ?, edad = ?, genero = ?, animal = ?, foto_nombre = ?, foto_tipo = ?, foto_contenido = ? WHERE id_paciente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssssi", $nombre, $edad, $genero, $animal, $imagen_nombre, $imagen_tipo, $imagen_contenido, $id_paciente);
    } else {
        // Actualizar la mascota en la base de datos sin la imagen
        $sql = "UPDATE paciente SET nombre = ?, edad = ?, genero = ?, animal = ? WHERE id_paciente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissi", $nombre, $edad, $genero, $animal, $id_paciente);
    }
    
    // Ejecutar la actualización
    $stmt->execute();

    // Redirigir a la página de lista de mascotas
    header("Location: dashboard_usuario.php");
    exit();
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container mt-4" style="background-color: rgba(255, 255, 255, 0.90); border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
    <h2 class="mb-4">Editar Mascota</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $mascota['nombre']; ?>">
        </div>
        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="text" class="form-control" id="edad" name="edad" value="<?php echo $mascota['edad']; ?>">
        </div>
        <div class="form-group">
            <label for="genero">Género:</label>
            <select class="form-control" id="genero" name="genero">
                <option value="macho" <?php if ($mascota['genero'] == 'macho') echo 'selected'; ?>>Macho</option>
                <option value="hembra" <?php if ($mascota['genero'] == 'hembra') echo 'selected'; ?>>Hembra</option>
                <option value="otro" <?php if ($mascota['genero'] == 'otro') echo 'selected'; ?>>Otro</option>
            </select>
        </div>
        <div class="form-group">
            <label for="animal">Especie:</label>
            <input type="text" class="form-control" id="animal" name="animal" value="<?php echo $mascota['animal']; ?>">
        </div>
        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" class="form-control-file" id="foto" name="foto">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>


    <?php include '../footer.php'; ?>

</body>

</html>
