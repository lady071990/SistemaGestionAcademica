<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$posision = 0;
$promedio = 0;
$contar = 0;
$cantidadNotas = 0;

$arrayPeriodoAcademico = PeriodoAcademico::getListaEnObjetos(null, 'id');
$listaNotas = array();

if ($editar == 1 || $editar == 6) {
    $listaNotas = NotasConsulta::getListaEnObjetos('GROUP BY n.id_periodo_academico, u.identificacion', false);
} else {
    $listaNotas = NotasConsulta::getListaEnObjetos("WHERE u.identificacion = {$USUARIO->getIdentificacion()} GROUP BY n.id_periodo_academico, u.identificacion", false);
}

foreach ($listaNotas as $key) {

    $lista .= '<div class="as-content-data">
                <div>
                    <h4>  #' . $count++ . ' - ' . $key->getPeriodoAcademico() . '</h4>
                </div>
                <div class="as-content-data-responsive">
                    <ul class="as-content-data-header">
                        <li><span class="as-content-data-title">Identificación: </span>' . $key->getIdentificacionEstudiante() . '</li>
                        <li><span class="as-content-data-title">Nombres: </span>' . $key->getNombreEstudiante() . '</li>
                        <li><span class="as-content-data-title">Grado: </span>' . $key->getNombreGrado() . ' ' . $key->getNombreGrupo() . '</li>
                    </ul>';

    $listaAsignaturas = NotasConsulta::getListaEnObjetos('GROUP BY n.id_periodo_academico, u.identificacion, n.id_asignatura', true);

    foreach ($listaAsignaturas as $asignatura) {

        if ($asignatura->getIdPeriodoAcademico() == $key->getIdPeriodoAcademico() && $key->getIdUsuarioEstudiante() == $asignatura->getIdUsuarioEstudiante()) {

            $lista .= '<div class="as-content-data-course">
                        <div class="as-content-data-course-title"><span>' . $asignatura->getNombreAsignatura() . '</span>';
            $listaInasistencias = Inasistencias::getListaEnObjetos("i.id_asignatura = {$asignatura->getIdAsignatura()} AND i.registrado_a_estudiante = {$key->getIdUsuarioEstudiante()}", null, 'suma');
            foreach ($listaInasistencias as $inasistencia) {
                $cantidad = $inasistencia->getCantidad() ? $inasistencia->getCantidad() : '0';
                $lista .= '<span> Inasistencias: ' . $cantidad . '</span>';
            }
            $lista .= '</div><div class="as-content-data-activity">';

            $listaNotasAsignadas = NotasConsulta::getListaEnObjetos("", false);

            $contar =  count($listaNotasAsignadas);
            $posicion = 0;
            $cantidadNotas = 0;
            foreach ($listaNotasAsignadas as $item) {
                $posicion++;
                if (
                    $asignatura->getIdPeriodoAcademico() == $key->getIdPeriodoAcademico() &&
                    $asignatura->getIdentificacionEstudiante() == $item->getIdentificacionEstudiante() &&
                    $asignatura->getIdAsignatura() == $item->getIdAsignatura()
                ) {
                    $lista .= '<p class="as-content-data-activity-item">
                                <span class="as-content-data-activity-title">' . $item->getNombreTipoActividad() . ':</span> ' . $item->getNota() . '
                            </p>';
                    $cantidadNotas++;
                }

                if ($contar == $posicion) {
                    $promedio = $asignatura->getNota() / $cantidadNotas;
                    $lista .= '<p class="as-content-data-activity-item"><span class="as-content-data-activity-title">Promedio :</span> ' . number_format($promedio, 2) . '</p>';
                }
            }

            $lista .= '</div><!-- as-content-data-activity fin -->
                    </div><!-- as-content-data-course fin -->';
        }
    }
    $lista .= '</div><!-- as-content-data-responsive fin -->
            </div><!-- as-content-data fin -->';
}

?>


<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">LISTA DE CALIFICACIONES</h3>
    </div>

    <?php print_r($lista); ?>

</div>