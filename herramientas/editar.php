<?php
require_once __DIR__ . '/../config/database.php';
//se agrega seguridad
require_once __DIR__ . '/../includes/auth.php';
requierePermiso('herramientas.editar');

$id = $_GET['id'];
$mensaje = '';

// ==================================================
// ACTUALIZAR HERRAMIENTA
// ==================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];

    $sql = "UPDATE herramientas
            SET
                codigo = :codigo,
                nombre = :nombre,
                descripcion = :descripcion,
                cantidad = :cantidad
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'codigo' => $codigo,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'cantidad' => $cantidad,
        'id' => $id
    ]);

    $mensaje = 'Herramienta actualizada correctamente.';
}


// ==================================================
// BUSCAR HERRAMIENTA
// ==================================================

$sql = "SELECT * FROM herramientas WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'id' => $id
]);

$herramienta = $stmt->fetch();

if (!$herramienta) {

    echo "Herramienta no encontrada.";
    exit;

}


// ==================================================
// ADMINLTE
// ==================================================

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
                        Editar herramienta
                    </h1>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item">

                            <a href="/prestamos2/index.php">
                                Inicio
                            </a>

                        </li>

                        <li class="breadcrumb-item">

                            <a href="index.php">
                                Herramientas
                            </a>

                        </li>

                        <li class="breadcrumb-item active">
                            Editar
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <!-- CONTENIDO -->

    <section class="content">

        <div class="container-fluid">

            <?php if ($mensaje): ?>

                <div class="alert alert-success">

                    <i class="fas fa-check"></i>

                    <?= htmlspecialchars($mensaje) ?>

                </div>

            <?php endif; ?>


            <div class="row">

                <div class="col-md-8">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title">

                                Información de la herramienta

                            </h3>

                        </div>


                        <form method="POST">

                            <div class="card-body">


                                <!-- CÓDIGO -->

                                <div class="form-group">

                                    <label for="codigo">
                                        Código
                                    </label>

                                    <input
                                        type="text"
                                        name="codigo"
                                        id="codigo"
                                        class="form-control"
                                        value="<?= htmlspecialchars($herramienta['codigo']) ?>"
                                        required
                                    >

                                </div>


                                <!-- NOMBRE -->

                                <div class="form-group">

                                    <label for="nombre">
                                        Nombre
                                    </label>

                                    <input
                                        type="text"
                                        name="nombre"
                                        id="nombre"
                                        class="form-control"
                                        value="<?= htmlspecialchars($herramienta['nombre']) ?>"
                                        required
                                    >

                                </div>


                                <!-- DESCRIPCIÓN -->

                                <div class="form-group">

                                    <label for="descripcion">
                                        Descripción
                                    </label>

                                    <textarea
                                        name="descripcion"
                                        id="descripcion"
                                        class="form-control"
                                        rows="3"
                                    ><?= htmlspecialchars($herramienta['descripcion']) ?></textarea>

                                </div>


                                <!-- CANTIDAD -->

                                <div class="form-group">

                                    <label for="cantidad">
                                        Cantidad
                                    </label>

                                    <input
                                        type="number"
                                        name="cantidad"
                                        id="cantidad"
                                        class="form-control"
                                        value="<?= $herramienta['cantidad'] ?>"
                                        min="1"
                                        required
                                    >

                                </div>


                                <!-- DISPONIBLE -->

                                <div class="form-group">

                                    <label>
                                        Cantidad disponible
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?= $herramienta['cantidad_disponible'] ?>"
                                        readonly
                                    >

                                    <small class="form-text text-muted">

                                        Este valor será controlado
                                        automáticamente por el sistema
                                        de préstamos.

                                    </small>

                                </div>


                            </div>


                            <!-- BOTONES -->

                            <div class="card-footer">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="fas fa-save"></i>

                                    Guardar cambios

                                </button>


                                <a
                                    href="index.php"
                                    class="btn btn-secondary"
                                >

                                    <i class="fas fa-arrow-left"></i>

                                    Cancelar

                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>