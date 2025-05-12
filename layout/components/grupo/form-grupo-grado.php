<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: ../../index.php?mensaje=Acceso no autorizado');
    exit();
}

// Inicializar variables con valores por defecto
$titulo = 'Adicionar';
$nombreGrado = 'Grado no disponible';
$nombresGrupo = 'No hay grupos registrados';
$idGrado = null;

try {
    if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
        $idGrado = (int)$_REQUEST['id'];
        
        // Consulta para obtener datos del grado
        $sqlGrado = "SELECT id, nombre_grado FROM grado WHERE id = ?";
        $resultGrado = ConectorBD::ejecutarQuery($sqlGrado, [$idGrado]);
        
        if (!empty($resultGrado)) {
            $grado = $resultGrado[0];
            $nombreGrado = htmlspecialchars($grado['nombre_grado']);
            
            // Consulta para grupos del grado
            $sqlGrupos = "SELECT nombre_grupo FROM grupo WHERE id_grado = ?";
            $grupos = ConectorBD::ejecutarQuery($sqlGrupos, [$idGrado]);
            
            if (!empty($grupos)) {
                $nombresGrupo = '';
                foreach ($grupos as $grupo) {
                    $nombresGrupo .= " [ ".htmlspecialchars($grupo['nombre_grupo'])." ] ";
                }
            }
        }
    }
} catch (Exception $e) {
    error_log("Error en form-grupo-grado.php: " . $e->getMessage());
    // Mantener los valores por defecto
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/grupo/lista-grupo.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/grupo/form-grupo-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Nombre del grupo</h2>
            <div class="as-form-input">
                <label class="show-label"><span>Grado: </span><?= $nombreGrado ?></label>
            </div>

            <div class="as-form-input">
                <label class="show-label"><span>Grupos actuales: </span><?= $nombresGrupo ?></label>
            </div>

            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre_grupo">Nombre</label>
                    <input type="text" name="nombre_grupo" id="nombre_grupo" required placeholder="Nombre del grupo">
                </div>
            </div>

            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?= htmlspecialchars($titulo) ?>
                </button>
            </div>
        </div>
        <?php if ($idGrado): ?>
        <input type="hidden" name="id_grado" value="<?= $idGrado ?>">
        <?php endif; ?>
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>