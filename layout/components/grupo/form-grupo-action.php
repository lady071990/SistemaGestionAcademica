<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Acceso no autorizado');

$grupo = new Grupo(null, null);
switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $grupo->setNombreGrupo($_REQUEST['nombre_grupo']);
        $grupo->setIdGrado($_REQUEST['id_grado']);
        $grupo->guardar();
        break;
    case 'Modificar':
        $grupo->setNombreGrupo($_REQUEST['nombre_grupo']);
        $grupo->setIdGrado($_REQUEST['id_grado']);
        $grupo->modificar($_REQUEST['id']);
        break;
    case 'Eliminar':
        $grupo->setId($_REQUEST['id']);
        $grupo->eliminar();
        break;
}
?>
<script>
    window.location = 'principal.php?CONTENIDO=layout/components/grupo/lista-grupo.php';
</script>