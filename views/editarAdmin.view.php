<?php
require "layout/header.php";

?>

<div class="contenedorPrincipal">
    <?php include __DIR__ . '/layout/nav.php'; ?>
    
    <main class="formulario">
        <div class="cabecera">
            <img src="assets/images/iconos/Editar.png" alt="">
        <h1>Edición de usuario</h1> 

        </div>


        <form action="index.php?controller=UsuarioController&accion=actualizarUsuario" method="POST">

            <input type="hidden" name="id" value="<?= $usuarioAEditar['id_usuario'] ?>">

            <div class="camposForm">

                <input type="text" name="nuevoNombre" placeholder="<?= $usuarioAEditar['nombre'] ?>">
                <input type="text" name="nuevoApellido" placeholder="<?= $usuarioAEditar['apellidos'] ?>">
                <input type="text" name="nuevoUsername" placeholder="<?= $usuarioAEditar['nombre_usuario'] ?>">
                <input type="password" name="nuevaContrasena">
                <select name="tipo" id="tipo" required>
                    <option value="" disabled selected>Tipo de usuario</option>
                    <option value="User">Usuario</option>
                    <option value="Admin">Administrador</option>
                </select>


            </div>

            <div class="botonesForm">

                <button type="submit" name="aceptar">
                    Aceptar cambios
                </button>

                <button type="button" onclick="window.history.back()">
                    Cancelar
                </button>

            </div>

        </form>
    <main>
</div>

<?php
require "layout/footer.php";

?>