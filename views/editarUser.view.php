<?php
require "layout/header.php";

?>

<div class="cabecera">

    <img src="../assets/images/iconos/Editar" alt="">
    <h1>Editar datos</h1>

</div>

<form action="" method="">

    <div class="camposForm">

        <input type="text" name="nuevoNombre" placeholder="Nuevo Nombre">
        <input type="text" name="nuevoApellido" placeholder="Nuevo Apellido">
        <input type="text" name="nuevUsername" placeholder="Nuevo Username">
        <input type="password" name="nuevaContrasena" placeholder="Nueva Contrasena">

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