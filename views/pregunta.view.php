<?php

require "layout/header.php";

require_once "models/PreguntaModel.php";

require_once "models/RespuestaModel.php";

$id = $_GET['id_pregunta'] ?? null;
$pregunta = PreguntaModel::getById((int) $id);

?>

<main class="detallePregunta">

    <?php if(!empty($pregunta)): ?>
        <div class="cabecera"> <h1> <?= $pregunta['titulo'] ?> </h1> <p> <?= $pregunta['fecha'] ?> </p></div>

        <div class="infoPregunta">
            <h2> <?= $pregunta['tipo'] ?> </h2> <br>
            <p> <?= $pregunta['descripcion'] ?> </p> <br>
            <p> <?= $pregunta['autor'] ?> </p>
            <p> Estado: <?= $pregunta['estado'] ?> </p>
        </div>

        <img src="../assets/images/SinImagen.png" alt="Imagen por defecto">




    <?php if (!empty($respuestas)): ?>
        <h2>Respuestas:</h2>
        <?php foreach($respuestas as $respuesta): ?>
            <div><!--quitar el estio del div-->
                <div style = "display:flex; justify-content: space-between">
                <h2> <?= $respuesta['titulo']; ?> </h2>
                </div>

                <div>
                    <?php if(!empty($respuesta['imagen_ruta'])) : ?>
                        <img src="<?= $respuesta['imagen_ruta'] ?>" >
                    <?php else: ?>
                        <img src="../assets/images/SinImagen.png" alt="Imagen de la pregunta">
                    <?php endif; ?>
                    
                    <p> <?= $respuesta['texto'] ?> </p>
                </div>

            </div>
        <?php endforeach;?>

    <?php else: ?>

    <p>No hay respuestas disponibles.</p>

    <?php endif; ?>





</main>



<?php
require "layout/footer.php";
?>