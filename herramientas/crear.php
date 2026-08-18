<?php

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];

    $sql = "INSERT INTO herramientas
            (codigo, nombre, descripcion, cantidad, cantidad_disponible, estado)
            VALUES
            (:codigo, :nombre, :descripcion, :cantidad, :cantidad_disponible, :estado)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'codigo' => $codigo,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'cantidad' => $cantidad,
        'cantidad_disponible' => $cantidad,
        'estado' => 'disponible'
    ]);

    echo "Herramienta registrada correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva herramienta</title>
</head>

<body>

<h1>Nueva herramienta</h1>

<form method="POST">

    <div>
        <label>Código:</label>
        <input
            type="text"
            name="codigo"
            required
        >
    </div>

    <br>

    <div>
        <label>Nombre:</label>
        <input
            type="text"
            name="nombre"
            required
        >
    </div>

    <br>

    <div>
        <label>Descripción:</label>
        <input
            type="text"
            name="descripcion"
        >
    </div>

    <br>

    <div>
        <label>Cantidad:</label>
        <input
            type="number"
            name="cantidad"
            min="1"
            required
        >
    </div>

    <br>

    <button type="submit">
        Guardar herramienta
    </button>

</form>

</body>

</html>