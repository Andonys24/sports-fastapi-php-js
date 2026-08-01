<h1 class="nombre-pagina"><?php echo s($title ?? 'Products'); ?></h1>
<p class="descripcion-pagina">Administra los Productos</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $product) : ?>
            <?php
            $catId = $product['category_id'] ?? null;
            $provId = $product['provider_id'] ?? null;
            ?>
            <li>
                <p>Nombre: <span><?php echo s($product['name'] ?? ''); ?></span></p>
                <p>Precio: <span>L. <?php echo s($product['price'] ?? '0.00'); ?></span></p>

                <!-- Búsqueda directa y limpia -->
                <p>Categoría: <span><?php echo s($categories[$catId] ?? 'Sin categoría'); ?></span></p>
                <p>Proveedor: <span><?php echo s($providers[$provId] ?? 'Sin proveedor'); ?></span></p>

                <div class="acciones">
                    <a class="boton" href="/products/update?id=<?php echo s($product['id']); ?>">Actualizar</a>
                    <button type="button" data-id="<?php echo s($product['id']); ?>" class="boton-eliminar producto">Eliminar</button>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay productos disponibles</h2>
    <?php endif; ?>
</ul>