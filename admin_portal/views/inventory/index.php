<h1 class="nombre-pagina"><?php echo s($title ?? 'Inventario'); ?></h1>
<p class="descripcion-pagina">Control de existencias y estado de stock</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($inventory) && is_array($inventory)) : ?>
        <?php foreach ($inventory as $item) : ?>
            <?php
            if (!is_array($item)) continue;
            $stock = (int)($item['stock'] ?? 0);
            $estadoClass = $stock === 0 ? 'agotado' : ($stock <= 5 ? 'bajo' : 'normal');
            ?>
            <li>
                <p>Producto: <span><?php echo s($item['product_name'] ?? ''); ?></span></p>
                <p>Categoría: <span><?php echo s($item['category'] ?? 'Sin Categoría'); ?></span></p>
                <p>Precio Venta: <span>L. <?php echo s($item['price'] ?? '0.00'); ?></span></p>
                <p>Estado: <span><?php echo s($item['status'] ?? ($stock > 0 ? 'Disponible' : 'Agotado')); ?></span></p>
                <p>Stock Disponible: <strong class="stock-badge <?php echo $estadoClass; ?>"><?php echo $stock; ?> unidades</strong></p>

                <div class="acciones">
                    <a class="boton-actualizar" href="/inventory/update?id=<?php echo s($item['product_id'] ?? ''); ?>">Ajustar Stock</a>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay elementos en inventario o la API no devolvió datos válidos</h2>
    <?php endif; ?>
</ul>