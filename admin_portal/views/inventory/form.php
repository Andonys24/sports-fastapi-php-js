<div class="campo">
    <label for="producto">Producto</label>
    <input
        type="text"
        id="producto"
        name="producto"
        placeholder="Producto"
        value="<?php echo s($inventory['product'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="stock">Stock</label>
    <input
        type="number"
        id="stock"
        name="stock"
        min="0"
        step="1"
        placeholder="Stock"
        value="<?php echo s($inventory['stock'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="observacion">Observación</label>
    <textarea
        id="observacion"
        name="observacion"
        placeholder="Observación de inventario"><?php echo s($inventory['note'] ?? ''); ?></textarea>
</div>