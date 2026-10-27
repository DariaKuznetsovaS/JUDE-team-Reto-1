<?php
require "layout/header.php"

?>

<main>
    <div class="cabecera">

        <img src="assets/images/Iconos/Usuarios.png" alt="">
        <h1>Lista de usuarios</h1>
    
    </div>
    <?php foreach($usuarios as $u): ?>
        <div>
            <img src="assets/images/Iconos/User.png" alt="">
            <p> <?= $u['nombre_usuario'] ?> </p>
            <p> <?= $u['nombre'] . ' ' . $u['apellidos'] ?> </p>
            
            <div class="opciones">
                <a href="editarAdmin.view.php?id=<?= $u['id_usuario'] ?>"> <img src="assets/images/Iconos/Ajustes.png" alt=""> </a>
<!-- con js sacar una ventana de confirmación y directamente eliminar -->  <img src="assets/images/Iconos/Trash.png" alt="">
            </div>
        </div>

    <?php endforeach ; ?>

    <a href="">+Crear nuevo usuario</a>

</main>







<?php
require "layout/footer.php"

?