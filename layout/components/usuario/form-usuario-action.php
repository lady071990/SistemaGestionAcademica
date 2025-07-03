<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();

$docente = new Usuario(null, null);
switch ($_REQUEST['accion']) {
  case 'Modificar':
    $docente->setIdentificacion($_REQUEST['identificacion']);
    $docente->setNombres($_REQUEST['nombres']);
    $docente->setApellidos($_REQUEST['apellidos']);
    $docente->setTelefono($_REQUEST['telefono']);
    $docente->setEmail($_REQUEST['email']);
    $docente->setClave($_REQUEST['pass']);
    $docente->setDireccion($_REQUEST['direccion']);
    /*
    $docente->setHojaVida($_REQUEST['hojavida']);
    $docente->setDocumentos($_REQUEST['documentos']);
    $docente->setFoto($_REQUEST['foto']);
     */
    $docente->setEstado($_REQUEST['estado']);
    $docente->setRolId($_REQUEST['rol_id']);
    $docente->modificar($_REQUEST['id']);
    break;
}

echo '<script>window.location = "principal.php?CONTENIDO=layout/components/usuario/datos-usuario.php"</script>';
