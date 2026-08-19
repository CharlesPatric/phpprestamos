<?php
require_once __DIR__ . '/../config/database.php';
$mensaje = '';
$error = '';
// --------------------------------------------------
// CARGAR ESTUDIANTES
// --------------------------------------------------
$sql = "SELECT registro, nombre, correo
        FROM lector
        ORDER BY nombre";
$stmt = $pdo->query($sql);
$estudiantes = $stmt->fetchAll();
// --------------------------------------------------
// CARGAR HERRAMIENTAS DISPONIBLES
// --------------------------------------------------
$sql = "SELECT
            id,
            codigo,
            nombre,
            cantidad_disponible
        FROM herramientas
        WHERE cantidad_disponible > 0
        AND estado = 'disponible'
        ORDER BY nombre";
$stmt = $pdo->query($sql);
$herramientas = $stmt->fetchAll();
// --------------------------------------------------
// PROCESAR FORMULARIO
// --------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registro_estudiante = $_POST['registro_estudiante'];
    $herramienta_id = $_POST['herramienta_id'];
    $observaciones = $_POST['observaciones'];
    try {
        // ------------------------------------------
        // INICIAR TRANSACCIÓN
        // ------------------------------------------
        $pdo->beginTransaction();
        // ------------------------------------------
        // COMPROBAR HERRAMIENTA
        // ------------------------------------------
        $sql = "SELECT
                    id,
                    cantidad_disponible,
                    estado
                FROM herramientas
                WHERE id = :id
                FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $herramienta_id
        ]);
        $herramienta = $stmt->fetch();
        if (!$herramienta) {
            throw new Exception(
                'La herramienta no existe.'
            );
        }
        // ------------------------------------------
        // COMPROBAR DISPONIBILIDAD
        // ------------------------------------------
        if ($herramienta['estado'] !== 'disponible') {
            throw new Exception(
                'La herramienta no está disponible.'
            );
        }
        if ($herramienta['cantidad_disponible'] <= 0) {
            throw new Exception(
                'No hay unidades disponibles.'
            );
        }
        // ------------------------------------------
        // REGISTRAR PRÉSTAMO
        // ------------------------------------------
        $sql = "INSERT INTO prestamos
                (
                    registro_estudiante,
                    herramienta_id,
                    observaciones
                )
                VALUES
                (
                    :registro_estudiante,
                    :herramienta_id,
                    :observaciones
                )";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'registro_estudiante' => $registro_estudiante,
            'herramienta_id' => $herramienta_id,
            'observaciones' => $observaciones
        ]);
        // ------------------------------------------
        // ACTUALIZAR INVENTARIO
        // ------------------------------------------
        $sql = "UPDATE herramientas
                SET cantidad_disponible =
                    cantidad_disponible - 1

                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $herramienta_id
        ]);
        // ------------------------------------------
        // CONFIRMAR TRANSACCIÓN
        // ------------------------------------------
        $pdo->commit();
        $mensaje = 'Préstamo registrado correctamente.';
    } catch (Exception $e) {
        // ------------------------------------------
        // CANCELAR TRANSACCIÓN
        // ------------------------------------------
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = $e->getMessage();
    }
}
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
                        Nuevo préstamo
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
                                Préstamos
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Nuevo
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENIDO -->
    <section class="content">
        <div class="container-fluid">
            <!-- MENSAJE DE ÉXITO -->
            <?php if ($mensaje): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>
            <!-- MENSAJE DE ERROR -->
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Registrar préstamo
                            </h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <!-- ESTUDIANTE -->
                                <div class="form-group">
                                    <label for="registro_estudiante">
                                        Estudiante
                                    </label>
                                    <select
                                        name="registro_estudiante"
                                        id="registro_estudiante"
                                        class="form-control"
                                        required
                                    >
                                        <option value="">
                                            Seleccione un estudiante
                                        </option>
                                        <?php foreach ($estudiantes as $estudiante): ?>
                                            <option
                                                value="<?= $estudiante['registro'] ?>"
                                            >
                                                <?= htmlspecialchars($estudiante['registro']) ?>
                                                -
                                                <?= htmlspecialchars($estudiante['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- HERRAMIENTA -->
                                <div class="form-group">
                                    <label for="herramienta_id">
                                        Herramienta
                                    </label>
                                    <select
                                        name="herramienta_id"
                                        id="herramienta_id"
                                        class="form-control"
                                        required
                                    >
                                        <option value="">
                                            Seleccione una herramienta
                                        </option>
                                        <?php foreach ($herramientas as $herramienta): ?>
                                            <option
                                                value="<?= $herramienta['id'] ?>"
                                            >
                                                <?= htmlspecialchars($herramienta['codigo']) ?>
                                                -
                                                <?= htmlspecialchars($herramienta['nombre']) ?>
                                                (Disponible:
                                                <?= $herramienta['cantidad_disponible'] ?>
                                                )
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- OBSERVACIONES -->
                                <div class="form-group">
                                    <label for="observaciones">
                                        Observaciones
                                    </label>
                                    <textarea
                                        name="observaciones"
                                        id="observaciones"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Observaciones del préstamo"
                                    ></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="fas fa-hand-holding"></i>
                                    Registrar préstamo
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