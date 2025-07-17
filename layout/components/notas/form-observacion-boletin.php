<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../../index.php?mensaje=Acceso no autorizado');

// 🔽 AGREGA ESTA LÍNEA para incluir la clase
require_once 'logica/clases/ObservacionBoletin.php';
require_once 'logica/clasesGenericas/ConectorBD.php';


// PRIMERO obtener las variables de la URL
$idUsuario = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
$accion = isset($_REQUEST['accion']) ? $_REQUEST['accion'] : null;

if (!$idUsuario || !$accion) {
    die("Error: Falta el parámetro 'id' o 'accion' en la URL.");
}

// Obtener el año escolar activo
$arrayAnioEscolar = AnioEscolar::getListaEnObjetos('estado=1', null);
if (!$arrayAnioEscolar || count($arrayAnioEscolar) === 0) {
    die("Error: No hay año escolar activo.");
}
$idAnioEscolar = $arrayAnioEscolar[0]->getId();

// Buscar si ya existe una observación previa
$observacionTexto = '';
$observacionExistente = ObservacionBoletin::buscarObservacion($idUsuario, $idAnioEscolar);
if ($observacionExistente) {
    $observacionTexto = $observacionExistente->getObservacion();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Observación</title>
    <link rel="stylesheet" href="../../../css/main.css">
</head>
<body>
<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/notas/form-observacion-boletin-action.php">
        <h3>Agregar Observación al Boletín</h3>
        <input type="hidden" name="id_usuario" value="<?= $idUsuario ?>">
        <input type="hidden" name="id_anio_escolar" value="<?= $idAnioEscolar ?>">
        <div class="as-form-fields">
            <label for="observacion">Observación:</label>
            <textarea name="observacion" id="observacion" rows="6" style="width:100%;"><?= htmlspecialchars($observacionTexto) ?></textarea>
        </div>
        <div class="as-form-button">
            <button type="submit" class="as-color-btn-green">Guardar</button>
            <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
