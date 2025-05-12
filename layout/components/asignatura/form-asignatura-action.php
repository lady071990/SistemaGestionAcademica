<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$asignatura = new Asignatura(null, null);
switch ($_REQUEST['accion']) {
  case 'Adicionar':
    $asignatura->setNombreAsignatura($_REQUEST['nombre_asignatura']);
    $asignatura->guardar();
    break;
  case 'Modificar':
    $asignatura->setNombreAsignatura($_REQUEST['nombre_asignatura']);
    $asignatura->modificar($_REQUEST['id']);
    break;
  case 'Eliminar':
    $asignatura->setId($_REQUEST['id']);
    $asignatura->eliminar();
    break;
}

?>
<script>
  window.location = 'principal.php?CONTENIDO=layout/components/asignatura/lista-asignatura.php';
</script>