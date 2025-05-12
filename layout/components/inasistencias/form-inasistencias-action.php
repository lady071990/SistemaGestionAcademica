<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$inasistencias = new Inasistencias(null, null, 'total');
switch ($_REQUEST['accion']) {
  case 'Adicionar':
    $inasistencias->setCantidad($_REQUEST['cantidad']);
    $inasistencias->setJustificacion($_REQUEST['justificacion']);
    $inasistencias->setRegistradoAEstudiante($_REQUEST['registrado_a_estudiante']);
    $inasistencias->setCreadoPorDocente($_REQUEST['creado_por_docente']);
    $inasistencias->setIdAsignatura($_REQUEST['id_asignatura']);
    $inasistencias->guardar();
    break;
  case 'Modificar':
    $inasistencias->setCantidad($_REQUEST['cantidad']);
    $inasistencias->setJustificacion($_REQUEST['justificacion']);
    $inasistencias->setRegistradoAEstudiante($_REQUEST['registrado_a_estudiante']);
    $inasistencias->setCreadoPorDocente($_REQUEST['creado_por_docente']);
    $inasistencias->setIdAsignatura($_REQUEST['id_asignatura']);
    $inasistencias->modificar($_REQUEST['id']);
    break;
  case 'Eliminar':
    $inasistencias->setId($_REQUEST['id']);
    $inasistencias->eliminar();
    break;
}

?>
<script>
  window.location = 'principal.php?CONTENIDO=layout/components/inasistencias/lista-inasistencias.php';
</script>