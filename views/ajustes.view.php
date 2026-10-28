<?php include __DIR__ . '/layout/header.php'; ?>



    <div id="contenedorAjustes">
        <?php include __DIR__ . '/layout/nav.php'; ?>
        <div id="tarjeta">
            <div id="d1">
                <img src="assets/images/Iconos/Editar.png" alt="Editar">
                <h2>Editar datos</h2>
            </div>

            


            <form action="index.php?controller=AjustesController&accion=editarDatos" method="post">
                <input type="text" name="apellidos" placeholder="<?=$usuario->apellidos?>">
                <input type="text" name="nombre" placeholder="<?=$usuario->nombre?>">
                <input type="password" name="contrasenna" id="contrasenna" placeholder="<?=$usuario->contrasenna?>">
                <p id="msgContrasenna"></p>
                <input type="text" name="nombreUsuario" id="nombreUsuario" placeholder="<?=$usuario->nombre_usuario?>">
                <p id="msgUsuario"></p>
                <?php if($usuario->tipo==="admin"):?>
                    <select name="tipo" id=tipo>
                        <option value="user">user</option>
                        <option value="admin" selected >admin</option>
                    </select>
                    <p id="msgTipo"></p>

                <?php else:?>
                    <select name="tipo" disabled>
                        <option value="user" selected >user</option>
                        <option value="admin">admin</option>
                    </select>
                <?php endif;?>

                <div id="d2">
                    <input type="submit" value="Aceptar cambios">
                    <a href="index.php">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/scripts/ajustes.js"></script>


<?php include __DIR__ . '/layout/footer.php'; ?>