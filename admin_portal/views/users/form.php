<div class="campo">
    <label for="nombre">Nombre</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        placeholder="Nombre del usuario"
        value="<?php echo s($user['name'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        placeholder="Email del usuario"
        value="<?php echo s($user['email'] ?? ''); ?>">
</div>

<div class="campo">
    <label for="password">Password</label>
    <input
        type="password"
        id="password"
        name="password"
        placeholder="Password del usuario">
</div>

<div class="campo">
    <label for="role">Rol</label>
    <select name="role" id="role">
        <option value="1">Administrador</option>
        <option value="2">Cliente</option>
    </select>
</div>
