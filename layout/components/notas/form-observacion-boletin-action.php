<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../../index.php?mensaje=Acceso no autorizado');

// 🔽 AGREGA ESTA LÍNEA para incluir la clase
require_once 'logica/clases/ObservacionBoletin.php';
require_once 'logica/clasesGenericas/ConectorBD.php';

// (si usas otras clases, inclúyelas también aquí)

$idUsuario = $_POST['id_usuario'];
$idAnioEscolar = $_POST['id_anio_escolar'];
$texto = trim($_POST['observacion']);

// Verifica si ya existe la observación
$observacion = ObservacionBoletin::buscarObservacion($idUsuario, $idAnioEscolar);

if ($observacion) {
    // Actualizar
    $observacion->setObservacion($texto);  // usa el setter correcto
    $observacion->modificar($observacion->getId());
} else {
    // Crear
    $nueva = new ObservacionBoletin();
    $nueva->setId_usuario_estudiante($idUsuario);
    $nueva->setId_anio_escolar($idAnioEscolar);
    $nueva->setObservacion($texto);
    $nueva->setFecha_registro(date('Y-m-d H:i:s'));
    $nueva->guardar();
}
?>
<script>
  window.location = 'principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php';
</script>
