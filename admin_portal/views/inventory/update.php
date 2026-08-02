<h1 class="nombre-pagina"><?php echo s($title ?? 'Ajustar Inventario'); ?></h1>
<p class="descripcion-pagina">Modifica las existencias físicas en caso de auditoría o pérdidas</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton-actualizar" value="Guardar Ajuste">
</form>