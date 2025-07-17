<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso no autorizado');
    exit;
}

$USUARIO = unserialize($_SESSION['usuario']);

// Cargar la institución
$instituciones = InfoDocencia::getListaEnObjetos('id=1', null);
$institution = !empty($instituciones) ? $instituciones[0] : null;

// Cargar el año escolar activo
$aniosEscolares = AnioEscolar::getListaEnObjetos('estado=1', null);
$anioEscolar = !empty($aniosEscolares) ? $aniosEscolares[0] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDGA - Gestión Documental</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .header {
            background-color: #0056b3;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header p {
            margin: 5px 0 0;
            font-style: italic;
            font-size: 14px;
        }
        
        .welcome-container {
            max-width: 800px;
            margin: 0 auto 40px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-top: 4px solid #27ae60;
        }
        
        .welcome-title {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }
        
        .welcome-text {
            color: #34495e;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .logo {
            max-width: 180px;
            margin: 0 auto 20px;
            display: block;
        }
        
        .btn-documental {
            background-color: #27ae60;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-documental:hover {
            background-color: #219653;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .institution-info {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-style: italic;
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>

</head>
<body>
    <div class="welcome-container">
        <img src="layout/img/logoDU.png" alt="Logo del Sistema" class="logo">
        
        <h2 class="welcome-title">Bienvenido al Módulo de Gestión Documental</h2>
        
        <p class="welcome-text">
            Este módulo le permite administrar toda la documentación académica de manera centralizada.
            Desde aquí podrá acceder a los registros, reportes y herramientas para una gestión eficiente
            de los documentos institucionales bajo el principio <strong>"Formando los profesionales de salud que el país necesita"</strong>.
        </p>
        
         <!-- Botón modificado para abrir en nueva pestaña -->
        <a href="https://revistacientifica.rf.gd/?i=1" target="_blank" class="btn-documental">
            <i class="fas fa-folder-open"></i> Acceder a Gestión Documental
        </a>
        
    </div>
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>