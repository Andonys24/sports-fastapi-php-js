<h1 class="nombre-pagina"><?php echo s($title ?? 'Update Category'); ?></h1>
<p class="descripcion-pagina">Actualiza los datos de la categoría</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton" value="Actualizar Categoría">
</form>