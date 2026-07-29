<h1 class="nombre-pagina"><?php echo s($title ?? 'Create Product'); ?></h1>
<p class="descripcion-pagina">Llena los campos para agregar la nueva Categoría</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form action="/categories/create" method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton" value="Guardar Categoría">
</form>