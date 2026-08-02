<h1 class="nombre-pagina"><?php echo s($title ?? 'Actualizar Compra'); ?></h1>
<p class="descripcion-pagina">Modifica los valores de la compra seleccionada</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton-actualizar" value="Actualizar Compra">
</form>