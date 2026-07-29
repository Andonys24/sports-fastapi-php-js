<?php if (!empty($alerts)) : ?>
    <div class="alertas" aria-live="polite" aria-atomic="true">
        <?php foreach (($alerts ?? []) as $key => $mensajes) : ?>
            <?php foreach ($mensajes as $mensaje) : ?>
                <div class="alerta <?php echo s($key); ?>" role="alert">
                    <?php echo s($mensaje); ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>