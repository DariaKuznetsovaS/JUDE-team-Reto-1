<?php

require "layout/header.php";

require_once "models/PreguntaModel.php";

?>

<main>
    <?php if (!empty($preguntas)): ?>
        <?php foreach($preguntas as $pregunta): ?>
            <div>
                <div style = "display:flex; justify-content: space-between">
                <h2> <?= $pregunta['titulo']; ?> </h2>
                </div>

                <div>
                    <?php if(!empty($pregunta['imagen_ruta'])) : ?>
                        <img src="<?= $pregunta['imagen_ruta'] ?>" >
                    <?php else: ?>
                        <img src="../assets/images/SinImagen.png" alt="Imagen de la pregunta">
                    <?php endif; ?>
                    
                    <p> <?= $pregunta['descripcion'] ?> </p>
                </div>

            </div>
        <?php endforeach;?>

    <?php else: ?>

    <p>No hay preguntas disponibles.</p>

    <?php endif; ?>

</main>



<?php
require "layout/footer.php";
?>