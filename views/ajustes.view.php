<?php include __DIR__ . '/layout/header.php'; ?>

<link rel="stylesheet" href="assets/css/ajustes.css">
<div class="contenedorPrincipal">

    <?php include __DIR__ . '/layout/nav.php'; ?>

<main class="formulario">

    <div class="cabecera">
        <img src="assets/images/Iconos/Editar.png" alt="Editar">
        <h1>Editar datos</h1>
    </div>

    <?php if (!empty($_SESSION['errores_ajustes'])): ?>
        <div class="errores">
            <?php foreach ($_SESSION['errores_ajustes'] as $error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>

            <?php unset($_SESSION['errores_ajustes']); ?>
        </div>
    <?php endif; ?>

    <form id="formAjustes"
          action="index.php?controller=AjustesController&accion=editarDatos"
          method="POST">

        <div class="camposForm">

            <input
                type="text"
                name="apellidos"
                placeholder="<?= htmlspecialchars($usuario->apellidos) ?>"
            >

            <input
                type="text"
                name="nombre"
                placeholder="<?= htmlspecialchars($usuario->nombre) ?>"
            >

            <input
                type="password"
                name="contrasenna"
                id="contrasenna"
                placeholder="Nueva contraseña"
            >

            <p id="msgContrasenna"></p>

            <input
                type="text"
                name="nombreUsuario"
                id="nombreUsuario"
                placeholder="<?= htmlspecialchars($usuario->nombre_usuario) ?>"
            >

            <p id="msgUsuario"></p>

            <?php if ($usuario->tipo === "admin"): ?>

                <select name="tipo" id="tipo" required>
                    <option value="" disabled selected>Tipo de usuario</option>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>

                <p id="msgTipo"></p>

            <?php else: ?>

                <select name="tipo" disabled>
                    <option value="user" selected>Usuario</option>
                    <option value="admin">Administrador</option>
                </select>

            <?php endif; ?>

        </div>

        <div class="botonesForm">

            <button type="submit">
                Aceptar cambios
            </button>

            <button type="button" onclick="window.location.href='index.php'">
                Cancelar
            </button>

        </div>

    </form>
</main>



</div>

<script src="assets/scripts/ajustes.js"></script>

<?php include __DIR__ . '/layout/footer.php'; ?>
