
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="contenedorPrincipal">

    <?php include __DIR__ . '/layout/nav.php'; ?>

    <main class="detallePregunta">

        <?php if (!empty($pregunta)): ?>

            <?php
                $usuarioSesion = $_SESSION['id_usuario'] ?? null;

                $idUsuarioSesion = is_object($usuarioSesion)
                    ? ($usuarioSesion->id_usuario ?? 0)
                    : ($usuarioSesion ?? 0);

                $tipoUsuarioSesion = is_object($usuarioSesion)
                    ? ($usuarioSesion->tipo ?? '')
                    : '';

                $esAutor = (int) $idUsuarioSesion === (int) $pregunta['id_usuario'];
                $esAdmin = $tipoUsuarioSesion === 'admin';
            ?>

            <!-- Información principal de la pregunta -->
            <div class="contenidoPregunta">

                <div class="informacionPregunta">

                    <h1>
                        <?= htmlspecialchars($pregunta['titulo']) ?>
                    </h1>

                    <p class="temaPregunta">
                        <?= htmlspecialchars($pregunta['tipo']) ?>
                    </p>

                    <p class="autorPregunta">
                        Publicada por:
                        <a href="index.php?controller=UsuarioController&accion=verPerfil&id=<?= (int) $pregunta['id_usuario'] ?>">
                            <?= htmlspecialchars($pregunta['nombre_usuario'] ?? '') ?>
                        </a>
                    </p>

                    <p class="fechaPregunta">
                        <?= date('d/m/Y', strtotime($pregunta['fecha_creacion'])) ?>
                    </p>

                    <div class="descripcionPregunta">
                        <?= nl2br(htmlspecialchars($pregunta['descripcion'])) ?>
                    </div>

                </div>

                <!-- Imagen y acciones a la derecha -->
                <div class="lateralPregunta">

                    <?php if (!empty($pregunta['imagen'])): ?>

                        <img
                            class="imagenPregunta"
                            src="<?= htmlspecialchars($pregunta['imagen']) ?>"
                            alt="Imagen de la pregunta"
                        >

                    <?php else: ?>

                        <img
                            class="imagenPregunta"
                            src="assets/images/Iconos/SinImagen.png"
                            alt="Imagen por defecto"
                        >

                    <?php endif; ?>

                    <?php if ($esAutor || $esAdmin): ?>

                        <div class="accionesPregunta">

                            <a
                                href="index.php?controller=PreguntaController&accion=editar&id=<?= (int) $pregunta['id_pregunta'] ?>"
                                title="Editar pregunta"
                            >
                                <img
                                    src="assets/images/Iconos/Editar.png"
                                    alt="Editar pregunta"
                                >
                            </a>

                            <form
                                action="index.php?controller=PreguntaController&accion=borrar"
                                method="POST"
                                onsubmit="return confirm('¿Seguro que quieres borrar esta pregunta?');"
                            >

                                <input
                                    type="hidden"
                                    name="id_pregunta"
                                    value="<?= (int) $pregunta['id_pregunta'] ?>"
                                >

                                <button type="submit" title="Borrar pregunta">
                                    <img
                                        src="assets/images/Iconos/Trash.png"
                                        alt="Borrar pregunta"
                                    >
                                </button>

                            </form>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <!-- Respuestas -->
            <section class="respuestas">

                <h2>Respuestas</h2>

                <?php if (!empty($respuestas)): ?>

                    <?php foreach ($respuestas as $respuesta): ?>

                        <article class="respuestaCard">

                            <div class="cabeceraRespuesta">

                                <div class="infoAutorRespuesta">

                                    <a
                                        class="nombreUsuarioRespuesta"
                                        href="index.php?controller=UsuarioController&accion=perfil&id=<?= (int) $respuesta['id_usuario'] ?>"
                                    >
                                        <?= htmlspecialchars($respuesta['nombre_usuario']) ?>
                                    </a>

                                    <span class="fechaRespuesta">
                                        <?= date('d/m/Y H:i', strtotime($respuesta['fecha'])) ?>
                                    </span>

                                </div>

                                <div class="favoritosRespuesta">

                                    <?php if (isset($_SESSION['id_usuario'])): ?>

                                        <button
                                            type="button"
                                            class="botonFavorito <?= !empty($respuesta['usuario_favorito']) ? 'favoritoActivo' : '' ?>"
                                            data-id-respuesta="<?= (int) $respuesta['id_respuesta'] ?>"
                                            aria-label="Añadir o quitar de favoritos"
                                            aria-pressed="<?= !empty($respuesta['usuario_favorito']) ? 'true' : 'false' ?>"
                                        >
                                            <?= !empty($respuesta['usuario_favorito']) ? '♥' : '♡' ?>
                                        </button>

                                    <?php else: ?>

                                        <span class="botonFavorito" aria-hidden="true">♡</span>

                                    <?php endif; ?>

                                    <span class="numeroFavoritos">
                                        <?= (int) ($respuesta['total_favoritos'] ?? 0) ?>
                                    </span>

                                </div>

                            </div>

                            <div class="contenidoRespuesta">

                                <p class="textoRespuesta">
                                    <?= nl2br(htmlspecialchars($respuesta['texto'])) ?>
                                </p>

                                <?php if (!empty($respuesta['imagen_ruta'])): ?>

                                    <img
                                        class="imagenRespuesta"
                                        src="<?= htmlspecialchars($respuesta['imagen_ruta']) ?>"
                                        alt="Imagen adjunta a la respuesta"
                                    >

                                <?php endif; ?>

                            </div>

                        </article>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="sinRespuestas">
                        Todavía no hay respuestas. ¡Sé la primera persona en responder!
                    </p>

                <?php endif; ?>

            </section>

            <!-- Formulario para añadir una respuesta -->
            <?php if (isset($_SESSION['id_usuario'])): ?>

                <div class="formularioRespuesta">

                    <h2>Añade tu respuesta:</h2>

                    <form
                        action="index.php?controller=RespuestaController&accion=store"
                        method="POST"
                        enctype="multipart/form-data"
                    >

                        <input
                            type="hidden"
                            name="id_pregunta"
                            value="<?= (int) $pregunta['id_pregunta'] ?>"
                        >

                        <textarea
                            name="texto"
                            placeholder="Escribe tu respuesta..."
                            required
                        ></textarea>

                        <div class="accionesRespuesta">

                            <input
                                type="file"
                                name="adjunto"
                                id="adjunto"
                            >

                            <button type="submit">Publicar</button>

                        </div>

                    </form>

                </div>

            <?php endif; ?>

        <?php else: ?>

            <p>La pregunta no existe.</p>

        <?php endif; ?>

    </main>

</div>

<script src="assets/scripts/favoritos.js" defer></script>

<?php include __DIR__ . '/layout/footer.php'; ?>
