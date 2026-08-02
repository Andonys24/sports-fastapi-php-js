<h1 class="nombre-pagina"><?php echo s($title ?? 'Historial de Compras'); ?></h1>
<p class="descripcion-pagina">Registro e historial de entradas de productos a inventario</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<h2>Buscar Compras por Fecha</h2>
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

<?php if (empty($purchases)) : ?>
    <h2 class="no-encargos">No hay registros de compras <?php echo !empty($fecha) ? "en la fecha " . s($fecha) : ""; ?></h2>
<?php else : ?>
    <div class="encargo-admin">
        <ul class="encargos" id="compras">
            <?php foreach ($purchases as $purchase) :
                if (!is_array($purchase)) continue;

                $quantity = (int) ($purchase['quantity'] ?? 0);
                $unitPrice = (float) ($purchase['purchase_price'] ?? 0);
                $totalPurchase = $quantity * $unitPrice;
            ?>
                <li data-id="<?php echo s($purchase['id']); ?>">
                    <p>Comprobante: <span>#<?php echo s($purchase['id']); ?></span></p>
                    <p>Fecha Registro: <span><?php echo s($purchase['date_purchase'] ?? 'N/A'); ?></span></p>
                    <p>Proveedor: <span><?php echo s($purchase['supplier_name'] ?? 'N/A'); ?></span></p>
                    <p>Registrado Por: <span><?php echo s($purchase['username'] ?? 'N/A'); ?></span></p>

                    <h3>Detalle del Producto</h3>
                    <p class="producto">Producto:
                        <span><?php echo s($purchase['product_name'] ?? 'N/A'); ?></span>
                    </p>
                    <p class="producto">Precio Unitario: L.
                        <span><?php echo s(number_format($unitPrice, 2)) . ' X ' . s($quantity) . ' unidades'; ?></span>
                    </p>

                    <p class="total">Total Invertido: <span>L. <?php echo s(number_format($totalPurchase, 2)); ?></span></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>