<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$docente = new Usuario(null, null);

// Función para manejar archivos
function manejarArchivo($fileInput, $rutaDestino, $nombreBase) {
    if ($fileInput['error'] === UPLOAD_ERR_OK) {
        if (!file_exists($rutaDestino)) mkdir($rutaDestino, 0777, true);
        $extension = strtolower(pathinfo($fileInput['name'], PATHINFO_EXTENSION));
        $nombreArchivo = $nombreBase . '.' . $extension;
        $rutaCompleta = $rutaDestino . $nombreArchivo;

        if (move_uploaded_file($fileInput['tmp_name'], $rutaCompleta)) {
            return $nombreArchivo;
        } else {
            // Error moviendo archivo
            error_log("❌ No se pudo mover el archivo a $rutaCompleta");
        }
    } else {
        // Error subiendo archivo
        error_log("⚠️ Error subiendo archivo: " . $fileInput['error']);
    }
    return null;
}

switch ($_REQUEST['accion']) {
    case 'Adicionar':
    // Preparar nombre base para archivos únicos
    $nombreBaseArchivos = $_REQUEST['identificacion'] . '_' . time();

    // Inicializar variables
    $fotoSubida = null;
    $hojaVidaSubida = null;
    $documentosSubidos = null;

    // Subir foto solo si fue cargada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoSubida = manejarArchivo($_FILES['foto'], 'documentos/fotos/', $nombreBaseArchivos);
    }

    // Subir hoja de vida
    if (isset($_FILES['hojaVida']) && $_FILES['hojaVida']['error'] === UPLOAD_ERR_OK) {
        $hojaVidaSubida = manejarArchivo($_FILES['hojaVida'], 'documentos/hojaVida/', $nombreBaseArchivos);
    }

    // Subir otros documentos
    if (isset($_FILES['documentos']) && $_FILES['documentos']['error'] === UPLOAD_ERR_OK) {
        $documentosSubidos = manejarArchivo($_FILES['documentos'], 'documentos/soportes/', $nombreBaseArchivos);
    }

    // Crear objeto y asignar campos
    $docente->setIdentificacion($_REQUEST['identificacion']);
    $docente->setNombres($_REQUEST['nombres']);
    $docente->setApellidos($_REQUEST['apellidos']);
    $docente->setTelefono($_REQUEST['telefono']);
    $docente->setEmail($_REQUEST['email']);
    $docente->setDireccion($_REQUEST['direccion']);
    $docente->setInstitucion_educativa_id($_REQUEST['institucion_educativa_id']);
    $docente->setEstado($_REQUEST['estado']);
    $docente->setRolId(4);

    // Si no se subió foto, asignar por defecto. Si sí se subió, guardar el nombre real.
    if ($fotoSubida) {
        $docente->setFoto($fotoSubida);
    } else {
        $docente->setFoto('foto1.jpeg'); // por defecto solo si no se cargó
    }

    $docente->setHojaVida($hojaVidaSubida ?? '');
    $docente->setDocumentos($documentosSubidos ?? '');

    // Guardar en base de datos
    $docente->guardar();
    break;


    case 'Modificar':
        $docenteActual = new Usuario('id', $_REQUEST['id']);

        // Subir archivos solo si se cambian
        if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $nombreFotoBase = $_REQUEST['id'] . '_' . $_REQUEST['identificacion'];
            $fotoSubida = manejarArchivo($_FILES['foto'], 'documentos/fotos/', $nombreFotoBase);
            $docente->setFoto($fotoSubida ?? $docenteActual->getFoto());
        } else {
            $docente->setFoto($docenteActual->getFoto());
        }

        if ($_FILES['hojaVida']['error'] === UPLOAD_ERR_OK) {
            $hojaVidaSubida = manejarArchivo($_FILES['hojaVida'], 'documentos/hojaVida/', $_REQUEST['identificacion']);
            $docente->setHojaVida($hojaVidaSubida ?? $docenteActual->getHojaVida());
        } else {
            $docente->setHojaVida($docenteActual->getHojaVida());
        }

        if ($_FILES['documentos']['error'] === UPLOAD_ERR_OK) {
            $documentosSubidos = manejarArchivo($_FILES['documentos'], 'documentos/soportes/', $_REQUEST['identificacion']);
            $docente->setDocumentos($documentosSubidos ?? $docenteActual->getDocumentos());
        } else {
            $docente->setDocumentos($docenteActual->getDocumentos());
        }

        // Asignar datos
        $docente->setIdentificacion($_REQUEST['identificacion']);
        $docente->setNombres($_REQUEST['nombres']);
        $docente->setApellidos($_REQUEST['apellidos']);
        $docente->setTelefono($_REQUEST['telefono']);
        $docente->setEmail($_REQUEST['email']);
        $docente->setClave($_REQUEST['pass'] ?: $docenteActual->getClave());
        $docente->setDireccion($_REQUEST['direccion']);
        $docente->setInstitucion_educativa_id($_REQUEST['institucion_educativa_id']);
        $docente->setEstado($_REQUEST['estado']);
        $docente->setRolId(4);

        $docente->modificar($_REQUEST['id']);
        break;

    case 'Eliminar':
        $docente->setId($_REQUEST['id']);
        $docente->eliminar();
        break;
}
?>

<script>
    // Redirige automáticamente después de la acción
    window.location = 'principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php';
</script>
