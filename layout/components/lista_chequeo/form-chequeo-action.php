<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso no autorizado');
    exit;
}

require_once 'logica/clases/ListaChequeo.php';

$lista = new ListaChequeo(null, null);

/**
 * Función para convertir nombres de campos a nombres de métodos setter
 */
function getSetterMethod($fieldName) {
    return 'set' . ucfirst($fieldName);
}

/**
 * Función para subir archivos PDF
 */
function subirPDF($campo, $tipo = '') {
    if (!isset($_FILES[$campo])) {
        return null;
    }

    $file = $_FILES[$campo];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        error_log("Error al subir archivo {$campo}: " . $file['error']);
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if ($mime !== 'application/pdf') {
        error_log("Archivo {$campo} no es PDF (tipo: {$mime})");
        return null;
    }

    $basePath = $_SERVER['DOCUMENT_ROOT'] . '/SistemaGestionAcademica/documentos/';
    $subfolder = $tipo ? "archivos/{$tipo}/" : "archivos/";
    $uploadDir = $basePath . $subfolder;

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $fullPath = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
        error_log("No se pudo mover el archivo {$campo} a {$fullPath}");
        return null;
    }

    return $filename;
}

$documentTypes = [
    'convenio' => 'convenio',
    'poliza_responsabilidad' => 'polizar',
    'poliza_riesgo_biologico' => 'polizarb',
    'anexo_tecnico' => 'anexo',
    'cronograma' => 'cronograma',
    'esquema_vacunacion' => 'esquema',
    'ssst' => 'certificado',
    'arl' => 'arl'
];

switch ($_REQUEST['accion']) {
    case 'Agregar':
        $lista->setInstitucion_educativa_id($_POST['institucion_educativa_id']);
        
        foreach ($documentTypes as $field => $type) {
            $method = getSetterMethod($field);
            $lista->$method(subirPDF($field, $type));
        }
        
        $lista->setFecha_subida($_POST['fecha_subida'] ?? date('Y-m-d H:i:s'));
        $lista->guardar();
        break;

    case 'Modificar':
        $lista = new ListaChequeo('id', $_REQUEST['id']);
        
        if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'institucion') {
            $institucionIdSesion = $_SESSION['usuario']['institucion_id'] ?? null;
            if ($lista->getInstitucion_educativa_id() != $institucionIdSesion) {
                header('location: principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php&mensaje=Modificación no autorizada');
                exit;
            }
        }
        
        foreach ($documentTypes as $field => $type) {
            if (!empty($_FILES[$field]['name'])) {
                $method = getSetterMethod($field);
                $lista->$method(subirPDF($field, $type));
            }
        }
        
        $lista->setInstitucion_educativa_id($_POST['institucion_educativa_id']);
        $lista->setFecha_subida($_POST['fecha_subida'] ?? date('Y-m-d H:i:s'));
        $lista->modificar($_REQUEST['id']);
        break;

    case 'Eliminar':
        $lista = new ListaChequeo('id', $_REQUEST['id']);
        $lista->eliminar();
        break;
}

$redirectUrl = 'principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php';
if (isset($_POST['id_universidad'])) {
    $redirectUrl .= '&id_universidad=' . urlencode($_POST['id_universidad']);
}
if (isset($_REQUEST['mensaje'])) {
    $redirectUrl .= '&mensaje=' . urlencode($_REQUEST['mensaje']);
}
?>

<script>
    window.location = 'principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php';
</script>