<?php include __DIR__ . '/layout/header.php'; ?>

<div class="contenedorPrincipal">

    <?php include __DIR__ . '/layout/nav.php'; ?>

    <main class="formularioPregunta">

        <h1>Editar pregunta</h1>

        <form action="index.php?controller=PreguntaController&accion=actualizar"
              method="POST"
              enctype="multipart/form-data">

            <input type="hidden"
                   name="id_pregunta"
                   value="<?= (int) $pregunta['id_pregunta'] ?>">

            <div class="campoFormulario">
                <label for="titulo">Título</label>

                <input type="text"
                       id="titulo"
                       name="titulo"
                       value="<?= htmlspecialchars($pregunta['titulo']) ?>"
                       required>
            </div>

            <div class="campoFormulario">
                <label for="descripcion">Descripción</label>

                <textarea id="descripcion"
                          name="descripcion"
                          required><?= htmlspecialchars($pregunta['descripcion']) ?></textarea>
                <label for="adjunto">Imagen de la pregunta</label>

                <?php if (!empty($pregunta['imagen'])): ?>
                    <img
                        src="<?= htmlspecialchars($pregunta['imagen']) ?>"
                        alt="Imagen actual de la pregunta"
                        class="imagenActualPregunta"
                    >
                <?php endif; ?>

                <input
                    type="file"
                    id="adjunto"
                    name="adjunto"
                    accept="image/*"
                >

                <p>Si no seleccionas otra imagen, se conservará la actual.</p>
            </div>

            <div class="campoFormulario">
                <label for="tipo">Tema</label>

                <select id="tipo" name="tipo" required>
                    <?php
                    $tipos = [
                        'aerodinamica' => 'Aerodinámica',
                        'materiales' => 'Materiales',
                        'motores' => 'Motores',
                        'software' => 'Software',
                        'procesos' => 'Procesos',
                        'diseño' => 'Diseño',
                        'otros' => 'Otros'
                    ];
                    ?>

                    <?php foreach ($tipos as $valor => $nombre): ?>
                        <option value="<?= htmlspecialchars($valor) ?>"
                            <?= $pregunta['tipo'] === $valor ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="accionesFormulario">
                <button type="submit">Guardar cambios</button>

                <a href="index.php?controller=PreguntaController&accion=index&id=<?= (int) $pregunta['id_pregunta'] ?>">
                    Cancelar
                </a>
            </div>

        </form>

    </main>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
