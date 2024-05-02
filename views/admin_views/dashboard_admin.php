<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

$nombreUsuario = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Veterinario</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <?php include '../header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Veterinario</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-transparent bg-blur">
                    <img class="card-img-top" src="../../img/Registrar" alt="Imagen 1">
                    <div class="card-body">
                        <h5 class="card-title">Registrar Consulta</h5>
                        <p class="card-description">Registra los detalles de la consulta del paciente.</p>
                        <a href="registrar_consulta.php" class="btn btn-primary">Ir a Registrar Consulta</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-transparent bg-blur">
                    <img class="card-img-top" src="../../img/vacuna" alt="Imagen 2">
                    <div class="card-body">
                        <h5 class="card-title">Registrar Vacuna</h5>
                        <p class="card-description">Registra la vacunación de un paciente.</p>
                        <br>
                        <a href="ver_vacunas.php" class="btn btn-primary">Ir a Registrar Vacuna</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-transparent bg-blur">
                    <img class="card-img-top" src="../../img/expediente" alt="Imagen 3">
                    <div class="card-body">
                        <h5 class="card-title">Ver Expediente</h5>
                        <p class="card-description">Visualiza el expediente médico de un paciente.</p>
                        <a href="ver_expedienteadmin.php" class="btn btn-primary">Ir a Ver Expediente</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-transparent bg-blur">
                    <img class="card-img-top" src="../../img/producto.png" alt="Imagen 6">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Productos</h5>
                        <p class="card-description">Agregar, actualizar o ver productos disponibles.</p>
                        <a href="gestionar_productos.php" class="btn btn-primary">Ir a Gestión de Productos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-transparent bg-blur">
                    <img class="card-img-top" src="../../img/grupo.png" alt="Imagen 6">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Usuarios</h5>
                        <p class="card-description">Administra los usuarios de la veterinaria.</p>
                        <a href="lista_usuarios.php" class="btn btn-primary">Ir a Gestión de Usuarios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-transparent bg-blur">
                    <img class="card-img-top" src="../../img/cirujano.png" alt="Imagen 6">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Operaciones</h5>
                        <p class="card-description">Administra las operaciones de la veterinaria.</p>
                        <a href="lista_operaciones.php" class="btn btn-primary">Gestión de Operaciones</a>
                    </div>
                </div>
            </div>

        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <?php include '../footer.php'; ?>
</body>

</html>