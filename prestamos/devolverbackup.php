<?php
require_once __DIR__ . '/../config/database.php';
$id = $_GET['id'];
$mensaje = '';
$error = '';

// --------------------------------------------------
// BUSCAR PRÉSTAMO
// --------------------------------------------------
$sql = "SELECT
            p.id,
            p.registro_estudiante,
            l.nombre AS estudiante,
            p.herramienta_id,
            h.codigo AS codigo_herramienta,
            h.nombre AS herramienta,
            p.fecha_prestamo,
            p.estado,
            p.observaciones
        FROM prestamos p
        INNER JOIN lector l
            ON p.registro_estudiante = l.registro
        INNER JOIN herramientas h
            ON p.herramienta_id = h.id
        WHERE p.id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'id' => $id
]);

$prestamo = $stmt->fetch();


if (!$prestamo) {

    echo "Préstamo no encontrado.";

    exit;

}


// --------------------------------------------------
// PROCESAR DEVOLUCIÓN
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $pdo->beginTransaction();


        // ------------------------------------------
        // BLOQUEAR PRÉSTAMO
        // ------------------------------------------

        $sql = "SELECT
                    id,
                    herramienta_id,
                    estado

                FROM prestamos

                WHERE id = :id

                FOR UPDATE";


        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $prestamo_actual = $stmt->fetch();


        if (!$prestamo_actual) {

            throw new Exception(
                'El préstamo no existe.'
            );

        }


        // ------------------------------------------
        // COMPROBAR ESTADO
        // ------------------------------------------

        if ($prestamo_actual['estado'] !== 'prestado') {

            throw new Exception(
                'Este préstamo ya fue devuelto.'
            );

        }


        // ------------------------------------------
        // ACTUALIZAR PRÉSTAMO
        // ------------------------------------------

        $sql = "UPDATE prestamos

                SET
                    estado = 'devuelto',
                    fecha_devolucion = NOW()

                WHERE id = :id";


        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);


        // ------------------------------------------
        // ACTUALIZAR INVENTARIO
        // ------------------------------------------

        $sql = "UPDATE herramientas

                SET cantidad_disponible =
                    cantidad_disponible + 1

                WHERE id = :id
                AND cantidad_disponible < cantidad";


        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'id' => $prestamo_actual['herramienta_id']
        ]);


        // ------------------------------------------
        // CONFIRMAR
        // ------------------------------------------

        $pdo->commit();


        header('Location: index.php');

        exit;


    } catch (Exception $e) {


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

    <div class="content-header">

        <div class="container-fluid">

            <h1 class="m-0">
                Registrar devolución
            </h1>

        </div>

    </div>


    <section class="content">

        <div class="container-fluid">


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

                                Confirmar devolución

                            </h3>

                        </div>


                        <div class="card-body">


                            <p>

                                <strong>
                                    Estudiante:
                                </strong>

                                <?= htmlspecialchars($prestamo['registro_estudiante']) ?>

                                -

                                <?= htmlspecialchars($prestamo['estudiante']) ?>

                            </p>


                            <p>

                                <strong>
                                    Herramienta:
                                </strong>

                                <?= htmlspecialchars($prestamo['codigo_herramienta']) ?>

                                -

                                <?= htmlspecialchars($prestamo['herramienta']) ?>

                            </p>


                            <p>

                                <strong>
                                    Fecha del préstamo:
                                </strong>

                                <?= htmlspecialchars($prestamo['fecha_prestamo']) ?>

                            </p>


                            <?php if ($prestamo['observaciones']): ?>

                                <p>

                                    <strong>
                                        Observaciones:
                                    </strong>

                                    <?= htmlspecialchars($prestamo['observaciones']) ?>

                                </p>

                            <?php endif; ?>


                            <div class="alert alert-warning">

                                <i class="fas fa-exclamation-circle"></i>

                                ¿Confirma que la herramienta fue devuelta?

                            </div>


                        </div>


                        <div class="card-footer">

                            <form method="POST">

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                >

                                    <i class="fas fa-check"></i>

                                    Confirmar devolución

                                </button>


                                <a
                                    href="index.php"
                                    class="btn btn-secondary"
                                >

                                    Cancelar

                                </a>

                            </form>

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