<?php
include __DIR__ . "/layout/header.php"

?>

<div class ="contenedorPrincipal">
    <?php include __DIR__ . '/layout/nav.php'; ?>
    <div class="cabecera">

        <img src="assets/images/Iconos/Usuarios.png" alt="">
        <h1>Lista de usuarios</h1>
    
    </div>
    <?php if(!empty($usuarios)) : ?>
        <?php foreach($usuarios as $u): ?>
        <div>
            <img src="assets/images/Iconos/User.png" alt="">
            <p> <?= $u['nombre_usuario'] ?> </p>
            <p> <?= $u['nombre'] . ' ' . $u['apellidos'] ?> </p>
            
            <div class="opciones">
                <a href="index.php?controller=UsuarioController&accion=prepararEditar&id=<?= $u['id_usuario'] ?>"> <img src="assets/images/Iconos/Ajustes.png" alt=""> </a>
                <a class="eliminar" href="index.php?controller=UsuarioController&accion=eliminar&id=<?= $u['id_usuario'] ?>"> <img src="assets/images/Iconos/Trash.png" alt=""></a>
            </div>
        </div>

        <?php endforeach ; ?>
    <?php else: ?>
        <p> No hay usuarios registrados. </p>
    <?php endif; ?>


    <a href="index.php?controller=UsuarioController&accion=crearUser">+Crear nuevo usuario</a>

</div>

<script src="assets/scripts/eliminarUser.js" ></script>







<?php
require "layout/footer.php"

?>