
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
    <link rel="stylesheet" href="assets/css/perfil.css">
    <script src="assets/scripts/script.js" defer></script>
</head>
<body>
    <header>
        <a href="index.php">
    <img src="assets/images/logoAergibide.png" alt="Logo de Aergibide" class="logo">
        </a>

<!-- Barra de búsqueda: -->     
        <?php
        $esPaginaPrincipal = (
            !isset($_GET['controller'])
            || $_GET['controller'] === 'PrincipalController'
        ) && (
            !isset($_GET['accion'])
            || $_GET['accion'] === 'index'
        );
        ?>

        
        <?php if ($esPaginaPrincipal): ?>
            <form action="index.php" method="GET" class="buscadorHeader">

                <select name="categoria" aria-label="Filtrar por categoría">
                    <option value="">Todas las categorías</option>
                    <option value="aerodinamica" <?= ($_GET["categoria"] ?? "") === "aerodinamica" ? "selected" : "" ?>>Aerodinámica</option>
                    <option value="materiales" <?= ($_GET["categoria"] ?? "") === "materiales" ? "selected" : "" ?>>Materiales</option>
                    <option value="motores" <?= ($_GET["categoria"] ?? "") === "motores" ? "selected" : "" ?>>Motores</option>
                    <option value="software" <?= ($_GET["categoria"] ?? "") === "software" ? "selected" : "" ?>>Software</option>
                    <option value="procesos" <?= ($_GET["categoria"] ?? "") === "procesos" ? "selected" : "" ?>>Procesos</option>
                    <option value="diseño" <?= ($_GET["categoria"] ?? "") === "diseño" ? "selected" : "" ?>>Diseño</option>
                    <option value="otros" <?= ($_GET["categoria"] ?? "") === "otros" ? "selected" : "" ?>>Otros</option>
                </select>

                <input
                    type="search"
                    name="buscar"
                    placeholder="Buscar por título o descripción..."
                    value="<?= htmlspecialchars($_GET["buscar"] ?? "") ?>"
                >

                <button type="submit" aria-label="Buscar">
                    <img src="assets/images/Iconos/Buscar.png" alt="">
                </button>

            </form>
        <?php endif; ?>


        <div class="botonesSesion">

            <?php if (isset($_SESSION["id_usuario"])): ?>

                <a href="index.php?controller=UsuarioController&accion=perfil"
                   class="bPerfil">
                    <img src="assets/images/Iconos/User.png">
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
