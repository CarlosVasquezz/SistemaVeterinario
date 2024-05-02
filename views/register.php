<?php
session_start();

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['id_usuario'])) {
    // Redireccionar al dashboard de usuario si ya ha iniciado sesión
    header("Location: user_views/dashboard_usuario.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-blur">
                    <div class="card-body card-form">
                        <h2 class="card-title text-center mb-4">Registrarse</h2>
                        <form action="../utils/register_process.php" method="POST">
                            <!-- Espacio para la foto -->
                            <div class="form-photo text-center">
                                <i class="fas fa-user-plus fa-5x"></i> <!-- Icono de usuario -->
                            </div>
                            <div class="form-group">
                                <!-- Icono de nombre -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        placeholder="Nombre" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- Icono de apellido -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="apellido" name="apellido"
                                        placeholder="Apellido" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- Icono de correo electrónico -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Correo Electrónico" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- Icono de contraseña -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Contraseña" required>
                                </div>
                            </div>
                            <input type="hidden" name="rol" value="usuario">
                            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>