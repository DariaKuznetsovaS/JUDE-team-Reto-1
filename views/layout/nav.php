<?php if (isset($_SESSION["id_usuario"])): ?>

    <nav class="menuLateral">

    <div class="navInicio">
        <img src="assets/images/Iconos/HomeClaro.png">
        <a href="index.php" class="opcionNav">
            Inicio
        </a>
    </div>

    <div class="navMisPreguntas">
<<<<<<< HEAD
        <img src="assets/images/Iconos/Pregunta.png">
        <a href="index.php?controller=UsuarioController&accion=misPreguntas"
=======
        <img src="assets/images/Iconos/PreguntaClaro.png">
        <a href="index.php?controller=usuario&accion=misPreguntas"
>>>>>>> e7973b00b3ec60cf0fd7d3f693f01e90763b43c8
           class="opcionNav">
            Mis Preguntas/Respuestas
        </a>
    </div>

    <div class="navMisFavoritos">
<<<<<<< HEAD
        <img src="assets/images/Iconos/Favorito.png">
        <a href="index.php?controller=UsuarioController&accion=favoritos"
=======
        <img src="assets/images/Iconos/FavoritoClaro.png">
        <a href="index.php?controller=usuario&accion=favoritos"
>>>>>>> e7973b00b3ec60cf0fd7d3f693f01e90763b43c8
           class="opcionNav">
            Mis Favoritos
        </a>
    </div>
    
    <div class="navAjustes">
<<<<<<< HEAD
        <img src="assets/images/Iconos/Ajustes.png">
        <a href="index.php?controller=AjustesController&accion=ajustes"
=======
        <img src="assets/images/Iconos/AjustesClaro.png">
        <a href="index.php?controller=usuario&accion=ajustes"
>>>>>>> e7973b00b3ec60cf0fd7d3f693f01e90763b43c8
           class="opcionNav">
            Ajustes
        </a>
    </div>

    <!-- SOLO VISIBLE PARA ADMINS: -->
    <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] === "admin"): ?>

    <div class="navUsuarios">
<<<<<<< HEAD
        <img src="assets/images/Iconos/Usuarios.png" alt="">
            <a href="index.php?controller=UsuarioController&accion=listar"
=======
        <img src="assets/images/Iconos/UsuariosClaro.png" alt="">
            <a href="index.php?controller=usuario&accion=listar"
>>>>>>> e7973b00b3ec60cf0fd7d3f693f01e90763b43c8
                class="opcionNav">
                Usuarios
            </a>
    </div>

        <?php endif; ?>

    <div class="navSalir">
<<<<<<< HEAD
        <img src="assets/images/Iconos/Salir.png">
        <a href="index.php?controller=UsuarioController&accion=logout"
=======
        <img src="assets/images/Iconos/SalirClaro.png">
        <a href="index.php?controller=usuario&accion=logout"
>>>>>>> e7973b00b3ec60cf0fd7d3f693f01e90763b43c8
           class="opcionNav">
            Salir
        </a>
    </div>

    </nav>

<?php endif; ?>