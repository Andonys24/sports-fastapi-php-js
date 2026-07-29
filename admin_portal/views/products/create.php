<h1 class="nombre-pagina"><?php echo s($title ?? 'Create Product'); ?></h1>
<p class="descripcion-pagina">Llena los campos para agregar un nuevo Producto</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form action="/products/create" method="post" class="formulario" enctype="multipart/form-data">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton" value="Guardar Producto">
</form>