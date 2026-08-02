<h1 class="nombre-pagina"><?php echo s($title ?? 'Nueva Compra'); ?></h1>
<p class="descripcion-pagina">Llena los campos para registrar una nueva compra de inventario</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form action="/purchases/create" method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton-guardar" value="Guardar Compra">
</form>