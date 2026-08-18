<?php

require_once __DIR__ . '/../config/database.php';

$sql = "SELECT * FROM herramientas ORDER BY id DESC";

$stmt = $pdo->query($sql);

$herramientas = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Herramientas</title>
</head>

<body>

<h1>Herramientas</h1>

<a href="crear.php">
    Nueva herramienta
</a>

<br><br>

<table border="1">

    <thead>

        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Disponible</th>
            <th>Estado</th>
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
                    <?= htmlspecialchars($herramienta['estado']) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

</body>

</html>