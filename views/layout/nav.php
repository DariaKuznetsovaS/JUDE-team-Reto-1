
<?php if (isset($_SESSION["id_usuario"])): ?>

    <nav class="menuLateral">

        <a href="index.php" class="navInicio opcionNav">
            <img src="assets/images/Iconos/HomeClaro.png" alt="">
            <span>Inicio</span>
        </a>

        <a href="index.php?controller=UsuarioController&accion=misPreguntas"
           class="navMisPreguntas opcionNav">
            <img src="assets/images/Iconos/PreguntaClaro.png" alt="">
            <span>Mis Preguntas/Respuestas</span>
        </a>

    <div class="navMisFavoritos">

        <img src="assets/images/Iconos/FavoritoClaro.png">
        <a href="index.php?controller=FavoritosController&accion=favoritos"
           class="opcionNav">
            <span>Mis Favoritos</span>
        </a>

        <a href="index.php?controller=AjustesController&accion=ajustes"
           class="navAjustes opcionNav">
            <img src="assets/images/Iconos/AjustesClaro.png" alt="">
            <span>Ajustes</span>
        </a>

        <!-- SOLO VISIBLE PARA ADMINS -->
        <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] === "admin"): ?>

            <a href="index.php?controller=UsuarioController&accion=listar"
               class="navUsuarios opcionNav">
                <img src="assets/images/Iconos/UsuariosClaro.png" alt="">
                <span>Usuarios</span>
            </a>

        <?php endif; ?>

        <a href="index.php?controller=UsuarioController&accion=logout"
           class="navSalir opcionNav">
            <img src="assets/images/Iconos/SalirClaro.png" alt="">
            <span>Salir</span>
        </a>

    </nav>

<?php endif; ?>
