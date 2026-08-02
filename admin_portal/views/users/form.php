<div class="campo">
    <label for="username">Username</label>
    <input
        type="text"
        id="username"
        name="username"
        placeholder="Nombre de usuario único"
        value="<?php echo s($user['username'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="nombre">Nombre Completo</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        placeholder="Nombre completo del usuario"
        value="<?php echo s($user['full_name'] ?? $user['nombre'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        placeholder="Correo electrónico"
        value="<?php echo s($user['email'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="password">Password</label>
    <input
        type="password"
        id="password"
        name="password"
        placeholder="Contraseña del usuario">
</div>