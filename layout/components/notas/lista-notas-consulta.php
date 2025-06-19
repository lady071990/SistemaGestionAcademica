<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: ../../index.php?mensaje=Acceso no autorizado');
    exit;
}
include 'logica/clasesGenericas/Librerias.php';

$USUARIO = unserialize($_SESSION['usuario']);
$rol = $USUARIO->getRolId();
$idInstitucion = $USUARIO->getInstitucion_educativa_id();

$consulta = '';
$where = '';
$bandera = false;

// Filtro por institución si es rol 7
if ($rol == 7 && $idInstitucion) {
    $where = "u.institucion_educativa_id = $idInstitucion";
    $bandera = true;
}

// Filtros de búsqueda
if (isset($_REQUEST['buscar'])) {
    $filtros = [];
    if (!empty($_REQUEST['identificacion'])) {
        $filtros[] = "u.identificacion LIKE '%{$_REQUEST['identificacion']}%'";
    }
    if (!empty($_REQUEST['nombres'])) {
        $filtros[] = "u.nombres LIKE '%{$_REQUEST['nombres']}%'";
    }
    
    if (!empty($filtros)) {
        $filtroStr = implode(' AND ', $filtros);
        $where .= ($where ? ' AND ' : '') . $filtroStr;
    }
}

// Obtener lista de notas
$condicion = ($where ? $where . ' AND ' : '') . "u.rol_id=4";
$notas = Notas::getListaEnObjetos($condicion, "u.identificacion, u.apellidos, u.nombres, gd.nombre_grado, g.nombre_grupo, n.id_periodo_academico, a.nombre_asignatura");

?>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/notas/lista-notas-consulta.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Buscar estudiante para consultar sus notas</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="identificacion">Identificación</label>
                    <input type="number" name="identificacion" id="identificacion" placeholder="Identificación" value="<?= isset($_REQUEST['identificacion']) ? htmlspecialchars($_REQUEST['identificacion']) : '' ?>">
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombres">Nombres</label>
                    <input type="text" name="nombres" id="nombres" placeholder="Nombres" value="<?= isset($_REQUEST['nombres']) ? htmlspecialchars($_REQUEST['nombres']) : '' ?>">
                </div>
            </div>
            <input type="hidden" name="buscar" value="buscar">
            <div class="as-form-button">
                <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/inicio.php">
                    Limpiar
                </a>
                <button class="as-color-btn-green" type="submit">
                    Buscar
                </button>
            </div>
        </div>
    </form>
</div>
<div class="as-layout-table">
<div>
    <h3 class="as-title-table">CONSULTAR NOTAS</h3>
</div>
</div>
<?php
if (count($notas) === 0) {
    echo "<p>No se encontraron resultados.</p>";
} else {
    $estudiantes = [];

    foreach ($notas as $nota) {
        $idEstudiante = $nota->getIdentificacionEstudiante();
        $idPeriodo = $nota->getPeriodoAcademico()->getId();
        $nombreAsignatura = $nota->getNombreAsignatura()->getNombreAsignatura();
        $nombreEstudiante = $nota->getNombreEstudiante();

        if (!isset($estudiantes[$idEstudiante])) {
            $estudiantes[$idEstudiante] = [
                'nombres' => $nombreEstudiante->getNombres() . ' ' . $nombreEstudiante->getApellidos(),
                'grado' => $nota->getNombreGrado(),
                'grupo' => $nota->getNombreGrupo(),
                'periodos' => []
            ];
        }

        if (!isset($estudiantes[$idEstudiante]['periodos'][$idPeriodo])) {
            $estudiantes[$idEstudiante]['periodos'][$idPeriodo] = [
                'nombre_periodo' => $nota->getPeriodoAcademico()->getNombre(),
                'asignaturas' => []
            ];
        }

        if (!isset($estudiantes[$idEstudiante]['periodos'][$idPeriodo]['asignaturas'][$nombreAsignatura])) {
            $estudiantes[$idEstudiante]['periodos'][$idPeriodo]['asignaturas'][$nombreAsignatura] = [
                'actividades' => [],
                'total' => 0,
                'cantidad' => 0
            ];
        }

        $actividad = $nota->getNombreTipoActividad();
        $nombreActividad = $actividad ? $actividad->getNombreActividad() : 'Sin competencia';

        $notaValor = $nota->getNota();

        $estudiantes[$idEstudiante]['periodos'][$idPeriodo]['asignaturas'][$nombreAsignatura]['actividades'][] = [
            'competencia' => $nombreActividad,
            'nota' => $notaValor
        ];

        $estudiantes[$idEstudiante]['periodos'][$idPeriodo]['asignaturas'][$nombreAsignatura]['total'] += $notaValor;
        $estudiantes[$idEstudiante]['periodos'][$idPeriodo]['asignaturas'][$nombreAsignatura]['cantidad']++;
    }

    foreach ($estudiantes as $identificacion => $datosEstudiante) {
        echo "<div style='margin-bottom: 30px; border: 1px solid #ccc; padding: 10px;'>";
        echo "<strong>Identificación:</strong> $identificacion<br>";
        echo "<strong>Nombre:</strong> {$datosEstudiante['nombres']}<br>";
        echo "<strong>Grado:</strong> {$datosEstudiante['grado']} - <strong>Grupo:</strong> {$datosEstudiante['grupo']}<br>";

        foreach ($datosEstudiante['periodos'] as $periodo) {
            echo "<br><strong>Periodo:</strong> {$periodo['nombre_periodo']}<br>";

            echo "<table border='1' cellpadding='5' style='width:100%; margin-top:10px;'>";
            echo "<tr><th>Asignatura</th><th>Actividad</th><th>Nota</th></tr>";

            $totalGeneral = 0;
            $cantidadGeneral = 0;

            foreach ($periodo['asignaturas'] as $asignaturaNombre => $asignatura) {
                foreach ($asignatura['actividades'] as $actividad) {
                    echo "<tr>";
                    echo "<td>$asignaturaNombre</td>";
                    echo "<td>{$actividad['competencia']}</td>";
                    echo "<td>{$actividad['nota']}</td>";
                    echo "</tr>";
                }

                $promedioAsignatura = $asignatura['cantidad'] > 0 ? round($asignatura['total'] / $asignatura['cantidad'], 2) : 0;
                echo "<tr><td colspan='3' align='right'><strong>Promedio $asignaturaNombre:</strong> $promedioAsignatura</td></tr>";

                $totalGeneral += $asignatura['total'];
                $cantidadGeneral += $asignatura['cantidad'];
            }

            $promedioGeneral = $cantidadGeneral > 0 ? round($totalGeneral / $cantidadGeneral, 2) : 0;
            echo "<tr><td colspan='3' align='right'><strong>Promedio General:</strong> $promedioGeneral</td></tr>";
            echo "</table>";
        }

        echo "</div>";
    }
}
?>