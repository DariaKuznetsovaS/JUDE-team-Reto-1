
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
                        <div class="respuestaCard">
                            <p><?= htmlspecialchars($respuesta['texto']) ?></p>

                            <?php if (!empty($respuesta['imagen_ruta'])): ?>
                                <img src="<?= htmlspecialchars($respuesta['imagen_ruta']) ?>"
                                     alt="Imagen de la respuesta">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <p>No hay respuestas disponibles.</p>
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
