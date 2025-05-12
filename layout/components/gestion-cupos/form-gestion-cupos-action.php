<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

require_once 'logica/clases/InstitucionEducativa.php';
require_once 'logica/clases/GestionCupos.php';

$gestionCupo = new GestionCupos(null, null); 
    switch ($_REQUEST['accion']) {
        case 'Adicionar':
            $gestionCupo->setEspecialidad($_REQUEST['especialidad']);
            $gestionCupo->setNumeroEstudiantes($_REQUEST['numero_estudiantes']);
            $gestionCupo->setInstitucionEducativaId($_REQUEST['institucion_educativa_id']);
            $gestionCupo->setNombreEstudiante($_REQUEST['nombre_estudiante']);
            $gestionCupo->setTurno($_REQUEST['turno']);
            $gestionCupo->setFechaRegistro($_REQUEST['fecha_registro']); // Nuevo campo
            $gestionCupo->guardar();
            break;

        case 'Modificar':
            $gestionCupo->setEspecialidad($_REQUEST['especialidad']);
            $gestionCupo->setNumeroEstudiantes($_REQUEST['numero_estudiantes']);
            $gestionCupo->setInstitucionEducativaId($_REQUEST['institucion_educativa_id']);
            $gestionCupo->setNombreEstudiante($_REQUEST['nombre_estudiante']);
            $gestionCupo->setTurno($_REQUEST['turno']);
            $gestionCupo->setFechaRegistro($_REQUEST['fecha_registro']); // Nuevo campo
            $gestionCupo->modificar($_REQUEST['id']);
            break;

        case 'Eliminar':
            $gestionCupo->setId($_REQUEST['id']);
            $gestionCupo->eliminar();
            break;
    }


?>
<script>
    window.location = 'principal.php?CONTENIDO=layout/components/gestion-cupos/lista-gestion-cupos.php';
</script>