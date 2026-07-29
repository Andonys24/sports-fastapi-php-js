<h1 class="nombre-pagina"><?php echo s($title ?? 'Update product'); ?></h1>
<p class="descripcion-pagina">Modifica los valores para actualizar</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton" value="Actualizar Producto">
</form>