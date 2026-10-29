<?php 


require "layout/header.php";

?>


<div class="contenedorPrincipal">
    <?php include __DIR__ . '/layout/nav.php'; ?>


    


    <div class="favsContainer">
        
        <div class="misPreguntas">
            <div>
                <h2> <img src="assets/images/Iconos/Favorito.png" alt=""> Respuestas favoritas</h2>
                <?php if (!empty($respuestasFavoritas)): ?>

                        <?php foreach ($respuestasFavoritas as $fav): ?>

                            <a href="index.php?controller=PreguntaController&accion=index&id=<?= $fav['id_pregunta'] ?>" class="enlacePregunta">
                                <div class="preguntaCard">

                                    <div class="cabeceraCard">
                                        <h2><?= htmlspecialchars($fav['autor']) ?></h2>
                                        <p class="cardFecha"><?= date('d/m/Y', strtotime($fav['fecha'])) ?></p>
                                    </div>

                                    <div class="contenidoCard">
                                        <div class="imagenCard">
                                            <?php if (!empty($fav['imagen_ruta'])): ?>
                                                <img src="<?= htmlspecialchars($fav['imagen_ruta']) ?>" alt="Imagen de la respuesta">
                                            <?php else: ?>
                                                <img src="assets/images/SinImagen.png" alt="Sin imagen">
                                            <?php endif; ?>
                                        </div>

                                        <div class="descCard">
                                            <p><?= htmlspecialchars($fav['descripcion']) ?></p>
                                        </div>
                                    </div>

                                </div>
                            </a>

                        <?php endforeach; ?>

                <?php else: ?>

                    <p>No hay respuestas favoritas.</p>

                <?php endif; ?>
            </div>


            
        </div>
    </div>
</div>

</div>


<?php

require "layout/footer.php";
?>