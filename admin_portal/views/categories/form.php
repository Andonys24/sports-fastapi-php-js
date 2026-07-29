<div class="campo">
    <label for="nombre">Nombre</label>
    <input
        type="text"
        id="nombre"
        placeholder="Nombre Categoría"
        name="nombre"
        value="<?php echo s($category['name'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="descripcion">Descripción</label>
    <textarea
        id="descripcion"
        name="descripcion"
        placeholder="Descripción categoría"><?php echo s($category['description'] ?? ''); ?></textarea>
</div>