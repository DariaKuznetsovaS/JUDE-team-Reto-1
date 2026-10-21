<?php
require "layout/header.php";

?>

<div class="cabecera">

    <img src="../assets/images/iconos/Editar" alt="">
    <h1>Edición de usuario</h1>

</div>

<form action="" method="">

    <div class="camposForm">

        <input type="text" name="nuevoNombre" placeholder="Nuevo Nombre">
        <input type="text" name="nuevoApellido" placeholder="Nuevo Apellido">
        <input type="text" name="nuevUsername" placeholder="Nuevo Username">
        <input type="password" name="nuevaContrasena" placeholder="Nueva Contrasena">
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



<?php
require "layout/footer.php";

?>