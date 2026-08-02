<div class="campo">
    <label for="product_id">Producto</label>
    <select name="product_id" id="product_id">
        <option value="" disabled <?php echo empty($purchase['product_id'] ?? null) ? 'selected' : ''; ?>>-- Seleccionar Producto --</option>
        <?php foreach ($products ?? [] as $product) : ?>
            <option value="<?php echo s($product['id']); ?>" <?php echo (($purchase['product_id'] ?? '') == $product['id']) ? 'selected' : ''; ?>>
                <?php echo s($product['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="campo">
    <label for="provider_id">Proveedor</label>
    <select name="provider_id" id="provider_id">
        <option value="" disabled <?php echo empty($purchase['provider_id'] ?? $purchase['supplier_id'] ?? null) ? 'selected' : ''; ?>>-- Seleccionar Proveedor --</option>
        <?php foreach ($providers ?? [] as $provider) : ?>
            <option value="<?php echo s($provider['id']); ?>" <?php echo ((($purchase['provider_id'] ?? $purchase['supplier_id'] ?? '') == $provider['id'])) ? 'selected' : ''; ?>>
                <?php echo s($provider['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="campo">
    <label for="quantity">Cantidad</label>
    <input
        type="number"
        id="quantity"
        placeholder="Cantidad de unidades"
        name="quantity"
        min="1"
        value="<?php echo s($purchase['quantity'] ?? 1); ?>">
</div>

<div class="campo">
    <label for="purchase_price">Precio de Compra (L.)</label>
    <input
        type="number"
        id="purchase_price"
        placeholder="Costo unitario de compra"
        name="purchase_price"
        value="<?php echo s($purchase['purchase_price'] ?? ''); ?>"
        step="0.01"
        min="0">
</div>

<div class="campo">
    <label for="date">Fecha de Compra</label>
    <input
        type="date"
        id="date"
        name="date"
        value="<?php echo s($purchase['date'] ?? date('Y-m-d')); ?>">
</div>