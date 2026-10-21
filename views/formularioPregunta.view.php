
<?php include __DIR__ . '/layout/header.php'; ?>
<?php include __DIR__ . '/layout/nav.php'; ?>

<main class="publicarPregunta">

    <form action="index.php?controller=PreguntaController&accion=store"
          method="POST"
          enctype="multipart/form-data">

        <div class="headPregunta">
            <img src="assets/images/Iconos/Subir.png" alt="">
            <h1>Publicar pregunta</h1>
        </div>

        <div class="infoPregunta">
            <input type="text"
                   name="titulo"
                   placeholder="Título"
                   required>

            <textarea name="descripcion"
                      placeholder="Descripción"
                      required></textarea>
                      
            <select name="tipo" required>
                <option value="" disabled selected>Selecciona un tema</option>
                <option value="aerodinamica">Aerodinámica</option>
                <option value="materiales">Materiales</option>
                <option value="motores">Motores</option>
                <option value="software">Software</option>
                <option value="procesos">Procesos</option>
                <option value="diseño">Diseño</option>
                <option value="otros">Otros</option>
            </select>
        </div>

        <div class="botonesPregunta">

            <label for="adjuntoPregunta" class="botonAdjuntar">
                Adjuntar
                <span aria-hidden="true">📎</span>
            </label>

            <input type="file"
                   name="adjunto"
                   id="adjuntoPregunta"
                   class="inputArchivo">

            <button type="submit">Publicar</button>

        </div>

    </form>

</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
