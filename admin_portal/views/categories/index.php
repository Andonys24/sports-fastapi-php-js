<h1 class="nombre-pagina"><?php echo s($title ?? 'Categorías'); ?></h1>
<p class="descripcion-pagina">Administra las categorías</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($categories)) : ?>
        <?php foreach ($categories as $category) : ?>
            <li>
                <p>Nombre: <span><?php echo s($category['name'] ?? ''); ?></span></p>
                <p>Descripción: <span><?php echo s($category['description'] ?? 'Sin descripción'); ?></span></p>

                <div class="acciones">
                    <a class="boton-actualizar" href="/categories/update?id=<?php echo s($category['id'] ?? ''); ?>">Actualizar</a>
                    <button type="button" data-id="<?php echo s($category['id'] ?? ''); ?>" class="boton-eliminar categoria">Eliminar</button>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay categorías disponibles</h2>
    <?php endif; ?>
</ul>