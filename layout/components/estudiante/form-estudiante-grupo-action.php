<?php
require_once 'logica/clases/GrupoEstudiante.php';
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$grupoEstudiante = new GrupoEstudiante(null, null);
switch ($_REQUEST['accion']) {
  case 'Adicionar':
    $grupoEstudiante->setIdUsuarioEstudiante($_REQUEST['id_usuario_estudiante']);
    $grupoEstudiante->setIdGrupo($_REQUEST['id_grupo']);
    $grupoEstudiante->setIdAnioEscolar($_REQUEST['id_anio_escolar']);
    $grupoEstudiante->guardar();
    break;
  case 'Modificar':
    $grupoEstudiante->setIdUsuarioEstudiante($_REQUEST['id_usuario_estudiante']);
    $grupoEstudiante->setIdGrupo($_REQUEST['id_grupo']);
    $grupoEstudiante->setIdAnioEscolar($_REQUEST['id_anio_escolar']);
    $grupoEstudiante->modificar($_REQUEST['id']);
    break;
  case 'Eliminar':
    $grupoEstudiante->setId($_REQUEST['id']);
    $grupoEstudiante->eliminar();
    break;
}

?>
<script>
  window.location = 'principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php';
</script>