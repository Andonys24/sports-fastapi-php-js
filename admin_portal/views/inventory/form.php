<div class="campo">
    <label for="product_name">Producto</label>
    <input
        type="text"
        id="product_name"
        value="<?php echo s($inventory['product_name'] ?? ''); ?>"
        readonly
        disabled>
</div>

<div class="campo">
    <label for="stock">Nuevo Stock Físico</label>
    <input
        type="number"
        id="stock"
        placeholder="Cantidad en existencia"
        name="stock"
        min="0"
        value="<?php echo s($inventory['stock'] ?? 0); ?>">
</div>