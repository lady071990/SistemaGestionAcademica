<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso no autorizado');
    exit;
}

require_once 'logica/clases/InstitucionEducativa.php';

// Verificar acción
if (!isset($_REQUEST['accion'])) {
    header('Location: principal.php?CONTENIDO=layout/components/institucion/lista-institucion.php');
    exit;
}

$institucion = new InstitucionEducativa(null, null);

switch ($_REQUEST['accion']) {
    case 'Agregar':
            $programas = [];
            if (!empty($_POST['programas_agregados'])) {
                $programasAgregados = json_decode($_POST['programas_agregados'], true);
                foreach ($programasAgregados as $programa) {
                    $programas[] = [
                        'tipo' => $programa['tipo'],
                        'nombre' => $programa['nombre']
                    ];
                }
            }
        
        $institucion->setNombre($_POST['nombre']);
        $institucion->setDireccion($_POST['direccion']);
        $institucion->setTelefono($_POST['telefono']);
        $institucion->setEmail($_POST['email']);
        $institucion->setTipo($_POST['tipo']);
        $institucion->setNombreDirectora($_POST['nombreDirectora']);
        $institucion->setPaginaWeb($_POST['paginaWeb']);
          $institucion->setProgramas($programas);
        $institucion->setEspecialidadesMedicas($_POST['especialidadesMedicas'] ?? '');
        $institucion->guardar();
        break;

    case 'Modificar':
        $institucion = new InstitucionEducativa('id', $_REQUEST['id']);
        $institucion->setNombre($_POST['nombre']);
        $institucion->setDireccion($_POST['direccion']);
        $institucion->setTelefono($_POST['telefono']);
        $institucion->setEmail($_POST['email']);
        $institucion->setTipo($_POST['tipo']);
        $institucion->setNombreDirectora($_POST['nombreDirectora']);
        $institucion->setPaginaWeb($_POST['paginaWeb']);
        $institucion->setProgramas($_POST['programas_agregados'] ?? '');
        $institucion->setEspecialidadesMedicas($_POST['especialidadesMedicas'] ?? '');
        $institucion->modificar($_REQUEST['id']);
        break;
    
    case 'Eliminar':
        $institucion = new InstitucionEducativa('id', $_REQUEST['id']);
        $institucion->eliminar();
        break;
}

?>
<script>
    window.location = 'principal.php?CONTENIDO=layout/components/institucion/lista-institucion.php';
</script>