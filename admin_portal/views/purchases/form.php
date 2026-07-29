<div class="campo">
    <label for="proveedor">Proveedor</label>
    <input
        type="text"
        id="proveedor"
        name="proveedor"
        placeholder="Proveedor"
        value="<?php echo s($purchase['provider'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="producto">Producto</label>
    <input
        type="text"
        id="producto"
        name="producto"
        placeholder="Producto"
        value="<?php echo s($purchase['product'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="cantidad">Cantidad</label>
    <input
        type="number"
        id="cantidad"
        name="cantidad"
        min="1"
        step="1"
        placeholder="Cantidad"
        value="<?php echo s($purchase['quantity'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="costo">Costo</label>
    <input
        type="number"
        id="costo"
        name="costo"
        min="0"
        step="0.01"
        placeholder="Costo total"
        value="<?php echo s($purchase['cost'] ?? ''); ?>">
</div>
