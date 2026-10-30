<?php include __DIR__ . '/layout/header.php'; ?>
 
    <div class="contenedorPrincipal">
        <?php include __DIR__ . '/layout/nav.php'; ?>
        <main id="tarjeta">
            <div id="d1">
                <img src="assets/images/Iconos/Editar.png" alt="Editar">
                <h2>Editar datos</h2>
            </div>
 
            <?php if (!empty($_SESSION['errores_ajustes'])): ?>
                <div class="errores">
                    <?php foreach ($_SESSION['errores_ajustes'] as $error): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errores_ajustes']); ?>
            <?php endif; ?>
 
            <form id="formAjustes" action="index.php?controller=AjustesController&accion=editarDatos" method="post">
                <input type="text" name="apellidos" placeholder="<?= $usuario->apellidos ?>" autocomplete="off">
                <input type="text" name="nombre" placeholder="<?= $usuario->nombre ?>" autocomplete="off">
 
                <input type="password" name="contrasenna" id="contrasenna"
                       placeholder="Nueva Contraseña" autocomplete="new-password">
                <p id="msgContrasenna"></p>
 
                <input type="text" name="nombreUsuario" id="nombreUsuario"
                       placeholder="<?= $usuario->nombre_usuario ?>" autocomplete="off">
                <p id="msgUsuario"></p>
 
                <?php if ($usuario->tipo === "admin"): ?>
                    <select name="tipo" id="tipo">
                        <option value="user">user</option>
                        <option value="admin" selected>admin</option>
                    </select>
                    <p id="msgTipo"></p>
                <?php else: ?>
                    <select name="tipo" disabled>
                        <option value="user" selected>user</option>
                        <option value="admin">admin</option>
                    </select>
                <?php endif; ?>
 
                <div id="d2">
                    <input type="submit" value="Aceptar cambios">
                    <a href="index.php">Cancelar</a>
                </div>
            </form>
        </main>
    </div>
 
    <script src="assets/scripts/ajustes.js"></script>
 
<?php include __DIR__ . '/layout/footer.php'; ?>