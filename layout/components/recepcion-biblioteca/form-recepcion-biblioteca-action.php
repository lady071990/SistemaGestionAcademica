<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

require_once 'logica/clases/RecepcionBiblioteca.php';
require_once 'logica/clases/InstitucionEducativa.php';


$recepcionAula = new RecepcionBiblioteca(null, null);

switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $recepcionAula->setNumero_computadores($_REQUEST['numero_computadores']);
        $recepcionAula->setNombre_proyecto($_REQUEST['nombre_proyecto']);
        $recepcionAula->setNumero_estudiantes($_REQUEST['numero_estudiantes']);
        $recepcionAula->setNombre_estudiantes($_REQUEST['nombre_estudiantes']);
        $recepcionAula->setInstitucionEducativaId($_REQUEST['institucion_educativa_id']);
        $recepcionAula->setHora_inicio($_REQUEST['hora_inicio']);
        $recepcionAula->setHora_fin($_REQUEST['hora_fin']);
        $recepcionAula->setTiempoAsignado($_REQUEST['tiempo_asignado']);
        $recepcionAula->setFecha_solicitud($_REQUEST['fecha_solicitud']);
        $recepcionAula->guardar();
        break;

    case 'Modificar':
        $recepcionAula->setNumero_computadores($_REQUEST['numero_computadores']);
        $recepcionAula->setNombre_proyecto($_REQUEST['nombre_proyecto']);
        $recepcionAula->setNumero_estudiantes($_REQUEST['numero_estudiantes']);
        $recepcionAula->setNombre_estudiantes($_REQUEST['nombre_estudiantes']);
        $recepcionAula->setInstitucionEducativaId($_REQUEST['institucion_educativa_id']);
        $recepcionAula->setHora_inicio($_REQUEST['hora_inicio']);
        $recepcionAula->setHora_fin($_REQUEST['hora_fin']);
        $recepcionAula->setTiempoAsignado($_REQUEST['tiempo_asignado']);
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
    window.location = 'principal.php?CONTENIDO=layout/components/recepcion-biblioteca/lista-recepcion-biblioteca.php';
</script>