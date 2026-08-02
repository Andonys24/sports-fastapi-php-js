<h1 class="nombre-pagina"><?php echo s($title ?? 'Nuevo Proveedor'); ?></h1>
<p class="descripcion-pagina">Registra un nuevo proveedor.</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton-guardar" value="Guardar Proveedor">
</form>