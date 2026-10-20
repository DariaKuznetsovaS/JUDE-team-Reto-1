<?php 


require "layout/header.php";

?>


<main>
    <div class="userContainer">
        <img src="../assets/images/User.png" alt="Imagen usuario">
        <div class="datosUsuario">
            <h1> <?= $user['nombre_usuario'] ?> </h1>
            <p> <?= $user['nombre'] . ' ' . $user['apellido'] ?> </p>
            <p> <?= $user['tipo'] ?> </p>
        </div>
        <div class="enlaces">
            <a href=""><img src="../assets/images/Editar.png" alt=""></a>
            <a href=""><img src="../assets/images/Salir.png" alt=""></a>
        </div>

    </div>


    <div class="preguntas">
        <button>+ Publicar nueva pregunta</button>
        <div class="misPreguntas">
            <div>
                <h2>Preguntas</h2>
                
            </div>
            <div>

            </div>
        </div>
    </div>
</main>