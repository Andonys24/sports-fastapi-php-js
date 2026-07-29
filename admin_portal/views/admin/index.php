<h1 class="nombre-pagina"><?php echo s($title ?? 'Dashboard'); ?></h1>
<p class="descripcion-pagina">Para gestionar y administrar los encargos programados.</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<h2>Buscar Encargos</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha-admin" value="<?php echo s($date ?? ''); ?>">
        </div>
    </form>
</div>

<?php if (empty($orders)) : ?>
    <h2 class="no-encargos">No hay encargos en esta Fecha</h2>
<?php else : ?>
    <div class="encargo-admin">
        <ul class="encargos" id="encargos">
            <?php
            $current_order_id = 0;
            foreach ($orders as $key => $order) :
                if ($current_order_id !== $order['id']) :
                    $total = 0;
            ?>
                    <li data-reporte="<?php echo s($order['id']); ?>">
                        <p>ID: <span><?php echo s($order['id']); ?></span></p>
                        <p>Hora Entrega: <span><?php echo s($order['time'] ?? ''); ?></span></p>
                        <p>Cliente: <span><?php echo s($order['client'] ?? ''); ?></span></p>
                        <p>Email: <span><?php echo s($order['email'] ?? ''); ?></span></p>
                        <p>Lugar: <span><?php echo s($order['place'] ?? ''); ?></span></p>
                        <h3>Productos</h3>
                    <?php
                    $current_order_id = $order['id'];
                endif;

                $item_total = ($order['price'] ?? 0) * ($order['quantity'] ?? 1);
                $total += $item_total;
                    ?>
                    <p class="producto">Nombre:
                        <span><?php echo s($order['product'] ?? ''); ?></span>
                    </p>
                    <p class="producto">Precio: L.
                        <span><?php echo s($order['price'] ?? 0) . ' X ' . s($order['quantity'] ?? 1); ?></span>
                    </p>
                    <p class="producto">Total Producto: L.
                        <span><?php echo s($item_total); ?></span>
                    </p>

                    <?php
                    $next_order = $orders[$key + 1] ?? null;
                    $is_last = !$next_order || $next_order['id'] !== $order['id'];
                    if ($is_last) : ?>
                        <p class="total">Total a Cobrar: <span>L. <?php echo s($total); ?></span></p>

                        <div id="reporte-acciones" class="reporte--acciones">
                            <input type="submit" data-id="<?php echo s($order['id']); ?>" value="Eliminar encargo" class="boton-eliminar encargo">
                            <button class="boton-imprimir" id="imprimir">Imprimir</button>
                        </div>
                    <?php endif; ?>
                    </li>
                <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>