<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Adicionar';

require_once 'logica/clases/InstitucionEducativa.php';
$universidades = InstitucionEducativa::getListaEnObjetos(null, 'id');
$optionsUniversidades = '<option value=""></option>';
foreach ($universidades as $universidad) {
    $optionsUniversidades .= '<option value="'.$universidad->getId().'">'.$universidad->getNombre().'</option>';
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/gestion-cupos/lista-gestion-cupos.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/gestion-cupos/form-gestion-cupos-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Registrar Gestión de Cupos</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="especialidad">Especialidad <span class="as-required">*</span></label>
                    <input type="text" name="especialidad" id="especialidad" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="numero_estudiantes">Número de Estudiantes <span class="as-required">*</span></label>
                    <input type="number" name="numero_estudiantes" id="numero_estudiantes" min="1" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="institucion_educativa_id">Universidad</label>
                    <select class="as-form-select" name="institucion_educativa_id" id="institucion_educativa_id">
                        <?= $optionsUniversidades ?>
                    </select>
                </div>

                <div class="as-form-input">
                    <label class="label" for="nombre_estudiante">Nombre del Estudiante <span class="as-required">*</span></label>
                    <input type="text" name="nombre_estudiante" id="nombre_estudiante" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="turno">Turno <span class="as-required">*</span></label>
                    <select class="as-form-select" name="turno" id="turno" required>
                        <option value="mañana">Mañana</option>
                        <option value="tarde">Tarde</option>
                    </select>
                </div>

                <div class="as-form-input">
                    <label class="label" for="fecha_registro">Fecha de Registro</label>
                    <input type="date" name="fecha_registro" id="fecha_registro" value="<?= date('Y-m-d') ?>">
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>