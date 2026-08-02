<h1 class="nombre-pagina"><?php echo s($title ?? 'Proveedores'); ?></h1>
<p class="descripcion-pagina">Administra los proveedores registrados.</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($suppliers)) : ?>
        <?php foreach ($suppliers as $supplier) : ?>
            <li>
                <p>Nombre: <span><?php echo s($supplier['name'] ?? ''); ?></span></p>
                <p>Dirección: <span><?php echo s($supplier['address'] ?? ''); ?></span></p>
                <p>Teléfono: <span><?php echo s($supplier['phone_number'] ?? ''); ?></span></p>
                <p>Periodo de contrato: <span><?php echo s((string)($supplier['contract_period'] ?? '')); ?> Meses</span></p>
                <p>Tipo de contrato: <span><?php echo s($supplier['contract_type'] ?? ''); ?></span></p>

                <div class="acciones">
                    <a class="boton-actualizar" href="/suppliers/update?id=<?php echo s($supplier['id'] ?? ''); ?>">Actualizar</a>
                    <button type="button" data-id="<?php echo s($supplier['id'] ?? ''); ?>" class="boton-eliminar proveedor">Eliminar</button>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay proveedores disponibles</h2>
    <?php endif; ?>
</ul>