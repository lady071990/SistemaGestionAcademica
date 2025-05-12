<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Adicionar';
$nombre = '';
$iniciaPeriodo = '';
$finPeriodo = '';
$idPeriodo = null;
$idPeriodoAca = '';
$selected = '';
$selectMenu = '';

if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $array = new PeriodoAcademico('id', $_REQUEST['id']);
    $periodo = PeriodoAcademico::getListaEnObjetos("id={$array->getId()}", null)[0];
    $nombre = $array->getNombre();
    $iniciaPeriodo = Generalidades::convertDate($array->getInicioPeriodo(), false);
    $finPeriodo = Generalidades::convertDate($array->getFinalizacionPeriodo(), false);
    $idPeriodoAca = $array->getIdAnioEscolar();
    $idPeriodo = $array->getId();
}

$totalAnioEscolar = AnioEscolar::getListaEnObjetos(null, null);

foreach ($totalAnioEscolar as $param) {
    $selected = $param->getId() == $idPeriodoAca ? 'selected' : '';
    $selectMenu .= '<option value="' . $param->getId() . '" ' . $selected . ' > Inicia: [' . Generalidades::convertDate($param->getInicio(), false) . '] - Finaliza: [' . Generalidades::convertDate($param->getFin(), false)  . '] - Estado: ' . Generalidades::getEstadoUsuario($param->getEstado()) . ' </option>';
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/periodo-academico/lista-periodo.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/periodo-academico/form-periodo-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Periodo Académico</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre">Nombre del periodo</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" required placeholder="Nombre del periodo">
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="inicio">Inicio del periodo</label>
                    <input type="text" name="inicio" id="inicio" value="<?php echo $iniciaPeriodo; ?>" required placeholder="Inicio del periodo">
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="fin">Finalización del periodo</label>
                    <input type="text" name="fin" id="fin" value="<?php echo $finPeriodo; ?>" required placeholder="Finalización del periodo">
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="id_anio_escolar">Año escolar</label>
                    <select class="as-form-select" name="id_anio_escolar" id="id_anio_escolar" required>
                        <?php
                        echo $selectMenu;
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
        <input type="hidden" name="id" value="<?php echo $idPeriodo; ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>