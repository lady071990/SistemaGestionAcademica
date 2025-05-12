<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Modificar';
$selected1 = '';
$selected2 = '';
$selected3 = '';
$arrayUsuario = '';
$arrayNotas = '';
$selectMenuPeriodo = '';
$selectMenuAsignatura = '';
$selectMenuTipoActividad = '';
$arrayPeriodoAcademico = PeriodoAcademico::getListaEnObjetos(null, 'inicio_periodo');
$arrayAsignatura = Asignatura::getListaEnObjetos(null, 'nombre_asignatura');
$arrayTipoActividad = TipoActividad::getListaEnObjetos(null, 'nombre_actividad');

if (isset($_REQUEST['id'])) {
    $arrayNotas = new Notas('n.id', $_REQUEST['id']);
    $arrayUsuario = new Usuario('id', $arrayNotas->getIdUsuarioEstudiante());
}

foreach ($arrayPeriodoAcademico as $paramPA) {
    $selected1 = $paramPA->getId() == $arrayNotas->getIdPeriodoAcademico() ? 'selected' : '';
    $selectMenuPeriodo .= '<option value="' . $paramPA->getId() . '"' . $selected1 . '>' . $paramPA->__toString() . '</option>';
}

foreach ($arrayAsignatura as $paramA) {
    $selected2 = $paramA->getId() == $arrayNotas->getIdAsignatura() ? 'selected' : '';
    $selectMenuAsignatura .= '<option value="' . $paramA->getId() . '"' . $selected2 . '>' . $paramA->getNombreAsignatura() . '</option>';
}

foreach ($arrayTipoActividad as $paramTA) {
    $selected3 = $paramTA->getId() == $arrayNotas->getIdTipoActividad() ? 'selected' : '';
    $selectMenuTipoActividad .= '<option value="' . $paramTA->getId() . '"' . $selected3 . '>' . $paramTA->getNombreActividad() . '</option>';
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
            <h2>Modificar Nota</h2>
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
                    <input type="number" name="nota" id="nota" value="<?= $arrayNotas->getNota() ?>" step="0.1" min="0" max="5" required placeholder="Calificación">
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $arrayNotas->getId() ?>">
        <input type="hidden" name="id_usuario_estudiante" value="<?= $arrayNotas->getIdUsuarioEstudiante() ?>">
        <input type="hidden" name="accion" value="<?php echo $titulo ?>">
    </form>
</div>