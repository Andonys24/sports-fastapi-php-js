<h1 class="nombre-pagina"><?php echo s($title ?? 'Proveedores'); ?></h1>
<p class="descripcion-pagina">Administra los proveedores registrados.</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($providers)) : ?>
        <?php foreach ($providers as $provider) : ?>
            <li>
                <p>Nombre: <span><?php echo s($provider['name'] ?? ''); ?></span></p>
                <p>Dirección: <span><?php echo s($provider['address'] ?? ''); ?></span></p>
                <p>Teléfono: <span><?php echo s($provider['phone_number'] ?? ''); ?></span></p>
                <p>Periodo de contrato: <span><?php echo s((string)($provider['contract_period'] ?? '')); ?></span></p>
                <p>Tipo de contrato: <span><?php echo s($provider['contract_type'] ?? ''); ?></span></p>
                <div class="acciones">
                    <a class="boton" href="/suppliers/update?id=<?php echo s($provider['id']); ?>">Actualizar</a>
                    <input type="submit" data-id="<?php echo s($provider['id']); ?>" value="Eliminar" class="boton-eliminar proveedor">
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay proveedores disponibles</h2>
    <?php endif; ?>
</ul>