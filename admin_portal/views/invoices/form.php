<div class="campo">
    <label for="cliente">Cliente</label>
    <input
        type="text"
        id="cliente"
        name="cliente"
        placeholder="Nombre del cliente"
        value="<?php echo s($invoice['client'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        placeholder="Email del cliente"
        value="<?php echo s($invoice['email'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="direccion">Dirección</label>
    <textarea
        id="direccion"
        name="direccion"
        placeholder="Dirección de entrega"><?php echo s($invoice['address'] ?? ''); ?></textarea>
</div>

<div class="campo">
    <label for="fecha">Fecha</label>
    <input
        type="date"
        id="fecha"
        name="fecha"
        value="<?php echo s($invoice['date'] ?? ''); ?>">
</div>