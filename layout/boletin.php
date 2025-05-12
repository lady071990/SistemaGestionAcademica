<?php  
require_once '../logica/clasesGenericas/LibreriasImprimir.php';
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al software académico - Imprimir</title>
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .student-photo {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ddd;
            margin: 5px;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .boletin-container {
            border: 2px solid #000;
            padding: 20px;
            width: 800px;
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .header img.logo {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .student-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .student-photo {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #000;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }
        .print-button {
            margin-bottom: 20px;
            text-align: center;
        }
        .print-button button {
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="as-nav-header">
            <div class="as-first-div">
                <img class="as-logo" src="img/logoDU.png" alt="logo" />
            </div>
            <div class="as-information">
                <h3 class="as-title">SIDGA - SISTEMA INTEGRAL DE DOCENCIA Y GESTIÓN ACADÉMICA</h3>
                <p>Plataforma académica del Hospital Universitario Departamental de Nariño, comprometida con la excelencia en la formación 
                    de profesionales de la salud. Nuestro sistema integra docencia, investigación y servicio, bajo el principio
                    <span>&quot;FORMANDO LOS PROFESIONALES DE SALUD QUE NARIÑO NECESITA&quot;</span>.</p>
            </div>
        </nav>
    </header>

   <?php
    @session_start();
    if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

    $lista = '';
    $count = 1;
    $posision = 0;
    $promedio = 0;
    $contar = 0;
    $cantidadNotas = 0;
    $listaNotas = array();
    $imagen='';
    if (isset($_FILES["foto"])){
        $file = $_FILES["foto"];
        $nombre = $_FILES["name"];
        $tipo = $_FILES["type"];
        $ruta_provisional = $file["tpm_name"];
        $size = $file["size"];
        $dimensiones = getimagesize($ruta_provisional);
        $width = $dimensiones[0];
        $height = $dimensiones[1];
        $carpeta = "layout/components/estudiante/fotos/";
        if ($tipo != 'image/jpg' && $tipo != 'image/JPG' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif'){
            echo "Error, el archivo no es una imagen";
        }
        else if ($size > 3*1024*1024){
            echo "Error, el tamaño maximo permitido es 3MB";
        }
        else{
            $src = $carpeta.$nombre;
            move_uploaded_file($ruta_provisional, $src);
            $imagen="layout/components/estudiante/fotos/ .$nombre";
        }
    }
    if (isset($_REQUEST['identificacion'])) {
    $listaNotas = NotasConsulta::getListaEnObjetos("WHERE u.identificacion = {$_REQUEST['identificacion']} GROUP BY n.id_periodo_academico, u.identificacion", false);
        
        foreach ($listaNotas as $key) {
            $lista .= '<div class="boletin-container">
                <div class="header">
                    <img class="logo" src="img/FORMATO.png" alt="Logo">
                    <h2>FORMATO DE EVALUACIÓN INTERNADO ROTATORIO</h2>
                    <p>' . htmlspecialchars($key->getNombreInstitucion()) . '</p>
                </div>

                <div class="student-info">
                    <div>
                        <p><strong>Identificación:</strong> ' . htmlspecialchars($key->getIdentificacionEstudiante()) . '</p>
                        <p><strong>Nombre:</strong> ' . htmlspecialchars($key->getNombreEstudiante()) . '</p>
                        <p><strong>Grado:</strong> ' . htmlspecialchars($key->getNombreGrado() . ' ' . $key->getNombreGrupo()) . '</p>
                        <p><strong>Periodo Académico:</strong> ' . htmlspecialchars($key->getPeriodoAcademico()) . '</p>
                    </div>
                </div>';

            $lista .= '<table class="info-table">
                        <thead>
                            <tr>
                                <th>Area de Rotacion</th>
                                <th>Competencia</th>
                                <th>Nota Acomulada</th>
                                <th>Inasistencias</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>';

            $listaAsignaturas = NotasConsulta::getListaEnObjetos('GROUP BY n.id_periodo_academico, u.identificacion, n.id_asignatura', true);

            foreach ($listaAsignaturas as $asignatura) {
                if ($asignatura->getIdPeriodoAcademico() == $key->getIdPeriodoAcademico() && $key->getIdUsuarioEstudiante() == $asignatura->getIdUsuarioEstudiante()) {

                    $listaInasistencias = Inasistencias::getListaEnObjetos("i.id_asignatura = {$asignatura->getIdAsignatura()} AND i.registrado_a_estudiante = {$key->getIdUsuarioEstudiante()}", null, 'suma');
                    $cantidadInasistencias = '0';
                    foreach ($listaInasistencias as $inasistencia) {
                        $cantidadInasistencias = $inasistencia->getCantidad() ? $inasistencia->getCantidad() : '0';
                    }

                    $listaNotasAsignadas = NotasConsulta::getListaEnObjetos("", false);
                    $cantidadNotas = 0;
                    $sumaNotas = 0;
                    $detallesActividades = '';

                    foreach ($listaNotasAsignadas as $item) {
                        if ($asignatura->getIdPeriodoAcademico() == $key->getIdPeriodoAcademico() &&
                            $asignatura->getIdentificacionEstudiante() == $item->getIdentificacionEstudiante() &&
                            $asignatura->getIdAsignatura() == $item->getIdAsignatura()) {
                            $detallesActividades .= $item->getNombreTipoActividad() . ': ' . $item->getNota() . '<br>';
                            $cantidadNotas++;
                            $sumaNotas += $item->getNota();
                        }
                    }
                    $promedio = ($cantidadNotas > 0) ? number_format($sumaNotas / $cantidadNotas, 2) : '-';

                    $lista .= '<tr>
                                <td>' . htmlspecialchars($asignatura->getNombreAsignatura()) . '</td>
                                <td>' . $detallesActividades . '</td>
                                <td>' . ($cantidadNotas > 0 ? number_format($sumaNotas, 2) : '-') . '</td>
                                <td>' . $cantidadInasistencias . '</td>
                                <td>' . $promedio . '</td>
                               </tr>';
                }
            }

            $lista .= '</tbody></table>
                </div>';  // cierre de boletin-container
        }
    }
    ?>

    <div class="print-button">
        <button onclick="window.print()">Imprimir</button>
    </div>

    <?php
    if (!empty($lista)) {
        echo $lista;
    } else {
        echo '<h3 style="text-align:center;">No Existen Notas para imprimir</h3>';
    }
    ?>

    <div class="footer">
        <p>Derechos Reservados &copy; <span id="year"></span></p>
    </div>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>