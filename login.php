<?php

session_start();

require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($correo === '' || $password === '') {

        $error = 'Ingrese su correo y contraseña.';

    } else {

        $sql = "
            SELECT
                registro,
                nombre,
                correo,
                password_hash
            FROM lector
            WHERE correo = :correo
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':correo' => $correo
        ]);

        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['registro'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_correo'] = $usuario['correo'];

            header('Location: index.php');
            exit;

        } else {

            $error = 'Correo o contraseña incorrectos.';

        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Préstamo de Herramientas</title>

    <link
        rel="stylesheet"
        href="plugins/fontawesome-free/css/all.min.css"
    >

    <link
        rel="stylesheet"
        href="dist/css/adminlte.min.css"
    >

</head>

<body class="hold-transition login-page">

<div class="login-box">

    <div class="card card-outline card-primary">

        <div class="card-header text-center">

            <h1 class="h1">
                <b>Préstamo</b>
            </h1>

            <p class="mb-0">
                Sistema de herramientas
            </p>

        </div>

        <div class="card-body">

            <p class="login-box-msg">
                Inicie sesión para continuar
            </p>

            <?php if ($error): ?>

                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="input-group mb-3">

                    <input
                        type="email"
                        name="correo"
                        class="form-control"
                        placeholder="Correo electrónico"
                        required
                        autofocus
                    >

                    <div class="input-group-append">

                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>

                    </div>

                </div>

                <div class="input-group mb-3">

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Contraseña"
                        required
                    >

                    <div class="input-group-append">

                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                >
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar sesión
                </button>

            </form>

        </div>

    </div>

</div>

<script src="plugins/jquery/jquery.min.js"></script>

<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="dist/js/adminlte.min.js"></script>

</body>

</html>