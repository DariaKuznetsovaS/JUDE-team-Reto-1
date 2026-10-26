
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="contenedorPrincipal">
    <?php include __DIR__ . '/layout/nav.php'; ?>

<main class="listaPreguntas">

    <?php if (!empty($preguntas)): ?>

        <?php foreach ($preguntas as $pregunta): ?>

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

</main>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
