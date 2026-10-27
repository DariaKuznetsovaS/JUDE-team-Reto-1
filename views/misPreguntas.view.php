<?php include __DIR__ . '/layout/header.php'; 
$usuario=$_SESSION["id_usuario"];?>


<div id="perfil">

    <?php include __DIR__ . '/layout/nav.php'; ?>

    <main>
        <div id="datosUsuario">
            <img src="assets/images/Iconos/User.png" alt="Foto de perfil">

            <div id="infoUsuario">
                <h1><?= $usuario->nombre_usuario ?></h1>
                <h3><?= $usuario->nombre . ' ' . $usuario->apellidos ?></h3>
                <p><?= $usuario->tipo ?></p>
            </div>

            <div id="accionesUsuario">
                <a href="index.php?controller=AjustesController&accion=ajustes">
                    <img src="assets/images/Iconos/Editar.png" alt="Editar datos">
                </a>
                <a href="index.php?controller=UsuarioController&accion=cerrarSesion">
                    <img src="assets/images/Iconos/CerrarSesion.png" alt="Cerrar sesión">
                </a>
            </div>
        </div>

        <a href="index.php?controller=PreguntaController&accion=publicar" id="botonPublicar">
            + Publicar nueva pregunta
        </a>

        <div id="listados">

            <div class="columna">
                <h3>Preguntas</h3>

                <?php foreach ($preguntas as $pregunta): ?>
                    <div class="preguntaCard">
                        <div class="cabeceraCard">
                            <h4><?= $pregunta->titulo ?></h4>
                            <p><?= $pregunta->fecha_creacion ?></p>
                        </div>
                        <div class="contenidoCard">
                            <img src="<?=$pregunta->imagen ?? 'assets/images/Iconos/SinImagen.png'?>" alt="Imagen de la pregunta">
                            <p><?= $pregunta->descripcion ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="columna">
                <h3>Respuestas</h3>

                <?php foreach ($respuestas as $respuesta): ?>
                    <div class="respuestaCard">
                        <div class="cabeceraCard">
                            <h4><?= $usuario->nombre_usuario ?></h4>
                            <p><?= $respuesta->fecha ?></p>
                        </div>
                        <div class="contenidoCard">
                            <img src="<?=$respuesta->imagen ?? 'assets/images/Iconos/SinImagen.png'?>" alt="Imagen de la respuesta">
                            <p><?= $respuesta->texto ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </main>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>