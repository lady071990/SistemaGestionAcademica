<?php
?>
<!doctype html>
<html>
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
        .aprobado-reprobado {
            font-size: 16px;
            margin-top: 20px;
            font-weight: bold;
        }
        
        .firma-coordinador {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
        }

        
    </style>
</head>
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
    
    if (isset($_REQUEST['identificacion'])) {
    $listaNotas = NotasConsulta::getListaEnObjetos("WHERE u.identificacion = {$_REQUEST['identificacion']} GROUP BY n.id_periodo_academico, u.identificacion", false);

    foreach ($listaNotas as $key) {
        $lista .= '<div class="boletin-container">
            <div class="header">
                <img class="logo" src="layout/img/FORMATO.png" alt="Logo">
                <h2>FORMATO DE EVALUACIÓN INTERNADO ROTATORIO</h2>
                <p>' . htmlspecialchars($key->getNombreInstitucion()) . '</p>
            </div>

            <div class="student-info">
                <div>
                    <p><strong>Identificación:</strong> ' . htmlspecialchars($key->getIdentificacionEstudiante()) . '</p>
                    <p><strong>Nombre:</strong> ' . htmlspecialchars($key->getNombreEstudiante()) . '</p>
                    <p><strong>Grado:</strong> ' . htmlspecialchars($key->getNombreGrado() . ' ' . $key->getNombreGrupo()) . '</p>
                    <p><strong>Programa Académico:</strong> ' . htmlspecialchars($key->getProgramaAcademico()) . '</p>
                    <p><strong>Periodo Académico:</strong> ' . htmlspecialchars($key->getPeriodoAcademico()) . '</p>
                </div>
                <img class="student-photo" src="./documentos/fotos/' . htmlspecialchars($key->getFoto()) . '" alt="Foto del estudiante">
            </div>';

        $lista .= '<table class="info-table">
                    <thead>
                        <tr>
                            <th>Area de Rotación</th>
                            <th>Competencia</th>
                            <th>Nota Acumulada</th>
                            <th>Inasistencias</th>
                            <th>Promedio</th>
                            <th>Responsable de Calificación</th>
                        </tr>
                    </thead>
                    <tbody>';

        $listaAsignaturas = NotasConsulta::getListaEnObjetos('GROUP BY n.id_periodo_academico, u.identificacion, n.id_asignatura', true);

        foreach ($listaAsignaturas as $asignatura) {
            if ($asignatura->getIdPeriodoAcademico() == $key->getIdPeriodoAcademico() && $key->getIdUsuarioEstudiante() == $asignatura->getIdUsuarioEstudiante()) {

                $listaInasistencias = Inasistencias::getListaEnObjetos("i.id_asignatura = {$asignatura->getIdAsignatura()} AND i.registrado_a_estudiante = {$key->getIdUsuarioEstudiante()}", null, 'suma');
                $cantidadInasistencias = '0';
                foreach ($listaInasistencias as $inasistencia) {
                    $cantidadInasistencias = $inasistencia->getCantidad() ?: '0';
                }

                $listaNotasAsignadas = NotasConsulta::getListaEnObjetos("", false);
                $cantidadNotas = 0;
                $sumaNotas = 0;
                $detallesActividades = '';

                foreach ($listaNotasAsignadas as $item) {
                    if (
                        $asignatura->getIdPeriodoAcademico() == $key->getIdPeriodoAcademico() &&
                        $asignatura->getIdentificacionEstudiante() == $item->getIdentificacionEstudiante() &&
                        $asignatura->getIdAsignatura() == $item->getIdAsignatura()
                    ) {
                        $detallesActividades .= $item->getNombreTipoActividad() . ': ' . $item->getNota() . '<br>';
                        $cantidadNotas++;
                        $sumaNotas += $item->getNota();
                    }
                }
                $promedio = ($cantidadNotas > 0) ? number_format($sumaNotas / $cantidadNotas, 2) : '-';

                // Obtener nombre del docente responsable
                $asignaciones = AsignacionDocente::getListaEnObjetos("ad.id_asignatura = {$asignatura->getIdAsignatura()}", null);
                $docenteResponsable = 'No asignado';
                if (!empty($asignaciones)) {
                    $docente = $asignaciones[0]->getNombreDocente();
                    $docenteResponsable = $docente->getNombres() . ' ' . $docente->getApellidos();
                }

                $lista .= '<tr>
                            <td>' . htmlspecialchars($asignatura->getNombreAsignatura()) . '</td>
                            <td>' . $detallesActividades . '</td>
                            <td>' . ($cantidadNotas > 0 ? number_format($sumaNotas, 2) : '-') . '</td>
                            <td>' . $cantidadInasistencias . '</td>
                            <td>' . $promedio . '</td>
                            <td>' . htmlspecialchars($docenteResponsable) . '</td>
                        </tr>';
            }
        }
            $lista .= '</tbody></table>';

        // Buscar observación del estudiante para el año escolar activo
        require_once 'logica/clases/ObservacionBoletin.php';
        require_once 'logica/clases/AnioEscolar.php';

        $anioActivo = AnioEscolar::getListaEnObjetos("estado=1", null);
        $idAnioActivo = ($anioActivo && count($anioActivo) > 0) ? $anioActivo[0]->getId() : null;

        $textoObservacion = 'Sin observaciones registradas.';

        if ($idAnioActivo) {
            $observacionBoletin = ObservacionBoletin::buscarObservacion($key->getIdUsuarioEstudiante(), $idAnioActivo);

            if ($observacionBoletin) {
                $texto = nl2br(htmlspecialchars($observacionBoletin->getObservacion()));

                $fechaOriginal = $observacionBoletin->getFecha_registro();
                $fechaFormateada = '';

                if ($fechaOriginal && $fechaOriginal !== '0000-00-00') {
                    $meses = [
                        '01' => 'enero', '02' => 'febrero', '03' => 'marzo',
                        '04' => 'abril', '05' => 'mayo', '06' => 'junio',
                        '07' => 'julio', '08' => 'agosto', '09' => 'septiembre',
                        '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
                    ];

                    $fechaSinHora = explode(' ', $fechaOriginal)[0]; // "2025-07-17"
                    $partes = explode('-', $fechaSinHora);
                    $dia = ltrim($partes[2], '0'); // Quita ceros a la izquierda
                    $mes = $meses[$partes[1]] ?? '';
                    $anio = $partes[0];

                    $fechaFormateada = "$dia de $mes $anio";
                }

                $textoObservacion = "$texto<br><em>Fecha: $fechaFormateada</em>";
            }
        }

        // Concatenar al HTML
        $lista .= '
            <div style="margin-top: 30px;">
                <p><strong>Observaciones:</strong></p>
                <div style="border: 1px solid #000; min-height: 80px; padding: 10px;">' . $textoObservacion . '</div>
            </div>

    <div style="margin-top: 20px; display: flex; justify-content: space-between; width: 100%;">
        <div>
            <strong>Resultado:</strong><br>
            <div class="aprobado-reprobado">
                <span>' . ($promedio >= 3.0 ? '☑' : '☐') . ' APROBADO</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span>' . ($promedio < 3.0 ? '☑' : '☐') . ' REPROBADO</span>
            </div>
        </div>
    </div>

    <div class="firma-coordinador" style="text-align: center; margin-top: 40px;">
        <img src="layout/img/firma.png" alt="Firma" style="height: 100px; margin-bottom: 5px;">

        <hr style="width: 200px; border: 1px solid #000; margin: 10px auto 5px auto;">

        <p style="font-weight: bold; margin: 0;">Martín Caicedo</p>
        <p style="margin: 0;"><small>Coordinación Docencia e Investigación</small></p>
    </div>



    </div>';
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
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>