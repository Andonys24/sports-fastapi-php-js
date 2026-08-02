<h1 class="nombre-pagina"><?php echo s($title ?? 'Inventario'); ?></h1>
<p class="descripcion-pagina">Control de existencias y estado de stock</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($inventory) && is_array($inventory)) : ?>
        <?php foreach ($inventory as $item) : ?>
            <?php
            if (!is_array($item)) continue;
            $stock = (int) ($item['stock'] ?? 0);
            $estadoClass = $stock === 0 ? 'agotado' : ($stock <= 5 ? 'bajo' : 'normal');
            $precio = number_format((float) ($item['price'] ?? 0), 2);
            ?>
            <li>
                <p>Producto: <span><?php echo s($item['product_name'] ?? $item['name'] ?? ''); ?></span></p>
                <p>Categoría: <span><?php echo s($item['category_name'] ?? $item['category'] ?? 'Sin Categoría'); ?></span></p>
                <p>Precio Venta: <span>L. <?php echo s($precio); ?></span></p>
                <p>Estado: <span><?php echo s($stock > 0 ? 'Disponible' : 'Agotado'); ?></span></p>
                <p>Stock Disponible: <span class="stock-badge <?php echo $estadoClass; ?>"><?php echo $stock; ?> unidades</span></p>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay elementos en inventario o la API no devolvió datos válidos</h2>
    <?php endif; ?>
</ul>