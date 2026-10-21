
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="JUDE team">
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <title>AERGIBIDE, S.L.</title>
    <link rel="stylesheet" href="assets/css/principal.css">
</head>
<body>
    <header>
        <a href="index.php">
    <img src="assets/images/logoAergibide.png" alt="Logo de Aergibide" class="logo">
        </a>

        <input type="search" placeholder="Buscar por título, tema...">

        <div class="botonesSesion">

            <?php if (isset($_SESSION["id_usuario"])): ?>

                <a href="index.php?controller=usuario&accion=perfil"
                   class="bPerfil">
                    Mi perfil
                </a>

            <?php else: ?>

                <a href="index.php?controller=UsuarioController&accion=login"
                   class="bIniciarSesion">
                    Iniciar sesión
                </a>

                <a href="index.php?controller=UsuarioController&accion=registro"
                   class="bRegistrarse">
                    Registrarse
                </a>

            <?php endif; ?>

        </div>
    </header>
