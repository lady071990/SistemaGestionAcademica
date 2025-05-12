<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$asignatura = new TipoActividad(null, null);
switch ($_REQUEST['accion']) {
  case 'Adicionar':
    $asignatura->setNombreActividad($_REQUEST['nombre_actividad']);
    $asignatura->guardar();
    break;
  case 'Modificar':
    $asignatura->setNombreActividad($_REQUEST['nombre_actividad']);
    $asignatura->modificar($_REQUEST['id']);
    break;
  case 'Eliminar':
    $asignatura->setId($_REQUEST['id']);
    $asignatura->eliminar();
    break;
}

?>
<script>
  window.location = 'principal.php?CONTENIDO=layout/components/tipo-actividad/lista-tipo-actividad.php';
</script>