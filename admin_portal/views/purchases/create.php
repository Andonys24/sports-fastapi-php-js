<h1 class="nombre-pagina"><?php echo s($title ?? 'Registrar Compra'); ?></h1>
<p class="descripcion-pagina">Prepara la estructura para registrar compras de inventario.</p>

<?php
include_once __DIR__ . '/../templates/bar.php';
include_once __DIR__ . '/../templates/alerts.php';
?>

<form method="post" class="formulario">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input type="submit" class="boton" value="Registrar Compra">
</form>
