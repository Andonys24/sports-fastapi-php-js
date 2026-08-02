<div class="barra">
    <p>Bienvenido <span><?php echo s($_SESSION['name'] ?? 'Desconocido'); ?></span></p>
    <form action="/logout" method="post" class="logout-form">
        <button type="submit" class="boton">Cerrar Sesión</button>
    </form>
</div>

<?php if (isset($_SESSION['role']) && (int)$_SESSION['role'] === ROLE_ADMIN) : ?>
    <div class="barra-productos">
        <a href="/admin" class="boton">Panel Principal</a>
        <a href="/inventory" class="boton">Ver Inventario</a>
        <a href="/products" class="boton">Ver Productos</a>
        <a href="/products/create" class="boton">Agregar Producto</a>
        <a href="/categories" class="boton">Ver Categorías</a>
        <a href="/categories/create" class="boton">Agregar Categoría</a>
        <a href="/users" class="boton">Ver Usuarios</a>
        <a href="/users/create" class="boton">Agregar Usuario</a>
        <a href="/suppliers" class="boton">Ver Proveedores</a>
        <a href="/suppliers/create" class="boton">Agregar Proveedor</a>
        <a href="/purchases" class="boton">Ver Compras</a>
        <a href="/purchases/create" class="boton">Registrar Compra</a>
    </div>
<?php endif; ?>