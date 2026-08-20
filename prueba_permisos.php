<?php

require_once __DIR__ . '/includes/auth.php';

requiereLogin();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Prueba de permisos</title>

</head>

<body>

    <h1>Prueba del sistema de permisos</h1>

    <p>
        Usuario:
        <strong>
            <?= htmlspecialchars(usuarioNombre()) ?>
        </strong>
    </p>

    <hr>

    <h3>Permisos</h3>

    <p>
        herramientas.ver:

        <?= tienePermiso('herramientas.ver')
            ? '✅ TIENE PERMISO'
            : '❌ NO TIENE PERMISO'
        ?>
    </p>

    <p>
        herramientas.crear:

        <?= tienePermiso('herramientas.crear')
            ? '✅ TIENE PERMISO'
            : '❌ NO TIENE PERMISO'
        ?>
    </p>

    <p>
        herramientas.eliminar:

        <?= tienePermiso('herramientas.eliminar')
            ? '✅ TIENE PERMISO'
            : '❌ NO TIENE PERMISO'
        ?>
    </p>

    <p>
        prestamos.crear:

        <?= tienePermiso('prestamos.crear')
            ? '✅ TIENE PERMISO'
            : '❌ NO TIENE PERMISO'
        ?>
    </p>

    <p>
        usuarios.crear:

        <?= tienePermiso('usuarios.crear')
            ? '✅ TIENE PERMISO'
            : '❌ NO TIENE PERMISO'
        ?>
    </p>

    <p>
        roles.editar:

        <?= tienePermiso('roles.editar')
            ? '✅ TIENE PERMISO'
            : '❌ NO TIENE PERMISO'
        ?>
    </p>

    <hr>

    <p>
        ¿Es Administrador?

        <?= esAdministrador()
            ? '✅ SÍ'
            : '❌ NO'
        ?>
    </p>

</body>

</html>