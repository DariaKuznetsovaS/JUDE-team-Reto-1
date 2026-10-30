<?php
include __DIR__ . '/layout/header.php'; ?>


    <div class="contenedorPrincipal">
        <?php include __DIR__ . '/layout/nav.php'; ?>
        <main id="tarjeta">
            <div id="d1">
                <img src="assets/images/Iconos/UsuarioNuevo.png" alt="Editar">
                <h2>A&ntilde;adir Nuevo Usuario</h2>
            </div>
 
            <form id="formAnnadirUser" action="index.php?controller=Usuario&accion=annadirUsuarioNuevo" method="post">
                <input type="text" name="apellidos" placeholder="Apellidos" required >
                <input type="text" name="nombre" placeholder="Nombre" required>
 
                <input type="password" name="contrasenna" id="contrasenna"
                       placeholder="Contraseña" required>
                <p id="msgContrasenna"></p>
 
                <input type="text" name="nombreUsuario" id="nombreUsuario"
                       placeholder="Nombre de Usuario" required >
                <p id="msgUsuario"></p>
                
                <select name="tipo" id="tipo" required>
                    <option value="user" selected>user</option>
                    <option value="admin" >admin</option>
                </select>
                <p id="msgTipo"></p>
 
                <div id="d2">
                    <input type="submit" value="Aceptar cambios">
                    <a href="index.php">Cancelar</a>
                </div>
            </form>
        </main>
    </div>
    <script src="assets/scripts/scriptRegistro.js"></script>


<?php include __DIR__ . '/layout/footer.php'; ?>
