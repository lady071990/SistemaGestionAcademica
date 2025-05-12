<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: ../../index.php?mensaje=Acceso no autorizado');
    exit();
}

// Inicializar todas las variables con valores por defecto
$titulo = 'Adicionar';
$nameGrupo = '';
$idGrupo = null;
$idGrado = '';
$selectMenu = '<option value="">Seleccione un grado</option>';

// Solo procesar si existe el parámetro id
if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
    try {
        $array = new Grupo('id', $_REQUEST['id']);
        $titulo = 'Modificar';
        $nameGrupo = $array->getNombreGrupo() ?? '';
        $idGrado = $array->getIdGrado() ?? '';
        $idGrupo = $array->getId();
    } catch (Exception $e) {
        error_log("Error al cargar grupo: " . $e->getMessage());
    }
}

// Cargar lista de grados
try {
    $sql = "SELECT id, nombre_grado FROM grado ORDER BY nombre_grado";
    $grados = ConectorBD::ejecutarQuery($sql);
    
    foreach ($grados as $grado) {
        $selected = ($grado['id'] == $idGrado) ? 'selected' : '';
        $selectMenu .= '<option value="'.$grado['id'].'" '.$selected.'>'
                      .htmlspecialchars($grado['nombre_grado']).'</option>';
    }
} catch (Exception $e) {
    error_log("Error al cargar grados: " . $e->getMessage());
    $selectMenu = '<option value="">Error al cargar grados</option>';
}
?>

<!-- Resto del HTML permanece igual -->
<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/grupo/form-grupo-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Nombre del grupo</h2>
            
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="id_grado">Grado</label>
                    <select class="as-form-select" name="id_grado" id="id_grado" required>
                        <?= $selectMenu ?>
                    </select>
                </div>
            </div>

            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre_grupo">Nombre</label>
                    <input type="text" name="nombre_grupo" id="nombre_grupo" 
                           value="<?= htmlspecialchars($nameGrupo) ?>" 
                           required placeholder="Nombre del grupo">
                </div>
            </div>

            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?= htmlspecialchars($titulo) ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $idGrupo ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>