<?php
require_once __DIR__ . '/../config/database.php';

// --------------------------------------------------
// CONSULTAR PRÉSTAMOS
// --------------------------------------------------


////-*
// --------------------------------------------------
// FILTROS
// --------------------------------------------------

$buscar = $_GET['buscar'] ?? '';
$estado = $_GET['estado'] ?? '';


// --------------------------------------------------
// CONSULTA
// --------------------------------------------------

$sql = "SELECT
            p.id,
            p.registro_estudiante,
            l.nombre AS estudiante,
            p.herramienta_id,
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

        WHERE 1=1";


// --------------------------------------------------
// FILTRO DE BÚSQUEDA
// --------------------------------------------------

if ($buscar !== '') {

    $sql .= " AND (
                l.nombre LIKE :buscar
                OR l.registro LIKE :buscar
                OR h.codigo LIKE :buscar
                OR h.nombre LIKE :buscar
              )";
}


// --------------------------------------------------
// FILTRO DE ESTADO
// --------------------------------------------------

if ($estado !== '') {

    $sql .= " AND p.estado = :estado";
}


$sql .= " ORDER BY p.id DESC";


$stmt = $pdo->prepare($sql);


// --------------------------------------------------
// PARÁMETROS
// --------------------------------------------------

$params = [];


if ($buscar !== '') {

    $params['buscar'] = '%' . $buscar . '%';

}


if ($estado !== '') {

    $params['estado'] = $estado;

}


$stmt->execute($params);

$prestamos = $stmt->fetchAll();
///--*

// --------------------------------------------------
// ADMINLTE
// --------------------------------------------------

require_once __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../includes/navbar.php';

require_once __DIR__ . '/../includes/sidebar.php';

?>

<div class="content-wrapper">

    <!-- ENCABEZADO -->

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


    <!-- CONTENIDO -->

    <section class="content">

        <div class="container-fluid">
            <!-- BOTÓN -->
            <div class="row">
                <div class="col-12">
                    <a
                        href="crear.php"
                        class="btn btn-primary mb-3"
                    >
                        <i class="fas fa-plus"></i>
                        Nuevo préstamo
                    </a>
                    <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">

                            <i class="fas fa-search"></i>

                            Buscar préstamos

                        </h3>

                    </div>


                    <div class="card-body">

                        <form method="GET">

                            <div class="row">


                                <!-- BUSCAR -->

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label for="buscar">

                                            Estudiante o herramienta

                                        </label>

                                        <input
                                            type="text"
                                            name="buscar"
                                            id="buscar"
                                            class="form-control"
                                            value="<?= htmlspecialchars($buscar) ?>"
                                            placeholder="Registro, nombre, código..."
                                        >

                                    </div>

                                </div>


                                <!-- ESTADO -->

                                <div class="col-md-4">

                                    <div class="form-group">

                                        <label for="estado">

                                            Estado

                                        </label>

                                        <select
                                            name="estado"
                                            id="estado"
                                            class="form-control"
                                        >

                                            <option value="">
                                                Todos
                                            </option>

                                            <option
                                                value="prestado"
                                                <?= $estado === 'prestado' ? 'selected' : '' ?>
                                            >
                                                Prestados
                                            </option>

                                            <option
                                                value="devuelto"
                                                <?= $estado === 'devuelto' ? 'selected' : '' ?>
                                            >
                                                Devueltos
                                            </option>

                                            <option
                                                value="atrasado"
                                                <?= $estado === 'atrasado' ? 'selected' : '' ?>
                                            >
                                                Atrasados
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                <!-- BOTONES -->

                                <div class="col-md-2">

                                    <label>
                                        &nbsp;
                                    </label>

                                    <div>

                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >

                                            <i class="fas fa-search"></i>

                                            Buscar

                                        </button>


                                        <a
                                            href="index.php"
                                            class="btn btn-secondary"
                                        >

                                            <i class="fas fa-sync"></i>

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>
                </div>
            </div>
            <!-- TABLA -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Lista de préstamos
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
                                                    <?= htmlspecialchars($prestamo['registro_estudiante']) ?>
                                                </strong>
                                                <br>
                                                <?= htmlspecialchars($prestamo['estudiante']) ?>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?= htmlspecialchars($prestamo['codigo_herramienta']) ?>
                                                </strong>
                                                <br>
                                                <?= htmlspecialchars($prestamo['herramienta']) ?>
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
                                                <?php if ($prestamo['estado'] === 'prestado'): ?>
                                                    <span class="badge badge-warning">
                                                        Prestado
                                                    </span>
                                                <?php elseif ($prestamo['estado'] === 'devuelto'): ?>
                                                    <span class="badge badge-success">
                                                        Devuelto
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">
                                                        Atrasado
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($prestamo['estado'] === 'prestado'): ?>

                                                    <a
                                                        href="devolver.php?id=<?= $prestamo['id'] ?>"
                                                        class="btn btn-success btn-sm"
                                                        title="Registrar devolución"
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