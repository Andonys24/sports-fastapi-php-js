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
        value="<?php echo s($provider['phone_number'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="periodo_contrato">Periodo de Contrato</label>
    <input
        type="number"
        id="periodo_contrato"
        name="periodo_contrato"
        placeholder="Periodo del contrato"
        value="<?php echo s((string)($provider['contract_period'] ?? '')); ?>">
</div>

<div class="campo">
    <label for="tipo_contrato">Tipo de Contrato</label>
    <input
        type="text"
        id="tipo_contrato"
        name="tipo_contrato"
        placeholder="Tipo de contrato"
        value="<?php echo s($provider['contract_type'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="direccion">Dirección</label>
    <textarea
        id="direccion"
        name="direccion"
        placeholder="Dirección del proveedor"><?php echo s($provider['address'] ?? ''); ?></textarea>
</div>