<div class="campo">
    <label for="product_id">Producto:</label>
    <select name="product_id" id="product_id">
        <option value="">-- Seleccione --</option>
        <?php foreach ($products ?? [] as $product) : ?>
            <option
                value="<?php echo s($product['id']); ?>"
                <?php echo (string)($purchase['product_id'] ?? '') === (string)$product['id'] ? 'selected' : ''; ?>>
                <?php echo s($product['name'] ?? $product['nombre'] ?? ''); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="campo">
    <label for="supplier_id">Proveedor:</label>
    <select name="supplier_id" id="supplier_id">
        <option value="">-- Seleccione --</option>
        <?php foreach ($suppliers ?? [] as $supplier) : ?>
            <option
                value="<?php echo s($supplier['id']); ?>"
                <?php echo (string)($purchase['supplier_id'] ?? '') === (string)$supplier['id'] ? 'selected' : ''; ?>>
                <?php echo s($supplier['name'] ?? $supplier['nombre'] ?? ''); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="campo">
    <label for="quantity">Cantidad:</label>
    <input
        type="number"
        id="quantity"
        name="quantity"
        placeholder="Ej. 10"
        value="<?php echo s($purchase['quantity'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="purchase_price">Precio de Compra (L.):</label>
    <input
        type="number"
        step="0.01"
        id="purchase_price"
        name="purchase_price"
        placeholder="Ej. 150.00"
        value="<?php echo s($purchase['purchase_price'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="date_purchase">Fecha de Compra:</label>
    <input
        type="date"
        id="date_purchase"
        name="date_purchase"
        value="<?php echo s($purchase['date_purchase'] ?? date('Y-m-d')); ?>">
</div>