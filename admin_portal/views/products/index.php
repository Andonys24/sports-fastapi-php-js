<h1 class="nombre-pagina"><?php echo s($title ?? 'Products'); ?></h1>
<p class="descripcion-pagina">Administra los Productos</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $product) : ?>
            <li>
                <p>Nombre: <span><?php echo s($product['name'] ?? ''); ?></span></p>
                <p>Precio: <span>L. <?php echo s($product['price'] ?? '0.00'); ?></span></p>
                <?php foreach ($categories ?? [] as $category) :
                    $cat_id = $category['id'] ?? null;
                    $prod_cat_id = $product['category_id'] ?? null;
                    if ((string)$prod_cat_id === (string)$cat_id) : ?>
                        <p>Categoría: <span><?php echo s($category['name'] ?? ''); ?></span></p>
                <?php
                    endif;
                endforeach;
                ?>
                <div class="acciones">
                    <a class="boton" href="/products/update?id=<?php echo s($product['id']); ?>">Actualizar</a>
                    <input type="submit" data-id="<?php echo s($product['id']); ?>" value="Eliminar" class="boton-eliminar producto">
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay productos disponibles</h2>
    <?php endif; ?>
</ul>