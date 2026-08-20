<?php
//se agrega seguridad
require_once __DIR__ . '/../includes/auth.php';
requierePermiso('herramientas.eliminar');
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'];
// --------------------------------------------------
// ELIMINAR HERRAMIENTA
// --------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $sql = "DELETE FROM herramientas WHERE id = :id";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
       'id' => $id
   ]);
   header('Location: index.php');
   exit;
}
// --------------------------------------------------
// BUSCAR HERRAMIENTA
// --------------------------------------------------
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>Eliminar herramienta</title>
</head>
<body>
<h1>Eliminar herramienta</h1>
<p>
   ¿Está seguro de eliminar esta herramienta?
</p>
<p>
   <strong>Código:</strong>
   <?= htmlspecialchars($herramienta['codigo']) ?>
</p>
<p>
   <strong>Nombre:</strong>
   <?= htmlspecialchars($herramienta['nombre']) ?>
</p>
<form method="POST">
   <button type="submit">
       Sí, eliminar
   </button>
   <a href="index.php">
       Cancelar
   </a>
</form>
</body>
</html>
