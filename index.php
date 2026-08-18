<?php

require_once __DIR__ . '/config/database.php';

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM lector");

$resultado = $stmt->fetch();

echo "Estudiantes registrados: " . $resultado['total'] . "<BR>";

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM herramientas");

$resultado = $stmt->fetch();

echo "Herramientas registrados: " . $resultado['total'] . "<BR>";

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM prestamos");

$resultado = $stmt->fetch();

echo "Prestamos registrados: " . $resultado['total'] . "<BR>";

