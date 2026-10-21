<?php
require "layout/header.php"

?>

<main>
    <div class="cabecera">

        <img src="..assets/images/iconos/Usuarios.png" alt="">
        <h1>Lista de usuarios</h1>
    
    </div>
    <?php foreach($usuarios as $u): ?>
        <div>
            <img src="..assets/images/iconos/User.png" alt="">
            <p> <?= $u[''] ?> </p>
            <p> <?= $u[''] ?> </p>
            
            <div class="opciones">
                <a href=""> <img src="..assets/images/iconos/Ajustes.png" alt=""> </a>
                <a href=""> <img src="..assets/images/iconos/Trash.png" alt=""> </a>
            </div>
        </div>
</main>







<?php
require "layout/footer.php"

?>