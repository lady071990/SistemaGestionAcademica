<?php
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$docente = new Usuario(null, null);
switch ($_REQUEST['accion']) {
  case 'Adicionar':
    // Subir archivo
    $nombreArchivo=$_REQUEST['id'].'_'.$_REQUEST['identificacion'];
    $ruta='documentos';
    $extension= substr($_FILES['foto']['name'], strrpos($_FILES['foto']['name'], '.')); //Obtiene la extension del archivo archivo.extension
    move_uploaded_file($_FILES['foto']['tmp_name'], "$ruta/fotos/$nombreArchivo$extension");
    // Fin subir archivo 
      
    $docente->setIdentificacion($_REQUEST['identificacion']);
    $docente->setNombres($_REQUEST['nombres']);
    $docente->setApellidos($_REQUEST['apellidos']);
    $docente->setTelefono($_REQUEST['telefono']);
    $docente->setEmail($_REQUEST['email']);
    $docente->setDireccion($_REQUEST['direccion']);
    $docente->setInstitucion_educativa_id($_REQUEST['institucion_educativa_id']);
    
    $nombreArchivo = $_REQUEST['identificacion'];
    $ruta = 'documentos/hojaVida';
    move_uploaded_file($_FILES['hojaVida']['tmp_name'], "$ruta/hojaVida/$nombreArchivo.pdf");

    $docente->setHojaVida($nombreArchivo . '.pdf');
    
    $nombreArchivos = $_REQUEST['identificacion'];
    $ruta = 'documentos/hojaVida';
    move_uploaded_file($_FILES['documentos']['tmp_name'], "$ruta/soportes/$nombreArchivo.pdf");

    $docente->setDocumentos($nombreArchivos . '.pdf');
    
    $docente->setFoto($nombreArchivo . $extension); // PENDIENTE EXTENCION FOTO 14.2
    
    $docente->setEstado($_REQUEST['estado']);
    $docente->setRolId(4);
    $docente->guardar();
    break;
  case 'Modificar':
    // Subir archivo
    $nombreArchivo=$_REQUEST['id'].'_'.$_REQUEST['identificacion'];
    $ruta='documentos';
    $extension= substr($_FILES['foto']['name'], strrpos($_FILES['foto']['name'], '.')); //Obtiene la extension del archivo archivo.extension
    move_uploaded_file($_FILES['foto']['tmp_name'], "$ruta/fotos/$nombreArchivo$extension");
    // Fin subir archivo  
      
    $docente->setIdentificacion($_REQUEST['identificacion']);
    $docente->setNombres($_REQUEST['nombres']);
    $docente->setApellidos($_REQUEST['apellidos']);
    $docente->setTelefono($_REQUEST['telefono']);
    $docente->setEmail($_REQUEST['email']);
    $docente->setClave($_REQUEST['pass']);
    $docente->setDireccion($_REQUEST['direccion']);
    $docente->setInstitucion_educativa_id($_REQUEST['institucion_educativa_id']);
    
    $nombreArchivo = $_REQUEST['identificacion'];
    $ruta = 'documentos';
    move_uploaded_file($_FILES['hojaVida']['tmp_name'], "$ruta/hojaVida/$nombreArchivo.pdf");

    $docente->setHojaVida($nombreArchivo . '.pdf');
    
    $nombreArchivos = $_REQUEST['identificacion'];
    $ruta = 'documentos';
    move_uploaded_file($_FILES['documentos']['tmp_name'], "$ruta/soportes/$nombreArchivo.pdf");

    $docente->setDocumentos($nombreArchivos . '.pdf');
    
    $docente->setFoto($nombreArchivo . $extension); // PENDIENTE EXTENCION FOTO 14.2
    
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
  window.location = 'principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php';
</script>