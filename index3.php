<?php if (tienePermiso('herramientas.eliminar')): ?>

    <a
        href="eliminar.php?id=<?= $herramienta['id'] ?>"
        class="btn btn-danger btn-sm"
        title="Eliminar"
    >
        <i class="fas fa-trash"></i>
    </a>

<?php endif; ?>