<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesion</title>
    <link rel="stylesheet" href="assets/css/perfil.css">
</head>
<body>
    <main class="login">
    <div id="d1">
        <img src="assets/images/Iconos/UserLogin.png" alt="No se pudo cargar esta imagen">
        <h2>Login</h2>
    </div>
    <?php if (!empty($_SESSION['error_login'])): ?>
        <h1 class="error" style="color: red;"><?=$_SESSION['error_login'] ?></h1><br>
        <?php unset($_SESSION['error_login']); ?>
    <?php endif; ?>
        <form action="index.php?controller=UsuarioController&accion=iniciarSesion" method="post">
        <input type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Nombre de Usuario" required>
        <input type="password" name="contrasenna" id="contrasenna" placeholder="Contraseña" required>
        <input type="submit" value="Iniciar Sesión">
    </form>

    <div id="d2">
        <p>¿Aún no tienes cuenta?</p>
        <a href="index.php?controller=UsuarioController&accion=registro">Darse de alta</a>
    </div>
</main>
</body>
</html>