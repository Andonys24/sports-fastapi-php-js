<h1 class="nombre-pagina"><?php echo s($title ?? 'Panel de Encargos'); ?></h1>
<p class="descripcion-pagina">Para gestionar y consultar los encargos programados</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<h2>Buscar Encargos</h2>
<div class="busqueda">
    <form action="" method="GET" class="formulario">
        <div class="campo">
            <label for="fecha-admin">Fecha:</label>
            <input
                type="date"
                name="fecha"
                id="fecha-admin"
                value="<?php echo s($fecha ?? ''); ?>">
        </div>
    </form>
</div>

<?php if (empty($encargos)) : ?>
    <h2 class="no-encargos">No hay encargos registrados para la fecha <?php echo s($fecha ?? ""); ?></h2>
<?php else : ?>
    <div class="encargo-admin">
        <ul class="encargos" id="encargos">
            <?php foreach ($encargos as $encargo) : ?>
                <?php if (!is_array($encargo)) continue; ?>

                <li data-reporte="<?php echo s($encargo['id'] ?? ''); ?>">
                    <p>ID: <span>#<?php echo s($encargo['id'] ?? 'N/A'); ?></span></p>
                    <p>Hora Entrega: <span><?php echo s(substr($encargo['time_order'] ?? 'N/A', 0, 5)); ?></span></p>
                    <p>Cliente: <span><?php echo s($encargo['client_name'] ?? 'N/A'); ?></span></p>
                    <p>Email: <span><?php echo s($encargo['client_email'] ?? 'N/A'); ?></span></p>
                    <p>Lugar: <span><?php echo s($encargo['address'] ?? 'N/A'); ?></span></p>
                    <p class="total">Total a Cobrar: <span>L. <?php echo s(number_format((float)($encargo['total'] ?? 0), 2)); ?></span></p>

                    <div class="reporte-acciones">
                        <button type="button" class="boton-imprimir boton-guardar">Imprimir</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>