<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$registrarNota = new Notas(null, null);
switch ($_REQUEST['accion']) {
  case 'Adicionar':
    $registrarNota->setIdUsuarioEstudiante($_REQUEST['id_usuario_estudiante']);
    $registrarNota->setIdPeriodoAcademico($_REQUEST['id_periodo_academico']);
    $registrarNota->setIdAsignatura($_REQUEST['id_asignatura']);
    $registrarNota->setIdTipoActividad($_REQUEST['id_tipo_actividad']);
    $registrarNota->setNota($_REQUEST['nota']);
    $registrarNota->guardar();
    break;
  case 'AdicionarArray':
    for ($i = 1; $i <= $_REQUEST['numero_tipo_actividad']; $i++) {
      if (!empty($_REQUEST['actividad' . $i]) && !empty($_REQUEST['nota' . $i])) {
        $registrarNota->setIdUsuarioEstudiante($_REQUEST['id_usuario_estudiante']);
        $registrarNota->setIdPeriodoAcademico($_REQUEST['id_periodo_academico']);
        $registrarNota->setIdAsignatura($_REQUEST['id_asignatura']);
        $registrarNota->setIdTipoActividad($_REQUEST['actividad' . $i]);
        $registrarNota->setNota($_REQUEST['nota' . $i]);
        $registrarNota->guardar();
      }
    }
    break;
  case 'Modificar':
    $registrarNota->setIdUsuarioEstudiante($_REQUEST['id_usuario_estudiante']);
    $registrarNota->setIdPeriodoAcademico($_REQUEST['id_periodo_academico']);
    $registrarNota->setIdAsignatura($_REQUEST['id_asignatura']);
    $registrarNota->setIdTipoActividad($_REQUEST['id_tipo_actividad']);
    $registrarNota->setNota($_REQUEST['nota']);
    $registrarNota->modificar($_REQUEST['id']);
    break;
  case 'Eliminar':
    $registrarNota->setId($_REQUEST['id']);
    $registrarNota->eliminar();
    break;
}

?>
<script>
  window.location = 'principal.php?CONTENIDO=layout/components/notas/lista-notas.php';
</script>