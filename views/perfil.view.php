<?php include __DIR__ . '/layout/header.php'; ?>
<?php include __DIR__ . '/layout/nav.php'; ?>


<main class="perfil">
    <div class="userContainer">

        <img src="assets/images/Iconos/User.png" alt="Imagen usuario">
        <div class="datosUsuario">
            <h1><?= htmlspecialchars($usuario['nombre_usuario'] ?? '') ?></h1>

            <p> <?= htmlspecialchars(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? '')) ?></p>

            <p> <?= htmlspecialchars($usuario['tipo'] ?? '') ?></p>
        </div>

        <div class="enlaces">
            <a href="index.php?controller=UsuarioController&accion=editar&id=<?= $usuario['id_usuario'] ?>">
                <img src="assets/images/Iconos/Editar.png" alt="Editar perfil">
            </a>

            <a href="index.php?controller=UsuarioController&accion=logout">
                <img src="assets/images/Iconos/Salir.png" alt="">
            </a>
    </div>

    </div>


    <div class="preguntasRespuestasContainer">
        <a href="index.php?controller=PreguntaController&accion=crear" class="btnPublicar">+ Publicar nueva pregunta</a>
        <div class="misPreguntas">
            <div>
                <h2>Preguntas</h2>
                <?php if (!empty($preguntasUsuario)): ?>

                    <?php foreach ($preguntasUsuario as $pregunta): ?>

                        <a href="index.php?controller=PreguntaController&accion=index&id=<?=$pregunta["id_pregunta"]?>" class="enlacePregunta">
                        <div class="preguntaCard">

                            <div class="cabeceraCard">
                                <h2><?= htmlspecialchars($pregunta['titulo']) ?></h2>

                                <p class="cardFecha">
                                    <?= date('d/m/Y', strtotime($pregunta['fecha_creacion'])) ?>
                                </p>
                            </div>

                            <div class="contenidoCard">

                                <div class="imagenCard">
                                    <?php if (!empty($pregunta['imagen_ruta'])): ?>

                                        <img
                                            src="<?= htmlspecialchars($pregunta['imagen_ruta']) ?>"
                                            alt="Imagen de la pregunta">

                                    <?php else: ?>

                                        <img src="assets/images/Iconos/SinImagen.png" alt="Imagen de la pregunta">

                                    <?php endif; ?>
                                </div>

                                <div class="descCard">
                                    <p><?= htmlspecialchars($pregunta['descripcion']) ?></p>
                                </div>

                            </div>

                        </div>
                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No hay preguntas disponibles.</p>

                <?php endif; ?>
            </div>



            <div>
                <h2>Respuestas</h2>
                <?php if (!empty($respuestas)): ?>

                    <?php foreach ($respuestas as $respuesta): ?>

                        <div class="respuestaCard">

                            <div>
                                <p><?= htmlspecialchars($respuesta['texto']) ?></p>
                            </div>

                            <?php if (!empty($respuesta['imagen_ruta'])): ?>
                                <img
                                    src="<?= htmlspecialchars($respuesta['imagen_ruta']) ?>"
                                    alt="Imagen de la respuesta">
                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No hay respuestas disponibles.</p>

                <?php endif; ?>
            </div>
        </div>
    </div>
</main>


<?php

require "layout/footer.php";
?>