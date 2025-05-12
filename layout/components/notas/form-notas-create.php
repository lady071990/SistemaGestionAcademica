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
    $selectMenuTipoActividad .= '<option value="' . $paramG->getId() . '">' . $paramG->getNombreActividad() . '</option>';
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

                <div class="as-form-fields">
                    <div class="as-form-input">
                        <label class="label" for="id_tipo_actividad">Tipo de actividad</label>
                        <select class="as-form-select" name="id_tipo_actividad" id="id_tipo_actividad" required>
                            <?php
                            echo $selectMenuTipoActividad;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="nota">Calificación</label>
                    <input type="text" name="nota" id="nota" required placeholder="Calificación" onkeypress="return valideKey(event);">
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $arrayUsuario->getId() ?>">
        <input type="hidden" name="id_usuario_estudiante" value="<?= $_REQUEST['id'] ?>">
        <input type="hidden" name="accion" value="<?php echo $titulo ?>">
    </form>
</div>