<?php if (isset($_SESSION["id_usuario"])): ?>

    <nav class="menuLateral">

    <div class="navInicio">
        <img src="../assets/images/Iconos/Home.png">
        <a href="index.php" class="opcionNav">
            Inicio
        </a>
    </div>

    <div class="navMisPreguntas">
        <img src="../assets/images/Iconos/Pregunta.png">
        <a href="index.php?controller=usuario&accion=misPreguntas"
           class="opcionNav">
            Mis Preguntas/Respuestas
        </a>
    </div>

    <div class="navMisFavoritos">
        <img src="../assets/images/Iconos/Favorito.png">
        <a href="index.php?controller=usuario&accion=favoritos"
           class="opcionNav">
            Mis Favoritos
        </a>
    </div>
    
    <div class="navAjustes">
        <img src="../assets/images/Iconos/Ajustes.png">
        <a href="index.php?controller=usuario&accion=ajustes"
           class="opcionNav">
            Ajustes
        </a>
    </div>

    <!-- SOLO VISIBLE PARA ADMINS: -->
    <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] === "admin"): ?>

    <div class="navUsuarios">
        <img src="assets/images/Iconos/Usuarios.png" alt="">
            <a href="index.php?controller=usuario&accion=listar"
                class="opcionNav">
                Usuarios
            </a>
    </div>

        <?php endif; ?>

    <div class="navSalir">
        <img src="../assets/images/Iconos/Salir.png">
        <a href="index.php?controller=usuario&accion=logout"
           class="opcionNav">
            Salir
        </a>
    </div>

    </nav>

<?php endif; ?>