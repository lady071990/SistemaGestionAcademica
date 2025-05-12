<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$titulo = 'Modificar';
$selected1 = '';
$selected2 = '';
$selectMenuAsignatura = '';
$selectMenuDocente = '';
$arrayInasistencia = array();
$arrayDocente = Usuario::getListaEnObjetos("rol_id = 2", 'nombres');
$arrayAsignatura = Asignatura::getListaEnObjetos(null, 'nombre_asignatura');

if (isset($_REQUEST['id'])) {
    $arrayInasistencia = new Inasistencias('i.id', $_REQUEST['id'], 'total');
    $arrayUsuario = new Usuario('id', $arrayInasistencia->getRegistradoAEstudiante());
}

foreach ($arrayDocente as $paramD) {
    $selected1 = $paramD->getId() == $arrayInasistencia->getCreadoPorDocente() ? ' selected' : '';
    $selectMenuDocente .= '<option class="as-text-uppercase" value="' . $paramD->getId() . '"' . $selected1 . '>' . $paramD->__toString() . '</option>';
}

foreach ($arrayAsignatura as $paramA) {
    $selected2 = $paramA->getId() == $arrayInasistencia->getIdAsignatura() ? ' selected' : '';
    $selectMenuAsignatura .= '<option value="' . $paramA->getId() . '"' . $selected2 . '>' . $paramA->getNombreAsignatura() . '</option>';
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/inasistencias/lista-inasistencias.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/inasistencias/form-inasistencias-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Registrar inasistencia</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="show-label"><span>Identificación: </span><?= $arrayUsuario->getIdentificacion() ?></label>
                </div>

                <div class="as-form-input">
                    <label class="show-label"><span>Nombres: </span><?= $arrayUsuario->__toString() ?></label>
                </div>
                <?php
                if ($editar == 1 || $editar == 6) {
                ?>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="label" for="creado_por_docente">Docente</label>
                            <select class="as-form-select as-text-uppercase" name="creado_por_docente" id="creado_por_docente" required>
                                <?php
                                echo $selectMenuDocente;
                                ?>
                            </select>
                        </div>
                    </div>
                <?php
                } else {
                    echo '<input type="hidden" name="creado_por_docente" value="' . $arrayInasistencia->getCreadoPorDocente() . '">';
                }
                ?>
                <div class="as-form-fields">
                    <div class="as-form-input">
                        <label class="label" for="id_asignatura">Asignaturas</label>
                        <select class="as-form-select" name="id_asignatura" id="id_asignatura">
                            <option>Asignaturas...</option>
                            <?php
                            echo $selectMenuAsignatura;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" value="<?= $arrayInasistencia->getCantidad() ?>" required placeholder="Número de inasistencias">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="justificacion">Justificación</label>
                    <textarea class="as-form-textarea" name="justificacion" id="justificacion" cols="30" rows="10" required placeholder="Describa la justificación..."><?= $arrayInasistencia->getJustificacion() ?></textarea>
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $arrayInasistencia->getId() ?>">
        <input type="hidden" name="registrado_a_estudiante" value="<?= $arrayInasistencia->getRegistradoAEstudiante() ?>">
        <input type="hidden" name="accion" value="<?php echo $titulo ?>">
    </form>
</div>