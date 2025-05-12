<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();

$docente = new Usuario(null, null);
switch ($_REQUEST['accion']) {
  case 'Adicionar':
    $docente->setIdentificacion($_REQUEST['identificacion']);
    $docente->setNombres($_REQUEST['nombres']);
    $docente->setApellidos($_REQUEST['apellidos']);
    $docente->setTelefono($_REQUEST['telefono']);
    $docente->setEmail($_REQUEST['email']);
    $docente->setDireccion($_REQUEST['direccion']);
    $docente->setEstado($_REQUEST['estado']);
    $docente->setRolId(2);
    
    $docente->guardar();
    break;
  case 'Modificar':
    $docente->setIdentificacion($_REQUEST['identificacion']);
    $docente->setNombres($_REQUEST['nombres']);
    $docente->setApellidos($_REQUEST['apellidos']);
    $docente->setTelefono($_REQUEST['telefono']);
    $docente->setEmail($_REQUEST['email']);
    $docente->setClave($_REQUEST['pass']);
    $docente->setDireccion($_REQUEST['direccion']);
    $docente->setEstado($_REQUEST['estado']);
    $docente->setRolId(2);
   
    $docente->modificar($_REQUEST['id']);
    break;
  case 'Eliminar':
    $docente->setId($_REQUEST['id']);
    $docente->eliminar();
    break;
}

if ($editar == 1 || $editar == 6) {
?>
  <script>
    window.location = 'principal.php?CONTENIDO=layout/components/docente/lista-docente.php';
  </script>
<?php
} else {
?>
  <script>
    window.location = 'principal.php?CONTENIDO=layout/components/docente/lista-asignacion-docente.php';
  </script>
<?php
}
?>