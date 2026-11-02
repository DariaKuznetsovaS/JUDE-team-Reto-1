<?php include __DIR__ . '/layout/header.php'; ?>

<div class="contenedorPrincipal">

    <?php include __DIR__ . '/layout/nav.php'; ?>

    <main class="formularioPregunta">

        <h1>Editar respuesta</h1>

        <form action="index.php?controller=RespuestaController&accion=actualizar"
              method="POST"
              enctype="multipart/form-data">

            <input type="hidden"
                   name="id_respuesta"
                   value="<?= (int) $respuesta['id_respuesta'] ?>">

            <div class="campoFormulario">
                <label for="texto">Texto de la respuesta</label>

                <textarea id="texto"
                          name="texto"
                          required><?= htmlspecialchars($respuesta['texto']) ?></textarea>
            </div>

            <div class="campoFormulario">

                <label>Archivo adjunto actual</label>

                <?php if (!empty($respuesta['adjunto_ruta'])): ?>

                    <p>
                        <a href="<?= htmlspecialchars($respuesta['adjunto_ruta']) ?>"
                           target="_blank"
                           rel="noopener noreferrer">
                            <?= htmlspecialchars($respuesta['adjunto_nombre'] ?? 'Ver archivo adjunto') ?>
                        </a>
                    </p>

                <?php else: ?>

                    <p>Esta respuesta no tiene ningún archivo adjunto.</p>

                <?php endif; ?>

                <label for="adjunto">Cambiar archivo adjunto</label>

                <input type="file"
                       id="adjunto"
                       name="adjunto"
                       accept=".pdf,.png,.jpg,.jpeg,.txt,.doc,.docx,.xlsx">

                <p>Si no seleccionas otro archivo, se conservará el actual.</p>

            </div>

            <div class="accionesFormulario">

                <button type="submit">Guardar cambios</button>

                <a href="index.php?controller=PreguntaController&accion=index&id=<?= (int) $respuesta['id_pregunta'] ?>">
                    Cancelar
                </a>

            </div>

        </form>

    </main>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
