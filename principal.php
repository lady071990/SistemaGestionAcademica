<?php
include 'logica/clasesGenericas/Librerias.php';

date_default_timezone_set('America/Bogota');
session_start();
if (!isset($_SESSION['usuario'])) header('location: index.php?mensaje=Acceso no autorizado');
$USUARIO = '';
$USUARIO = unserialize($_SESSION['usuario']);
$roles = Rol::getListaEnObjetos("id={$USUARIO->getRolId()}", null)[0];


?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al software académico - <?= $USUARIO ?> (<?= $USUARIO->getRolNombre() ?>)</title>
    <link rel="icon" type="image/png" href="layout/img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="layout/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
    <header>
        <nav class="as-nav-header">
            <div class="as-first-div">
                <img class="as-logo" src="layout/img/logoDU.png" alt="logo" />
            </div>
            <div class="as-information">
                <h3 class="as-title">SIDGA - SISTEMA INTEGRAL DE DOCENCIA Y GESTIÓN ACADÉMICA</h3>
                <p>Plataforma académica del Hospital Universitario Departamental de Nariño, comprometida con la excelencia en la formación 
                    de profesionales de la salud. Nuestro sistema integra docencia, investigación y servicio, bajo el principio
                    <span>&quot;FORMANDO LOS PROFESIONALES DE SALUD QUE NARIÑO NECESITA&quot;</span>.</p>
            </div>
        </nav>
    </header>

    <span class="as-nav-bar" id="as-menu-btn"><i class="fas fa-bars"></i> <span>Menú</span></span>
    <nav class="as-main-nav">
        <ul class="as-menu" id="as-menu">
            <li class="menu__item"><a href="principal.php?CONTENIDO=layout/inicio.php" class="as-menu__link">Inicio</a></li>
            <?php
            $menu = MenuLista::getMenu($USUARIO->getRolId());
            echo $menu;
            ?>
            <li class="menu__item as-dropdown-submenu"><a href="#" class="as-menu__link as-submenu-btn"> <span>Perfil <?php echo $roles->getNombre(); ?> </span> <i class="fas fa-chevron-down"></i></a>
                <ul class="as-submenu">
                    <li class="menu__item"><a href="principal.php?CONTENIDO=layout/components/usuario/form-usuario.php&accion=Modificar&id=<?= $USUARIO->getId() ?>" class="as-menu__link as-submenu-color">Actualizar Datos</a></li>
                    <li class="menu__item"><a href="index.php" class="as-menu__link as-submenu-color">Cerrar sesión</a></li>
                </ul>
            </li>
    </nav>


    <main class="as-layout">
        <?php include $_REQUEST['CONTENIDO']; ?>
    </main>

    <?php
    $footer = Footer::showFooter();
    echo $footer;
    ?>

    <script src="layout/js/main.js"></script>
</body>

</html>