<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();

$docente = new Usuario(null, null);

// Función para manejar archivos
function manejarArchivo($fileInput, $rutaDestino, $nombreBase) {
    // Verificar si el archivo se subió correctamente
    if (!isset($fileInput) || $fileInput['error'] !== UPLOAD_ERR_OK) {
        error_log("⚠️ Error subiendo archivo: " . ($fileInput['error'] ?? 'Archivo no recibido'));
        return null;
    }
    
    // Verificar que es un archivo válido
    if (!is_uploaded_file($fileInput['tmp_name'])) {
        error_log("❌ Intento de acceso a archivo no subido: " . $fileInput['name']);
        return null;
    }
    
    // Crear directorio si no existe
    if (!file_exists($rutaDestino)) {
        mkdir($rutaDestino, 0777, true);
    }
    
    $extension = strtolower(pathinfo($fileInput['name'], PATHINFO_EXTENSION));
    $nombreArchivo = $nombreBase . '.' . $extension;
    $rutaCompleta = $rutaDestino . $nombreArchivo;
    
    if (move_uploaded_file($fileInput['tmp_name'], $rutaCompleta)) {
        return $nombreArchivo;
    } else {
        error_log("❌ No se pudo mover el archivo a $rutaCompleta");
        return null;
    }
}

switch ($_REQUEST['accion']) {
    case 'Adicionar':
    // Preparar nombre base para archivos únicos
    $nombreBaseArchivos = $_REQUEST['identificacion'] . '_' . time();

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
    $docente->setEstado($_REQUEST['estado']);
    $docente->setRolId(2);
    
    $docente->setHojaVida($hojaVidaSubida ?? '');
    $docente->setDocumentos($documentosSubidos ?? '');
    
    $docente->setTipoVinculacion($_REQUEST['tipo_vinculacion']);
    $docente->setExperienciaLaboral($_REQUEST['experiencia_laboral']);
    $docente->setCertificacionPostgrado($_REQUEST['certificacion_postgrado']);
    $docente->setFechaCertificacion($_REQUEST['certificacion_postgrado'] == 1 ? $_REQUEST['fecha_certificacion'] : null);
    $docente->setPerfilProfesional($_REQUEST['perfil_profesional']);

    
    // Validar fecha de certificación no mayor a 6 meses
    /*
    if ($_REQUEST['certificacion_postgrado'] == 1) {
        $fechaCertificacion = new DateTime($_REQUEST['fecha_certificacion']);
        $hoy = new DateTime();
        $diferencia = $hoy->diff($fechaCertificacion);

        if ($diferencia->m > 6 || $diferencia->y > 0) {
            die("La fecha de certificación no puede ser mayor a 6 meses");
        }
    }
    */
    // Guardar en base de datos
    $docente->guardar();
    break;
    
  // Actualiza el case 'Modificar' con esta versión mejorada:
case 'Modificar':
    $docenteActual = new Usuario('id', $_REQUEST['id']);
    
    // Inicializar variables para documentos
    $hojaVidaSubida = $docenteActual->getHojaVida();
    $documentosSubidos = $docenteActual->getDocumentos();
    
    // Manejar hoja de vida si se subió un nuevo archivo
    if (isset($_FILES['hojaVida']) && $_FILES['hojaVida']['error'] === UPLOAD_ERR_OK) {
        $hojaVidaSubida = manejarArchivo($_FILES['hojaVida'], 'documentos/hojaVida/', $_REQUEST['identificacion']);
    }
    
    // Manejar documentos si se subió un nuevo archivo
    if (isset($_FILES['documentos']) && $_FILES['documentos']['error'] === UPLOAD_ERR_OK) {
        $documentosSubidos = manejarArchivo($_FILES['documentos'], 'documentos/soportes/', $_REQUEST['identificacion']);
    }
    
    // Asignar datos  
    $docente->setIdentificacion($_REQUEST['identificacion']);
    $docente->setNombres($_REQUEST['nombres']);
    $docente->setApellidos($_REQUEST['apellidos']);
    $docente->setTelefono($_REQUEST['telefono']);
    $docente->setEmail($_REQUEST['email']);
    if (!empty($_REQUEST['pass'])) {
        $docente->setClave($_REQUEST['pass']);
    }
    $docente->setDireccion($_REQUEST['direccion']);
    $docente->setEstado($_REQUEST['estado']);
    $docente->setRolId(2);
    $docente->setHojaVida($hojaVidaSubida);
    $docente->setDocumentos($documentosSubidos);
    
    $docente->setTipoVinculacion($_REQUEST['tipo_vinculacion']);
    $docente->setExperienciaLaboral($_REQUEST['experiencia_laboral']);
    $docente->setCertificacionPostgrado($_REQUEST['certificacion_postgrado']);
    $docente->setFechaCertificacion($_REQUEST['certificacion_postgrado'] == 1 ? $_REQUEST['fecha_certificacion'] : null);
    $docente->setPerfilProfesional($_REQUEST['perfil_profesional']);

    
    // Validar fecha de certificación no mayor a 6 meses
    /*
    if ($_REQUEST['certificacion_postgrado'] == 1) {
        $fechaCertificacion = new DateTime($_REQUEST['fecha_certificacion']);
        $hoy = new DateTime();
        $diferencia = $hoy->diff($fechaCertificacion);

        if ($diferencia->m > 6 || $diferencia->y > 0) {
            die("La fecha de certificación no puede ser mayor a 6 meses");
        }
    }
    */
    $docente->modificar($_REQUEST['id']);
    break;
        
      case 'Eliminar':
        $docente->setId($_REQUEST['id']);
        $docente->eliminar();
        break;
    }

if ($editar == 1 || $editar == 6) {
?>
  <script>
    window.location = 'principal.php?CONTENIDO=layout/components/docente/lista-docente.php';
  </script>
<?php
} else {
?>
  <script>
    window.location = 'principal.php?CONTENIDO=layout/components/docente/lista-asignacion-docente.php';
  </script>
<?php
}
?>