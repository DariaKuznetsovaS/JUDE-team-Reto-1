<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XXXX</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div id="d1">
        <img src="assets/images/Iconos/UserLogin.png" alt="No se pudo cargar esta imagen">
        <h2>Login</h2>
    </div>
    <form action="index.php?controller=UsuarioController&accion=iniciarSesion" method="post">
        <input type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Nombre de Usuario" required>
        <input type="password" name="contrasenna" id="contrasenna" placeholder="Contraseña" required>
        <input type="submit" value="Iniciar Sesión">
    </form>

    <div id="d2">
        <p>¿Aún no tienes cuenta?</p>
        <a href="index.php?controller=UsuarioController&accion=registro">Darse de alta</a>
    </div>
</body>
</html>