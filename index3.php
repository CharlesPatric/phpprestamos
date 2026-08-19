<td>
    <a
        href="#"
        class="btn btn-info btn-sm"
        title="Ver préstamo"
    >
        <i class="fas fa-eye"></i>
    </a>
    <?php if ($prestamo['estado'] === 'prestado'): ?>
        <a
            href="devolver.php?id=<?= $prestamo['id'] ?>"
            class="btn btn-success btn-sm"
            title="Devolver herramienta"
            onclick="return confirm('¿Está seguro de registrar la devolución de esta herramienta?');"
        >
            <i class="fas fa-undo"></i>
        </a>
    <?php endif; ?>
</td>