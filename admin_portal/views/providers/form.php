<div class="campo">
    <label for="nombre">Nombre</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        placeholder="Nombre del proveedor"
        value="<?php echo s($provider['name'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="telefono">Teléfono</label>
    <input
        type="text"
        id="telefono"
        name="telefono"
        placeholder="Teléfono del proveedor"
        value="<?php echo s($provider['phone'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        placeholder="Email del proveedor"
        value="<?php echo s($provider['email'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="direccion">Dirección</label>
    <textarea
        id="direccion"
        name="direccion"
        placeholder="Dirección del proveedor"><?php echo s($provider['address'] ?? ''); ?></textarea>
</div>