<div class="campo">
    <label for="nombre">Nombre</label>
    <input
        type="text"
        id="nombre"
        placeholder="Nombre Producto"
        name="nombre"
        value="<?php echo s($product['name'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="categoria">Categoría</label>
    <select name="categoria_id" id="categoria">
        <option value="" disabled selected>-- Seleccionar --</option>
        <?php foreach ($categories ?? [] as $category) :
            $cat_id = $category['id'] ?? null;
            $selected_cat_id = $product['category_id'] ?? null;
        ?>
            <option
                <?php echo ($selected_cat_id === $cat_id) ? 'selected' : ''; ?>
                value="<?php echo s($cat_id); ?>">
                <?php echo s($category['name'] ?? ''); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="campo">
    <label for="precio">Precio</label>
    <input
        type="number"
        id="precio"
        placeholder="Precio del Producto"
        name="precio"
        value="<?php echo s($product['price'] ?? ''); ?>"
        step="0.01"
        min="0">
</div>