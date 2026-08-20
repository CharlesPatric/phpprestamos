<?php
require_once __DIR__ . '/../includes/auth.php';
requierePermiso('herramientas.ver');

require_once __DIR__ . '/../config/database.php';
$sql = "SELECT * FROM herramientas ORDER BY id DESC";
$stmt = $pdo->query($sql);
$herramientas = $stmt->fetchAll();
//se agrega seguridad


// AdminLTE
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<!-- CONTENIDO PRINCIPAL -->
<div class="content-wrapper">
    <!-- Encabezado -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        Herramientas
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/prestamos2/index.php">
                                Inicio
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Herramientas
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENIDO -->
    <section class="content">
        <div class="container-fluid">
           <!-- BOTÓN NUEVA HERRAMIENTA -->
            <div class="row">
                <div class="col-12">
                    <a
                        href="crear.php"
                        class="btn btn-primary mb-3"
                    >
                        <i class="fas fa-plus"></i>
                        Nueva herramienta
                    </a>
                </div>
            </div>
            <!-- TABLA -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Lista de herramientas
                            </h3>
                       </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Disponible</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($herramientas as $herramienta): ?>
                                        <tr>
                                            <td>
                                                <?= $herramienta['id'] ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($herramienta['codigo']) ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($herramienta['nombre']) ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($herramienta['descripcion']) ?>
                                            </td>
                                            <td>
                                                <?= $herramienta['cantidad'] ?>
                                            </td>
                                            <td>
                                                <?= $herramienta['cantidad_disponible'] ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">
                                                    <?= htmlspecialchars($herramienta['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a
                                                    href="editar.php?id=<?= $herramienta['id'] ?>"
                                                    class="btn btn-warning btn-sm"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a
                                                    href="eliminar.php?id=<?= $herramienta['id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>