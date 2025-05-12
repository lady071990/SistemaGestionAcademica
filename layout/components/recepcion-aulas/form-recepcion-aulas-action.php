<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

require_once 'logica/clases/RecepcionAulas.php';
require_once 'logica/clases/InstitucionEducativa.php';


$recepcionAula = new RecepcionAulas(null, null);

switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $recepcionAula->setNombreAula($_REQUEST['nombre_aula']);
        $recepcionAula->setNombreEstudiante($_REQUEST['nombre_estudiante']);
        $recepcionAula->setNombreDocente($_REQUEST['nombre_docente']);
        $recepcionAula->setInstitucionEducativaId($_REQUEST['institucion_educativa_id']);
        $recepcionAula->setNombreTema($_REQUEST['nombre_tema']);
        $recepcionAula->setSemestre($_REQUEST['semestre']);
        $recepcionAula->setTiempoAsignado($_POST['tiempo_asignado']);
        $recepcionAula->setHora_inicio($_REQUEST['hora_inicio']);
        $recepcionAula->setHora_fin($_REQUEST['hora_fin']);
        $recepcionAula->setFecha_solicitud($_REQUEST['fecha_solicitud']);
        $recepcionAula->guardar();
        break;

    case 'Modificar':
        $recepcionAula->setNombreAula($_REQUEST['nombre_aula']);
        $recepcionAula->setNombreEstudiante($_REQUEST['nombre_estudiante']);
        $recepcionAula->setNombreDocente($_REQUEST['nombre_docente']);
        $recepcionAula->setInstitucionEducativaId($_REQUEST['institucion_educativa_id']);
        $recepcionAula->setNombreTema($_REQUEST['nombre_tema']);
        $recepcionAula->setSemestre($_REQUEST['semestre']);
        $recepcionAula->setTiempoAsignado($_POST['tiempo_asignado']);
        $recepcionAula->setHora_inicio($_REQUEST['hora_inicio']);
        $recepcionAula->setHora_fin($_REQUEST['hora_fin']);
        $recepcionAula->setFecha_solicitud($_REQUEST['fecha_solicitud']);
        $recepcionAula->modificar($_REQUEST['id']);
        break;

    case 'Eliminar':
        $recepcionAula->setId($_REQUEST['id']);
        $recepcionAula->eliminar();
        break;
}
?>
<script>
    window.location = 'principal.php?CONTENIDO=layout/components/recepcion-aulas/lista-recepcion-aulas.php';
</script>