
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="contenedorPrincipal">

    <?php include __DIR__ . '/layout/nav.php'; ?>

    <main class="detallePregunta">

        <?php if (!empty($pregunta)): ?>

            <div class="detalleCabecera">

                <div class="cabecera">
                    <h1><?= htmlspecialchars($pregunta['titulo']) ?></h1>
                    <p><?= date('d/m/Y', strtotime($pregunta['fecha_creacion'])) ?></p>
                </div>

                <div class="infoPregunta">
                    <h2><?= htmlspecialchars($pregunta['tipo']) ?></h2>
                    <p><?= htmlspecialchars($pregunta['descripcion']) ?></p>
                </div>

                <?php if (!empty($pregunta['imagen_ruta'])): ?>
                    <img class="imagenPregunta"
                         src="<?= htmlspecialchars($pregunta['imagen_ruta']) ?>"
                         alt="Imagen de la pregunta">
                <?php else: ?>
                    <img class="imagenPregunta"
                         src="assets/images/Iconos/SinImagen.png"
                         alt="Imagen por defecto">
                <?php endif; ?>

            </div>

      
<section class="respuestas">
    <h2>Respuestas</h2>

    <?php if (!empty($respuestas)): ?>

        <?php foreach ($respuestas as $respuesta): ?>

            <article class="respuestaCard">

                <div class="cabeceraRespuesta">

                    <div class="infoAutorRespuesta">
                       
                        <a class="nombreUsuarioRespuesta"
                        href="index.php?controller=UsuarioController&accion=perfil&id=<?= urlencode($respuesta['id_usuario']) ?>">
                            <?= htmlspecialchars($respuesta['nombre_usuario']) ?>
                        </a>


                        <span class="fechaRespuesta">
                            <?= date('d/m/Y H:i', strtotime($respuesta['fecha'])) ?>
                        </span>

                    </div>

                    <div class="favoritosRespuesta">
                        <button type="button"
                                class="botonFavorito"
                                aria-label="Añadir respuesta a favoritos">
                            ♡
                        </button>

                        <span class="numeroFavoritos">0</span>
                    </div>

                </div>

                <div class="contenidoRespuesta">

                    <p class="textoRespuesta">
                        <?= nl2br(htmlspecialchars($respuesta['texto'])) ?>
                    </p>

                    <?php if (!empty($respuesta['imagen_ruta'])): ?>
                        <img class="imagenRespuesta"
                             src="<?= htmlspecialchars($respuesta['imagen_ruta']) ?>"
                             alt="Imagen adjunta a la respuesta">
                    <?php endif; ?>

                </div>

            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <p class="sinRespuestas">Todavía no hay respuestas. ¡Sé la primera persona en responder!</p>

    <?php endif; ?>
</section>


        <?php else: ?>
            <p>La pregunta no existe.</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['id_usuario']) && !empty($pregunta)): ?>

            <div class="formularioRespuesta">
                <h2>Añade tu respuesta:</h2>

                <form action="index.php?controller=RespuestaController&accion=store"
                      method="POST"
                      enctype="multipart/form-data">

                    <input type="hidden"
                           name="id_pregunta"
                           value="<?= htmlspecialchars($pregunta['id_pregunta']) ?>">

                    <textarea name="texto"
                              placeholder="Escribe tu respuesta..."
                              required></textarea>

                    <div class="accionesRespuesta">
                        <input type="file" name="adjunto" id="adjunto">
                        <button type="submit">Publicar</button>
                    </div>

                </form>
            </div>

        <?php endif; ?>

    </main>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
