<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="assets/css/registro.css">
</head>
<body>
    <div id="d1">
        <img src="assets/images/Iconos/UsuarioNuevo.png" alt="No se pudo cargar esta imagen">
        <h2>Registro</h2>
    </div>
    <form action="index.php?controller=UsuarioController&accion=registrarse" method="post">
        <input type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Nombre de Usuario" required >
                <small id="msgUsuario" aria-live="polite"></small>

        <input type="text" name="nombre" id="nombre" placeholder="Nombre" required >
        <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required >
        <input type="password" name="contrasenna" id="contrasenna" placeholder="Contraseña" required >
        <input type="submit" value="Iniciar Sesión">
    </form>

    
</body>
</html>