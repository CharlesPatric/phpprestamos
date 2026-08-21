<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requiereLogin();


// ==================================================
// ESTUDIANTES
// ==================================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM lector
");

$total_estudiantes = $stmt->fetch()['total'];


// ==================================================
// HERRAMIENTAS
// ==================================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM herramientas
");

$total_herramientas = $stmt->fetch()['total'];


// ==================================================
// PRÉSTAMOS
// ==================================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM prestamos
");

$total_prestamos = $stmt->fetch()['total'];


// ==================================================
// PRÉSTAMOS ACTIVOS
// ==================================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM prestamos
    WHERE estado = 'prestado'
");

$total_prestados = $stmt->fetch()['total'];


// ==================================================
// DEVUELTOS
// ==================================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM prestamos
    WHERE estado = 'devuelto'
");

$total_devueltos = $stmt->fetch()['total'];


// ==================================================
// HERRAMIENTAS DISPONIBLES
// ==================================================

$stmt = $pdo->query("
    SELECT COALESCE(SUM(cantidad_disponible), 0) AS total
    FROM herramientas
");

$total_disponibles = $stmt->fetch()['total'];


// ==================================================
// ÚLTIMOS PRÉSTAMOS
// ==================================================

$sql = "
SELECT 
    p.id, 
    p.registro_estudiante, 
    l.nombre AS estudiante, 
    h.codigo AS codigo_herramienta, 
    h.nombre AS herramienta, 
    p.fecha_prestamo, 
    p.estado 
FROM prestamos p 
INNER JOIN lector l 
    ON p.registro_estudiante = l.registro 
INNER JOIN prestamo_detalle pe 
    on p.id=pe.prestamo_id 
INNER JOIN herramientas h 
    ON pe.herramienta_id = h.id 
ORDER BY p.id 
DESC LIMIT 5;
";

$stmt = $pdo->query($sql);

$ultimos_prestamos = $stmt->fetchAll();


// ==================================================
// ADMINLTE
// ==================================================

require_once __DIR__ . '/includes/header.php';

require_once __DIR__ . '/includes/navbar.php';

require_once __DIR__ . '/includes/sidebar.php';

?>

<div class="content-wrapper">


    <!-- ========================================= -->
    <!-- ENCABEZADO -->
    <!-- ========================================= -->

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0">
                        Dashboard
                    </h1>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================= -->
    <!-- CONTENIDO -->
    <!-- ========================================= -->

    <section class="content">

        <div class="container-fluid">


            <!-- ================================= -->
            <!-- INDICADORES -->
            <!-- ================================= -->

            <div class="row">


                <!-- ESTUDIANTES -->

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">

                        <div class="inner">

                            <h3>
                                <?= $total_estudiantes ?>
                            </h3>

                            <p>
                                Estudiantes
                            </p>

                        </div>

                        <div class="icon">

                            <i class="fas fa-user-graduate"></i>

                        </div>

                        <a
                            href="/prestamos2/estudiantes/"
                            class="small-box-footer"
                        >

                            Ver estudiantes

                            <i class="fas fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>


                <!-- HERRAMIENTAS -->

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-success">

                        <div class="inner">

                            <h3>
                                <?= $total_herramientas ?>
                            </h3>

                            <p>
                                Tipos de herramientas
                            </p>

                        </div>

                        <div class="icon">

                            <i class="fas fa-tools"></i>

                        </div>

                        <a
                            href="/prestamos2/herramientas/"
                            class="small-box-footer"
                        >

                            Ver herramientas

                            <i class="fas fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>


                <!-- PRÉSTAMOS -->

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning">

                        <div class="inner">

                            <h3>
                                <?= $total_prestamos ?>
                            </h3>

                            <p>
                                Total de préstamos
                            </p>

                        </div>

                        <div class="icon">

                            <i class="fas fa-hand-holding"></i>

                        </div>

                        <a
                            href="/prestamos2/prestamos/"
                            class="small-box-footer"
                        >

                            Ver préstamos

                            <i class="fas fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>


                <!-- PRESTADOS -->

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-danger">

                        <div class="inner">

                            <h3>
                                <?= $total_prestados ?>
                            </h3>

                            <p>
                                Préstamos activos
                            </p>

                        </div>

                        <div class="icon">

                            <i class="fas fa-exchange-alt"></i>

                        </div>

                        <a
                            href="/prestamos2/prestamos/?estado=prestado"
                            class="small-box-footer"
                        >

                            Ver activos

                            <i class="fas fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>

            </div>


            <!-- ================================= -->
            <!-- SEGUNDA FILA -->
            <!-- ================================= -->

            <div class="row">


                <!-- DISPONIBLES -->

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-primary">

                        <div class="inner">

                            <h3>
                                <?= $total_disponibles ?>
                            </h3>

                            <p>
                                Unidades disponibles
                            </p>

                        </div>

                        <div class="icon">

                            <i class="fas fa-boxes"></i>

                        </div>

                        <a
                            href="/prestamos2/herramientas/"
                            class="small-box-footer"
                        >

                            Ver inventario

                            <i class="fas fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>


                <!-- DEVUELTOS -->

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-secondary">

                        <div class="inner">

                            <h3>
                                <?= $total_devueltos ?>
                            </h3>

                            <p>
                                Préstamos devueltos
                            </p>

                        </div>

                        <div class="icon">

                            <i class="fas fa-check-circle"></i>

                        </div>

                        <a
                            href="/prestamos2/prestamos/?estado=devuelto"
                            class="small-box-footer"
                        >

                            Ver devueltos

                            <i class="fas fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>

            </div>


            <!-- ================================= -->
            <!-- ÚLTIMOS PRÉSTAMOS -->
            <!-- ================================= -->

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="fas fa-history"></i>

                                Últimos préstamos

                            </h3>

                            <div class="card-tools">

                                <a
                                    href="/prestamos2/prestamos/"
                                    class="btn btn-primary btn-sm"
                                >

                                    Ver todos

                                </a>

                            </div>

                        </div>


                        <div class="card-body p-0">

                            <table class="table table-striped">

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>Estudiante</th>

                                        <th>Herramienta</th>

                                        <th>Fecha</th>

                                        <th>Estado</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (count($ultimos_prestamos) > 0): ?>

                                        <?php foreach ($ultimos_prestamos as $prestamo): ?>

                                            <tr>

                                                <td>
                                                    <?= $prestamo['id'] ?>
                                                </td>

                                                <td>

                                                    <?= htmlspecialchars(
                                                        $prestamo['registro_estudiante']
                                                    ) ?>

                                                    -

                                                    <?= htmlspecialchars(
                                                        $prestamo['estudiante']
                                                    ) ?>

                                                </td>

                                                <td>

                                                    <?= htmlspecialchars(
                                                        $prestamo['codigo_herramienta']
                                                    ) ?>

                                                    -

                                                    <?= htmlspecialchars(
                                                        $prestamo['herramienta']
                                                    ) ?>

                                                </td>

                                                <td>

                                                    <?= htmlspecialchars(
                                                        $prestamo['fecha_prestamo']
                                                    ) ?>

                                                </td>

                                                <td>

                                                    <?php if (
                                                        $prestamo['estado'] === 'prestado'
                                                    ): ?>

                                                        <span class="badge badge-warning">
                                                            Prestado
                                                        </span>

                                                    <?php elseif (
                                                        $prestamo['estado'] === 'devuelto'
                                                    ): ?>

                                                        <span class="badge badge-success">
                                                            Devuelto
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge badge-danger">
                                                            Atrasado
                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>

                                            <td
                                                colspan="5"
                                                class="text-center"
                                            >

                                                No existen préstamos registrados.

                                            </td>

                                        </tr>

                                    <?php endif; ?>

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

require_once __DIR__ . '/includes/footer.php';

?>