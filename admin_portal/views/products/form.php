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
        <option value="" disabled <?php echo empty($product['category_id'] ?? null) ? 'selected' : ''; ?>>-- Seleccionar --</option>
        <?php foreach ($categories ?? [] as $category) :
            $cat_id = $category['id'] ?? null;
            $selected_cat_id = $product['category_id'] ?? null;
        ?>
            <option
                <?php echo ((string)$selected_cat_id === (string)$cat_id) ? 'selected' : ''; ?>
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

<div class="campo">
    <label for="proveedor">Proveedor</label>
    <select name="provider_id" id="proveedor">
        <option value="" disabled <?php echo empty($product['provider_id'] ?? null) ? 'selected' : ''; ?>>-- Seleccionar Proveedor --</option>
        <?php foreach ($providers ?? [] as $provider) : ?>
            <option value="<?php echo s($provider['id']); ?>" <?php echo (($product['provider_id'] ?? '') == $provider['id']) ? 'selected' : ''; ?>>
                <?php echo s($provider['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="campo">
    <label for="brand">Marca</label>
    <input type="text" id="brand" placeholder="Marca del producto" name="brand" value="<?php echo s($product['brand'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="img_url">URL de Imagen</label>
    <input type="text" id="img_url" placeholder="https://..." name="img_url" value="<?php echo s($product['img_url'] ?? ''); ?>">
</div>