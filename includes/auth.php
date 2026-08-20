<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';


/**
 * Comprueba si existe un usuario autenticado.
 */
function estaAutenticado(): bool
{
    return isset($_SESSION['usuario_id']);
}


/**
 * Obliga al usuario a iniciar sesión.
 */
function requiereLogin(): void
{
    if (!estaAutenticado()) {

        header('Location: /prestamos2/login.php');
        exit;
    }
}


/**
 * Obtiene el ID del usuario actualmente conectado.
 */
function usuarioId(): ?int
{
    return isset($_SESSION['usuario_id'])
        ? (int) $_SESSION['usuario_id']
        : null;
}


/**
 * Obtiene el nombre del usuario actualmente conectado.
 */
function usuarioNombre(): ?string
{
    return $_SESSION['usuario_nombre'] ?? null;
}


/**
 * Obtiene el correo del usuario actualmente conectado.
 */
function usuarioCorreo(): ?string
{
    return $_SESSION['usuario_correo'] ?? null;
}


/**
 * Comprueba si el usuario actual tiene un permiso específico.
 *
 * Ejemplo:
 *
 * tienePermiso('herramientas.crear')
 */
function tienePermiso(string $permiso): bool
{
    if (!estaAutenticado()) {
        return false;
    }

    global $pdo;

    $sql = "
        SELECT COUNT(*)
        FROM usuario_rol ur

        INNER JOIN rol_permiso rp
            ON rp.rol_id = ur.rol_id

        INNER JOIN permisos p
            ON p.id = rp.permiso_id

        WHERE ur.usuario_id = :usuario_id
          AND p.nombre = :permiso
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':usuario_id' => usuarioId(),
        ':permiso' => $permiso
    ]);

    return (int) $stmt->fetchColumn() > 0;
}


/**
 * Obliga al usuario a tener un permiso.
 *
 * Si no tiene el permiso, muestra acceso denegado.
 */
function requierePermiso(string $permiso): void
{
    requiereLogin();

    if (!tienePermiso($permiso)) {

        http_response_code(403);

        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>

            <meta charset="UTF-8">

            <meta name="viewport"
                  content="width=device-width, initial-scale=1.0">

            <title>Acceso denegado</title>

            <link
                rel="stylesheet"
                href="/prestamos2/plugins/fontawesome-free/css/all.min.css"
            >

            <link
                rel="stylesheet"
                href="/prestamos2/dist/css/adminlte.min.css"
            >

        </head>

        <body class="hold-transition">

            <div class="content-wrapper"
                 style="margin-left: 0; padding-top: 50px;">

                <section class="content">

                    <div class="container-fluid">

                        <div class="row justify-content-center">

                            <div class="col-md-6">

                                <div class="card card-danger">

                                    <div class="card-header">

                                        <h3 class="card-title">
                                            <i class="fas fa-ban"></i>
                                            Acceso denegado
                                        </h3>

                                    </div>

                                    <div class="card-body text-center">

                                        <h4>
                                            No tiene permiso para acceder
                                        </h4>

                                        <p>
                                            No está autorizado para realizar
                                            esta operación.
                                        </p>

                                        <a
                                            href="/prestamos2/"
                                            class="btn btn-primary"
                                        >
                                            <i class="fas fa-home"></i>
                                            Volver al inicio
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

        </body>
        </html>
        ';

        exit;
    }
}


/**
 * Comprueba si el usuario actual es Administrador.
 */
function esAdministrador(): bool
{
    if (!estaAutenticado()) {
        return false;
    }

    global $pdo;

    $sql = "
        SELECT COUNT(*)
        FROM usuario_rol ur

        INNER JOIN roles r
            ON r.id = ur.rol_id

        WHERE ur.usuario_id = :usuario_id
          AND r.nombre = 'Administrador'
          AND r.estado = 'activo'
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':usuario_id' => usuarioId()
    ]);

    return (int) $stmt->fetchColumn() > 0;
}