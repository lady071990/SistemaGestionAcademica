<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Acceso no autorizado');
$array = new AnioEscolar(null, null);
$titulo = 'Adicionar';
$inicio = '';
$fin = '';
$idAnioEscolar = null;
$idInstitucion = '';
$selected = '';
$select = '';
$selectMenu = '<option value="">Seleccione una institución</option>';
$selectMenuOpc = '';

if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $array = new AnioEscolar('id', $_REQUEST['id']);
    $inicio = Generalidades::convertDate($array->getInicio(), false);
    $fin = Generalidades::convertDate($array->getFin(), false);
    $idInstitucion = $array->getIdInstitucion();
    $idAnioEscolar = $array->getId();
}

// SOLUCIÓN: Consulta directa a la base de datos
$cadenaSQL = "SELECT id, nombre FROM institucion_educativa ORDER BY nombre";
$resultados = ConectorBD::ejecutarQuery($cadenaSQL);

foreach ($resultados as $institucion) {
    $selected = $institucion['id'] == $idInstitucion ? 'selected' : '';
    $selectMenu .= '<option value="' . $institucion['id'] . '" ' . $selected . '>' . htmlspecialchars($institucion['nombre']) . '</option>';
}

// Resto del código para el estado (se mantiene igual)
for ($i = 1; $i < 3; $i++) {
    $select = $array->getEstado() == $i ? 'selected' : '';
    $selectMenuOpc .= '<option value="' . $i . '" ' . $select . '>' . Generalidades::getEstadoUsuario($i) . '</option>';
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/anio-escolar/lista-anio.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/anio-escolar/form-anio-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Año escolar</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="inicio">Inicio</label>
                    <input type="text" name="inicio" id="inicio" value="<?php echo $inicio; ?>" required placeholder="Fecha inicial">
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="fin">Fin</label>
                    <input type="text" name="fin" id="fin" value="<?php echo $fin; ?>" required placeholder="Fecha final">
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="id_institucion">Institución</label>
                    <select class="as-form-select" name="id_institucion" id="id_institucion" required>
                        <?php
                        echo $selectMenu;
                        ?>
                    </select>
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="estado">Estado</label>
                    <select class="as-form-select" name="estado" id="estado" required>
                        <?php
                        for ($i = 1; $i < 3; $i++) {
                            $select = $array->getEstado() == $i ? 'selected' : '';
                            $selectMenuOpc .= '<option value="' . $i . '" ' . $select . ' >' . Generalidades::getEstadoUsuario($i) . ' </option>';
                        }
                        echo $selectMenuOpc;
                        ?>
                    </select>
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $idAnioEscolar; ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>