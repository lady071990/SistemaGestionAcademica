<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Acceso no autorizado');
$anioEscolar = new AnioEscolar(null, null, null);
switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $anioEscolar->setInicio(Generalidades::convertDate($_REQUEST['inicio'], true));
        $anioEscolar->setFin(Generalidades::convertDate($_REQUEST['fin'], true));
        $anioEscolar->setIdInstitucion($_REQUEST['id_institucion']);
        $anioEscolar->setEstado($_REQUEST['estado']);
        $anioEscolar->guardar();
        break;
    case 'Modificar':
        $anioEscolar->setInicio(Generalidades::convertDate($_REQUEST['inicio'], true));
        $anioEscolar->setFin(Generalidades::convertDate($_REQUEST['fin'], true));
        $anioEscolar->setIdInstitucion($_REQUEST['id_institucion']);
        $anioEscolar->setEstado($_REQUEST['estado']);
        $anioEscolar->modificar($_REQUEST['id']);
        break;
    case 'Eliminar':
        $anioEscolar->setId($_REQUEST['id']);
        $anioEscolar->eliminar();
        break;
}
?>
<script>
    window.location = 'principal.php?CONTENIDO=layout/components/anio-escolar/lista-anio.php';
</script>