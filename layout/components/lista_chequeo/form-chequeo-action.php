<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso no autorizado');
    exit;
}

require_once 'logica/clases/ListaChequeo.php';
require_once 'logica/clasesGenericas/ConectorBD.php';

// Verificar acción
if (!isset($_REQUEST['accion'])) {
    header('Location: principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php');
    exit;
}

$lista = new ListaChequeo(null, null);

switch ($_REQUEST['accion']) {
    case 'Agregar':
        // Configurar todos los campos del checklist
        $lista->setInstitucion_educativa_id($_POST['institucion_educativa_id']);
        $lista->setConvenio(subirPDF('convenio'));
        $lista->setObjetivo_convenio(subirPDF('objetivo_convenio'));
        $lista->setVigencia_convenio(subirPDF('vigencia_convenio'));
        $lista->setDeberes(subirPDF('deberes'));
        $lista->setPoliza_responsabilidad(subirPDF('poliza_responsabilidad'));
        $lista->setPoliza_riesgo_biologico(subirPDF('poliza_riesgo_biologico'));
        $lista->setFormas_compensacion(subirPDF('formas_compensacion'));
        $lista->setAnexo_tecnico(subirPDF('anexo_tecnico'));
        $lista->setCronograma(subirPDF('cronograma'));
        $lista->setEsquema_vacunacion(subirPDF('esquema_vacunacion'));
        $lista->setSsst(subirPDF('ssst'));
        $lista->setArl(subirPDF('arl'));
        $lista->setFecha_subida($_POST['fecha_subida'] ?? date('Y-m-d H:i:s'));
        
        $lista->guardar();
        break;

    case 'Modificar':
        $lista = new ListaChequeo('id', $_REQUEST['id']);
        
        // ⚠️ Validación: impedir que una institución modifique registros de otra
        if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'institucion') {
            $institucionIdSesion = $_SESSION['usuario']['institucion_id'] ?? null;
            if ($lista->getInstitucion_educativa_id() != $institucionIdSesion) {
                header('location: principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php&mensaje=Modificación no autorizada');
                exit;
            }
        }
        // Actualizar solo los archivos que se hayan subido
        if (!empty($_FILES['convenio']['name'])) $lista->setConvenio(subirPDF('convenio'));
        if (!empty($_FILES['objetivo_convenio']['name'])) $lista->setObjetivo_convenio(subirPDF('objetivo_convenio'));
        if (!empty($_FILES['vigencia_convenio']['name'])) $lista->setVigencia_convenio(subirPDF('vigencia_convenio'));
        if (!empty($_FILES['deberes']['name'])) $lista->setDeberes(subirPDF('deberes'));
        if (!empty($_FILES['poliza_responsabilidad']['name'])) $lista->setPoliza_responsabilidad(subirPDF('poliza_responsabilidad'));
        if (!empty($_FILES['poliza_riesgo_biologico']['name'])) $lista->setPoliza_riesgo_biologico(subirPDF('poliza_riesgo_biologico'));
        if (!empty($_FILES['formas_compensacion']['name'])) $lista->setFormas_compensacion(subirPDF('formas_compensacion'));
        if (!empty($_FILES['anexo_tecnico']['name'])) $lista->setAnexo_tecnico(subirPDF('anexo_tecnico'));
        if (!empty($_FILES['cronograma']['name'])) $lista->setCronograma(subirPDF('cronograma'));
        if (!empty($_FILES['esquema_vacunacion']['name'])) $lista->setEsquema_vacunacion(subirPDF('esquema_vacunacion'));
        if (!empty($_FILES['ssst']['name'])) $lista->setSsst(subirPDF('ssst'));
        if (!empty($_FILES['arl']['name'])) $lista->setArl(subirPDF('arl'));
        
        // Actualizar otros campos
        $lista->setInstitucion_educativa_id($_POST['institucion_educativa_id']);
        $lista->setFecha_subida($_POST['fecha_subida'] ?? date('Y-m-d H:i:s'));

        $lista->modificar($_REQUEST['id']);
        break;

    case 'Eliminar':
        $lista = new ListaChequeo('id', $_REQUEST['id']);
        $lista->eliminar();
        break;
}

    function subirPDF($campo) {
        if (isset($_FILES[$campo])) {
            if ($_FILES[$campo]['error'] != UPLOAD_ERR_OK) {
                return null;
            }

            // Verificar que sea un PDF
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES[$campo]['tmp_name']);

            if ($mime != 'application/pdf') {
                return null;
            }

            // Directorio de almacenamiento (ahora dentro de la carpeta pública)
            $directorio = $_SERVER['DOCUMENT_ROOT'] . '/SistemaGestionAcademica/documentos/archivos/';
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
            }

            // Generar nombre único
            $extension = pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $_FILES[$campo]['name']);
            $rutaCompleta = $directorio . $nombreArchivo;

            if (move_uploaded_file($_FILES[$campo]['tmp_name'], $rutaCompleta)) {
                return 'documentos/archivos/' . $nombreArchivo; // Ruta relativa desde la raíz del sitio
            }
        }
        return null;
    }
?>

<script>
    window.location = 'principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php';
</script>