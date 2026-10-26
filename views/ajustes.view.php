<?php include __DIR__ . '/layout/header.php'; ?>



    <div id="contenedorAjustes">
        <?php include __DIR__ . '/layout/nav.php'; ?>
        <div id="tarjeta">
            <div id="d1">
                <img src="assets/images/Iconos/Editar.png" alt="Editar">
                <h2>Editar datos</h2>
            </div>

            <form action="index.php?controller=AjustesController&accion=editarDatos" method="post">
                <input type="text" name="apellidos" placeholder="Nuevos Apellidos">
                <input type="text" name="nombre" placeholder="Nuevo Nombre">
                <input type="password" name="contrasenna" placeholder="Nueva Contraseña">
                <input type="text" name="nombreUsuario" placeholder="Nuevo username">

                <div id="d2">
                    <input type="submit" value="Aceptar cambios">
                    <a href="index.php">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

<?php include __DIR__ . '/layout/footer.php'; ?>