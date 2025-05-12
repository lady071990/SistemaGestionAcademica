<!doctype html>
<?php
require_once 'logica/clasesGenericas/Footer.php';

session_start();
session_unset();
session_destroy();

$mensaje = '';
if (isset($_REQUEST['mensaje'])) $mensaje = $_REQUEST['mensaje'];
?>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>.::SIDGA - SISTEMA DE GESTION ACADÉMICO::.</title>
	<link rel="icon" type="image/png" href="layout/img/favicon.png" />
	<link rel="stylesheet" type="text/css" href="layout/css/main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,400;1,700&display=swap" rel="stylesheet">
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
	<main class="as-content">
		<?php
		if ($mensaje) {
		?>
			<div class="as-msg-error"><span><?= $mensaje ?></span></div>
		<?php } ?>
		<div>
			<form class="as-form" name="formulario" method="post" action="control/validar.php" autocomplete="off">
				<div class="as-form-avatar">
					<img src="layout/img/avatar.png" alt="logo">
				</div>
				<div class="as-form-margin">
					<h2>SISTEMA DE GESTIÓN ACADÉMICO</h2>
					<h4>Iniciar sesión</h4>
					<div class="as-form-fields">
						<div class="as-form-input">
							<label for="usuario">Usuario</label>
							<input type="text" name="usuario" id="usuario" placeholder="Ingresar usuario">
						</div>

						<div class="as-form-input">
							<label for="clave">Clave</label>
							<input type="password" name="clave" id="clave" placeholder="Ingresar clave">
						</div>
					</div>
					<div class="as-form-button">
						<button class="as-color-btn-red" type="submit">
							Ingresar
						</button>
					</div>
				</div>
			</form>
		</div>
	</main>

	<?php
	$footer = Footer::showFooter();
	echo $footer;
	?>
</body>

</html>