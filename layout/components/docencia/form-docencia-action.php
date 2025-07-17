<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Acceso no autorizado');

$institution = new InfoDocencia(null, null);
switch ($_REQUEST['accion']) {
    case 'modificar':
        $institution->setNombre($_REQUEST['nombre']);
        $institution->setDireccion($_REQUEST['direccion']);
        $institution->setEmail($_REQUEST['email']);
        $institution->setTelefono($_REQUEST['telefono']);
        $institution->setNombreCoordinador($_REQUEST['director']);
        $institution->setPaginaWeb($_REQUEST['ulrWeb']);
        $institution->modificar($_REQUEST['id']);
        break;
}

?>
<script>
    window.location = 'principal.php?CONTENIDO=layout/inicio.php';
</script>