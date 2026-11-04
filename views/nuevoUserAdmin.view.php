<?php
include __DIR__ . '/layout/header.php'; ?>
<link rel="stylesheet" href="assets/css/ajustes.css">


    <div class="contenedorPrincipal">
        <?php include __DIR__ . '/layout/nav.php'; ?>
        <main class="formulario" id="tarjeta">
            <div class="cabecera" id="d1">
                <img src="assets/images/Iconos/UsuarioNuevo.png" alt="Editar">
                <h2>A&ntilde;adir Nuevo Usuario</h2>
            </div>
 
            <form id="formAnnadirUser" action="index.php?controller=UsuarioController&accion=annadirUsuarioNuevo" method="post">
                <div class="camposForm">
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
                </div>
 
                <div class="botonesForm" id="d2">
                    <input id="botonGuardar" type="submit" value="Crear Usuario">
                    <button type="button" onclick="window.location.href='index.php'">
                        Cancelar
                    </button>
                </div>
            </form>
        </main>
    </div>
    <script src="assets/scripts/scriptRegistro.js"></script>


<?php include __DIR__ . '/layout/footer.php'; ?>
