<?php

require_once __DIR__ . '/../config/database.php';

$sql = "
    SELECT
        p.id,
        p.registro_estudiante,
        l.nombre AS estudiante,
        h.codigo AS codigo_herramienta,
        h.nombre AS herramienta,
        p.fecha_prestamo,
        p.fecha_devolucion,
        p.estado,
        p.observaciones

    FROM prestamos p

    INNER JOIN lector l
        ON p.registro_estudiante = l.registro

    INNER JOIN herramientas h
        ON p.herramienta_id = h.id

    ORDER BY p.id DESC
";

$stmt = $pdo->query($sql);

$prestamos = $stmt->fetchAll();


require_once __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../includes/navbar.php';

require_once __DIR__ . '/../includes/sidebar.php';

?>

<div class="content-wrapper">

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0">
                        Préstamos
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
                            Préstamos
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <section class="content">

        <div class="container-fluid">


            <!-- BOTÓN NUEVO -->

            <div class="row">

                <div class="col-12">

                    <a
                        href="crear.php"
                        class="btn btn-primary mb-3"
                    >

                        <i class="fas fa-plus"></i>

                        Nuevo préstamo

                    </a>

                </div>

            </div>


            <!-- TABLA -->

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title">
                                Préstamos registrados
                            </h3>

                        </div>


                        <div class="card-body">

                            <table class="table table-bordered table-striped">

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>Estudiante</th>

                                        <th>Herramienta</th>

                                        <th>Fecha préstamo</th>

                                        <th>Fecha devolución</th>

                                        <th>Estado</th>

                                        <th>Acciones</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php foreach ($prestamos as $prestamo): ?>

                                        <tr>

                                            <td>
                                                <?= $prestamo['id'] ?>
                                            </td>


                                            <td>

                                                <strong>
                                                    <?= htmlspecialchars($prestamo['estudiante']) ?>
                                                </strong>

                                                <br>

                                                <small>
                                                    Registro:
                                                    <?= $prestamo['registro_estudiante'] ?>
                                                </small>

                                            </td>


                                            <td>

                                                <strong>
                                                    <?= htmlspecialchars($prestamo['codigo_herramienta']) ?>
                                                </strong>

                                                <br>

                                                <small>
                                                    <?= htmlspecialchars($prestamo['herramienta']) ?>
                                                </small>

                                            </td>


                                            <td>

                                                <?= htmlspecialchars($prestamo['fecha_prestamo']) ?>

                                            </td>


                                            <td>

                                                <?php if ($prestamo['fecha_devolucion']): ?>

                                                    <?= htmlspecialchars($prestamo['fecha_devolucion']) ?>

                                                <?php else: ?>

                                                    <span class="text-muted">
                                                        Pendiente
                                                    </span>

                                                <?php endif; ?>

                                            </td>


                                            <td>

                                                <?php

                                                if ($prestamo['estado'] === 'prestado') {

                                                    $clase = 'badge-warning';

                                                } elseif ($prestamo['estado'] === 'devuelto') {

                                                    $clase = 'badge-success';

                                                } else {

                                                    $clase = 'badge-danger';

                                                }

                                                ?>

                                                <span class="badge <?= $clase ?>">

                                                    <?= htmlspecialchars($prestamo['estado']) ?>

                                                </span>

                                            </td>


                                            <td>

                                                <a
                                                    href="#"
                                                    class="btn btn-info btn-sm"
                                                    title="Ver préstamo"
                                                >

                                                    <i class="fas fa-eye"></i>

                                                </a>


                                                <?php if ($prestamo['estado'] === 'prestado'): ?>

                                                    <a
                                                        href="devolver.php?id=<?= $prestamo['id'] ?>"
                                                        class="btn btn-success btn-sm"
                                                        title="Devolver herramienta"
                                                        onclick="return confirm('¿Está seguro de registrar la devolución de esta herramienta?');"
                                                    >

                                                        <i class="fas fa-undo"></i>

                                                    </a>

                                                <?php endif; ?>

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