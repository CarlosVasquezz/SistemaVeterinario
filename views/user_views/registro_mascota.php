<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nueva Mascota</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
</head>

<body>

    <?php include '../header.php'; ?>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Registrar Nueva Mascota</h2>
                <form action="../../utils/registrar_mascota.php" method="POST" enctype="multipart/form-data"
                    style="padding: 20px;">
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
                    <!-- Campo para cargar la foto -->
                    <div class="form-group">
                        <label for="foto">Foto:</label>
                        <input type="file" class="form-control-file" id="foto" name="foto" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar Mascota</button>
                </form>
            </div>
        </div>
    </div>


</body>

</html>