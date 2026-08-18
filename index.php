<?php

require_once __DIR__ . '/config/database.php';

$registro = 951020323;

$sql = "SELECT * FROM lector WHERE registro = :registro";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'registro' => $registro
]);

$estudiante = $stmt->fetch();

echo "Registro: " . $estudiante['registro'] . "<br>";
echo "Nombre: " . $estudiante['nombre'] . "<br>";
echo "Correo: " . $estudiante['correo'] . "<br>";

