<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nueva Mascota</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/registroMascota.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <h2>Registrar Nueva Mascota</h2>
    <!-- Formulario de registro de mascota -->
    <form action="../../utils/registrar_mascota.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" class="form-control" id="edad" name="edad" required>
        </div>
        <div class="form-group">
            <label for="genero">Género:</label>
            <select class="form-control" id="genero" name="genero" required>
                <option value="macho">Macho</option>
                <option value="hembra">Hembra</option>
                <option value="otro">Otro</option>
            </select>
        </div>
        <div class="form-group">
            <label for="animal">Especie:</label>
            <input type="text" class="form-control" id="animal" name="animal" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Mascota</button>
    </form>
</div>

<?php include '../footer.php'; ?>

</body>
</html>
