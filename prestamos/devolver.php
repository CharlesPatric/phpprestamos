<?php

require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die('Préstamo no especificado.');
}


try {

    // ==================================================
    // INICIAR TRANSACCIÓN
    // ==================================================

    $pdo->beginTransaction();


    // ==================================================
    // BUSCAR EL PRÉSTAMO
    // ==================================================

    $sql = "
        SELECT
            id,
            herramienta_id,
            estado
        FROM prestamos
        WHERE id = :id
        FOR UPDATE
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);

    $prestamo = $stmt->fetch();


    if (!$prestamo) {

        throw new Exception(
            'El préstamo no existe.'
        );

    }


    // ==================================================
    // COMPROBAR ESTADO
    // ==================================================

    if ($prestamo['estado'] !== 'prestado') {

        throw new Exception(
            'Este préstamo ya fue devuelto.'
        );

    }


    // ==================================================
    // ACTUALIZAR PRÉSTAMO
    // ==================================================

    $sql = "
        UPDATE prestamos

        SET
            estado = 'devuelto',
            fecha_devolucion = NOW()

        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);


    // ==================================================
    // DEVOLVER DISPONIBILIDAD
    // ==================================================

    $sql = "
        UPDATE herramientas

        SET cantidad_disponible =
            cantidad_disponible + 1

        WHERE id = :herramienta_id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'herramienta_id' => $prestamo['herramienta_id']
    ]);


    // ==================================================
    // CONFIRMAR
    // ==================================================

    $pdo->commit();


    header('Location: index.php?mensaje=devuelto');

    exit;


} catch (Exception $e) {


    // ==================================================
    // DESHACER SI HAY ERROR
    // ==================================================

    if ($pdo->inTransaction()) {

        $pdo->rollBack();

    }


    die(
        'Error al devolver el préstamo: '
        . htmlspecialchars($e->getMessage())
    );

}