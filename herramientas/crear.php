<?php

require_once __DIR__ . '/../config/database.php';
//se agrega seguridad
require_once __DIR__ . '/../includes/auth.php';

requierePermiso('herramientas.crear');

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];

    $sql = "INSERT INTO herramientas
            (
                codigo,
                nombre,
                descripcion,
                cantidad,
                cantidad_disponible,
                estado
            )
            VALUES
            (
                :codigo,
                :nombre,
                :descripcion,
                :cantidad,
                :cantidad_disponible,
                :estado
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'codigo' => $codigo,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'cantidad' => $cantidad,
        'cantidad_disponible' => $cantidad,
        'estado' => 'disponible'
    ]);

    $mensaje = 'Herramienta registrada correctamente.';
}


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
                        Nueva herramienta
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
                            Nueva
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


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
                                Registrar herramienta
                            </h3>

                        </div>


                        <form method="POST">

                            <div class="card-body">

                                <div class="form-group">

                                    <label for="codigo">
                                        Código
                                    </label>

                                    <input
                                        type="text"
                                        name="codigo"
                                        id="codigo"
                                        class="form-control"
                                        placeholder="Ej. H001"
                                        required
                                    >

                                </div>


                                <div class="form-group">

                                    <label for="nombre">
                                        Nombre
                                    </label>

                                    <input
                                        type="text"
                                        name="nombre"
                                        id="nombre"
                                        class="form-control"
                                        placeholder="Ej. Pala"
                                        required
                                    >

                                </div>


                                <div class="form-group">

                                    <label for="descripcion">
                                        Descripción
                                    </label>

                                    <textarea
                                        name="descripcion"
                                        id="descripcion"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Descripción de la herramienta"
                                    ></textarea>

                                </div>


                                <div class="form-group">

                                    <label for="cantidad">
                                        Cantidad
                                    </label>

                                    <input
                                        type="number"
                                        name="cantidad"
                                        id="cantidad"
                                        class="form-control"
                                        min="1"
                                        required
                                    >

                                </div>

                            </div>


                            <div class="card-footer">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="fas fa-save"></i>

                                    Guardar

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