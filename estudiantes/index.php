<?php

require_once __DIR__ . '/../config/database.php';

$estudiante = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $registro = $_POST['registro'];

    $sql = "SELECT * FROM lector WHERE registro = :registro";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'registro' => $registro
    ]);

    $estudiante = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Buscar estudiante</title>
</head>

<body>

<h1>Buscar estudiante</h1>

<form method="POST">

    <label>Registro:</label>

    <input
        type="number"
        name="registro"
        required
    >

    <button type="submit">
        Buscar
    </button>

</form>

<?php if ($estudiante): ?>

    <hr>

    <h2>Estudiante encontrado</h2>

    <p>
        <strong>Registro:</strong>
        <?= htmlspecialchars($estudiante['registro']) ?>
    </p>

    <p>
        <strong>Nombre:</strong>
        <?= htmlspecialchars($estudiante['nombre']) ?>
    </p>

    <p>
        <strong>Correo:</strong>
        <?= htmlspecialchars($estudiante['correo']) ?>
    </p>

<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

    <p>Estudiante no encontrado.</p>

<?php endif; ?>

</body>

</html>