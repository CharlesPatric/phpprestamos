<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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