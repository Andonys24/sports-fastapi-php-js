<h1 class="nombre-pagina"><?php echo $title ?? ""; ?></h1>
<p class="descripcion-pagina">Inicia sesion en <?php echo APP_NAME; ?></p>
<?php include_once __DIR__ . '/../templates/alerts.php'; ?>
<form class="formulario" method="post" action="/">
    <div class="campo">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Ingresa tu Email" value="<?php echo s($user["email"] ?? ""); ?>">
    </div>
    <div class="campo campo__password">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Ingresa tu Password">
        <i class="fa-solid fa-eye eye-icon" id="eye-icon"></i>
    </div>
    <input type="submit" class="boton" value="Iniciar Sesion">
</form>