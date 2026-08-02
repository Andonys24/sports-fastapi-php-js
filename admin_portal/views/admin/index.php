<h1 class="nombre-pagina"><?php echo s($title ?? 'Panel de Administración'); ?></h1>
<p class="descripcion-pagina">Resumen general y métricas del sistema</p>

<?php include_once __DIR__ . '/../templates/bar.php'; ?>

<div class="listado-productos">
    <div class="producto">
        <p class="precio-producto"><?php echo s($totalProducts ?? 0); ?></p>
        <p><strong>Productos Registrados</strong></p>
        <a class="boton-actualizar" href="/products">Ir a Productos</a>
    </div>

    <div class="producto">
        <p class="precio-producto"><?php echo s($totalCategories ?? 0); ?></p>
        <p><strong>Categorías Activas</strong></p>
        <a class="boton-actualizar" href="/categories">Ir a Categorías</a>
    </div>

    <div class="producto">
        <p class="precio-producto"><?php echo s($totalSuppliers ?? 0); ?></p>
        <p><strong>Proveedores Registrados</strong></p>
        <a class="boton-actualizar" href="/suppliers">Ir a Proveedores</a>
    </div>

    <div class="producto">
        <p class="precio-producto"><?php echo s($totalPurchases ?? 0); ?></p>
        <p><strong>Compras Realizadas</strong></p>
        <a class="boton-actualizar" href="/purchases">Ir a Compras</a>
    </div>

    <div class="producto">
        <p class="precio-producto"><?php echo s($lowStockCount ?? 0); ?></p>
        <p><strong>Productos con Stock Bajo (≤ 5)</strong></p>
        <a class="boton-eliminar" href="/inventory">Revisar Inventario</a>
    </div>
</div>