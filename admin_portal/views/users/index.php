<h1 class="nombre-pagina"><?php echo s($title ?? 'Usuarios'); ?></h1>
<p class="descripcion-pagina">Administra los usuarios registrados en el sistema.</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<ul class="productos">
    <?php if (!empty($users)) : ?>
        <?php foreach ($users as $user) : ?>
            <li>
                <p>Username: <span><?php echo s($user['username'] ?? ''); ?></span></p>
                <p>Nombre: <span><?php echo s($user['full_name'] ?? $user['nombre'] ?? 'Sin Nombre'); ?></span></p>
                <p>Email: <span><?php echo s($user['email'] ?? ''); ?></span></p>

                <div class="acciones">
                    <a class="boton-actualizar" href="/users/update?id=<?php echo s($user['id'] ?? ''); ?>">Actualizar</a>
                    <button type="button" data-id="<?php echo s($user['id'] ?? ''); ?>" class="boton-eliminar usuario">Eliminar</button>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h2 class="no-encargos">No hay usuarios disponibles</h2>
    <?php endif; ?>
</ul>