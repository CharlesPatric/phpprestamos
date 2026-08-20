<?php

require_once __DIR__ . '/auth.php';

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Menú izquierdo -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Menú derecho -->
    <ul class="navbar-nav ml-auto">

        <!-- Nombre del sistema -->
        <li class="nav-item">
            <span class="nav-link">
                Sistema FCA
            </span>
        </li>

        <?php if (isset($_SESSION['usuario_id'])): ?>

            <!-- Usuario conectado -->
            <li class="nav-item">
                <span class="nav-link">
                    <i class="fas fa-user"></i>
                    <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                </span>
            </li>

            <!-- Cerrar sesión -->
            <li class="nav-item ml-2">
                <a href="/prestamos2/logout.php"
                   class="btn btn-danger btn-sm mt-1">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar sesión
                </a>
            </li>

        <?php else: ?>

            <!-- Iniciar sesión -->
            <li class="nav-item ml-2">
                <a href="/prestamos/login.php"
                   class="btn btn-primary btn-sm mt-1">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar sesión
                </a>
            </li>

        <?php endif; ?>

    </ul>

</nav>