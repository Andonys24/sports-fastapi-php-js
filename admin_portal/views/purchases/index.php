<h1 class="nombre-pagina"><?php echo s($title ?? 'Panel de Compras'); ?></h1>
<p class="descripcion-pagina">Consulta y gestiona las compras y encargos por fecha</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<h2>Buscar por Fecha</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha-admin">Fecha:</label>
            <input
                type="date"
                name="fecha"
                id="fecha-admin"
                value="<?php echo s($fecha ?? ""); ?>">
        </div>
    </form>
</div>

<?php if (empty($purchases)) : ?>
    <h2 class="no-encargos">No hay compras registradas en esta fecha</h2>
<?php else : ?>
    <div class="encargo-admin">
        <ul class="encargos" id="encargos">
            <?php
            $currentPurchaseId = 0;
            foreach ($purchases as $key => $purchase) :
                if (!is_array($purchase)) continue;

                // Agrupamiento por ID de Compra/Encargo
                if ($currentPurchaseId !== $purchase['id']) :
                    $total = 0;
            ?>
                    <li data-reporte="<?php echo s($purchase['id']); ?>">
                        <p>ID: <span><?php echo s($purchase['id']); ?></span></p>
                        <p>Hora Entrega: <span><?php echo s($purchase['hora'] ?? $purchase['time'] ?? 'N/A'); ?></span></p>
                        <p>Cliente / Proveedor: <span><?php echo s($purchase['cliente'] ?? $purchase['supplier_name'] ?? 'N/A'); ?></span></p>
                        <p>Email: <span><?php echo s($purchase['email'] ?? 'N/A'); ?></span></p>
                        <p>Lugar: <span><?php echo s($purchase['lugar'] ?? $purchase['location'] ?? 'N/A'); ?></span></p>
                        <h3>Productos</h3>
                    <?php
                    $currentPurchaseId = $purchase['id'];
                endif;

                $itemTotal = (float)($purchase['total'] ?? (($purchase['precio'] ?? 0) * ($purchase['cantidad'] ?? 1)));
                $total += $itemTotal;
                    ?>
                    <p class="producto">Nombre:
                        <span><?php echo s($purchase['producto'] ?? $purchase['product_name'] ?? ''); ?></span>
                    </p>
                    <p class="producto">Precio: L.
                        <span><?php echo s(number_format((float)($purchase['precio'] ?? 0), 2)) . ' X ' . s($purchase['cantidad'] ?? 1); ?></span>
                    </p>
                    <p class="producto">Total Producto: L.
                        <span><?php echo s(number_format($itemTotal, 2)); ?></span>
                    </p>

                    <?php
                    // Detectar si es el último ítem de esta compra para imprimir el total global
                    $nextPurchase = $purchases[$key + 1] ?? null;
                    $isLast = !$nextPurchase || ($nextPurchase['id'] ?? 0) !== $purchase['id'];

                    if ($isLast) : ?>
                        <p class="total">Total a Cobrar / Pagar: <span>L. <?php echo s(number_format($total, 2)); ?></span></p>

                        <div id="reporte-acciones" class="reporte--acciones">
                            <button type="button" data-id="<?php echo s($purchase['id']); ?>" class="boton-eliminar encargo">Eliminar</button>
                            <button type="button" class="boton-imprimir" id="imprimir">Imprimir</button>
                        </div>
                    <?php endif; ?>
                    </li>
                <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>