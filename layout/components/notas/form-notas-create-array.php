<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Adicionar';
$selectMenuPeriodo = '';
$selectMenuAsignatura = '';
$selectMenuTipoActividad = '';
$arrayPeriodoAcademico = PeriodoAcademico::getListaEnObjetos(null, 'inicio_periodo');
$arrayAsignatura = Asignatura::getListaEnObjetos(null, 'nombre_asignatura');
$arrayTipoActividad = TipoActividad::getListaEnObjetos(null, 'nombre_actividad');
$numeroTipoActividad = count($arrayTipoActividad);
$count = 1;

if (isset($_REQUEST['id'])) {
    $arrayUsuario = new Usuario('id', $_REQUEST['id']);
}

foreach ($arrayPeriodoAcademico as $paramA) {
    $selectMenuPeriodo .= '<option value="' . $paramA->getId() . '">' . $paramA->getNombre() . '</option>';
    $count++;
}

foreach ($arrayAsignatura as $paramA) {
    $selectMenuAsignatura .= '<option value="' . $paramA->getId() . '">' . $paramA->getNombreAsignatura() . '</option>';
}

foreach ($arrayTipoActividad as $paramG) {
    $selectMenuTipoActividad .= '<div class="as-box-calification">
                                    <div class="as-form-group">
                                        <input type="checkbox" name="actividad' . $paramG->getId() . '" id="actividad' . $paramG->getId() . '" value="' . $paramG->getId() . '" onclick="habilitarCalificacion(\'actividad' . $paramG->getId() . '\',\'nota' . $paramG->getId() . '\')">
                                        <label class="as-form-check-label" for="actividad' . $paramG->getId() . '">' . $paramG->getNombreActividad() . '</label>
                                    </div>
                                    <div class="as-form-input">
                                        <input type="text" name="nota' . $paramG->getId() . '" id="nota' . $paramG->getId() . '" disabled placeholder="Calificación" onkeypress="return valideKey(event);">
                                        <label class="hide-label" for="nota' . $paramG->getId() . '">Calificación</label>
                                    </div>
                                </div>';
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/notas/lista-notas.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/notas/form-notas-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Agregar nota</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="show-label"><span>Identificación: </span><?= $arrayUsuario->getIdentificacion() ?></label>
                </div>

                <div class="as-form-input">
                    <label class="show-label"><span>Nombres: </span><?= $arrayUsuario->__toString() ?></label>
                </div>

                <div class="as-form-fields">
                    <div class="as-form-input">
                        <label class="label" for="id_periodo_academico">Periodo académico</label>
                        <select class="as-form-select" name="id_periodo_academico" id="id_periodo_academico" required>
                            <?php
                            echo $selectMenuPeriodo;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="as-form-fields">
                    <div class="as-form-input">
                        <label class="label" for="id_asignatura">Asignaturas</label>
                        <select class="as-form-select" name="id_asignatura" id="id_asignatura" required>
                            <?php
                            echo $selectMenuAsignatura;
                            ?>
                        </select>
                    </div>
                </div>

                <?= $selectMenuTipoActividad ?>

            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $arrayUsuario->getId() ?>">
        <input type="hidden" name="id_usuario_estudiante" value="<?= $_REQUEST['id'] ?>">
        <input type="hidden" name="numero_tipo_actividad" value="<?= $numeroTipoActividad ?>">
        <input type="hidden" name="accion" value="AdicionarArray">
    </form>
</div>