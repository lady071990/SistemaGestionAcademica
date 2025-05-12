<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
require_once 'logica/clases/GestionCupos.php';
require_once 'logica/clases/InstitucionEducativa.php';

$titulo = 'Modificar';

if (isset($_REQUEST['id'])) {
    $gestionCupo = new GestionCupos('id', $_REQUEST['id']);
} else {
    header('location:principal.php?CONTENIDO=layout/components/gestion-cupos/lista-gestion-cupos.php');
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
            <h2>Modificar Gestión de Cupos</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="especialidad">Especialidad</label>
                    <input type="text" name="especialidad" id="especialidad" value="<?= $gestionCupo->getEspecialidad() ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="numero_estudiantes">Número de Estudiantes</label>
                    <input type="number" name="numero_estudiantes" id="numero_estudiantes" value="<?= $gestionCupo->getNumeroEstudiantes() ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="institucion_educativa_id">Universidad</label>
                    <select class="as-form-select" name="institucion_educativa_id" id="institucion_educativa_id">
                        <?php
                        require_once __DIR__.'/../../../logica/clases/InstitucionEducativa.php';
                        $universidades = InstitucionEducativa::getListaEnObjetos(null, 'nombre');
                        $universidadActual = $gestionCupo->getInstitucionEducativaId();

                        echo '<option value="">Seleccione una universidad</option>';
                        foreach ($universidades as $universidad) {
                            $selected = ($universidad->getId() == $universidadActual) ? 'selected' : '';
                            echo '<option value="'.$universidad->getId().'" '.$selected.'>'.$universidad->getNombre().'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="as-form-input">
                    <label class="label" for="nombre_estudiante">Nombre del Estudiante</label>
                    <input type="text" name="nombre_estudiante" id="nombre_estudiante" value="<?= $gestionCupo->getNombreEstudiante() ?>" required>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="fecha_registro">Fecha de Registro</label>
                    <input type="date" name="fecha_registro" id="fecha_registro" 
                           value="<?= date('Y-m-d', strtotime($gestionCupo->getFechaRegistro())) ?>">
                </div>

                <div class="as-form-input">
                    <label class="label" for="turno">Turno</label>
                    <select class="as-form-select" name="turno" id="turno" required>
                        <option value="mañana" <?= $gestionCupo->getTurno() == 'mañana' ? 'selected' : '' ?>>Mañana</option>
                        <option value="tarde" <?= $gestionCupo->getTurno() == 'tarde' ? 'selected' : '' ?>>Tarde</option>
                    </select>
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $gestionCupo->getId() ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>