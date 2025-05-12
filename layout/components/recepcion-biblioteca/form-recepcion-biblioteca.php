<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
require_once 'logica/clases/RecepcionBiblioteca.php';
require_once 'logica/clases/InstitucionEducativa.php';

$titulo = isset($_REQUEST['id']) ? 'Modificar' : 'Adicionar';

if (isset($_REQUEST['id'])) {
    $recepcionAula = new RecepcionBiblioteca('id', $_REQUEST['id']);
} else {
    $recepcionAula = new RecepcionBiblioteca(null, null);
}

$instituciones = InstitucionEducativa::getListaEnObjetos(null, 'nombre');

?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/recepcion-biblioteca/lista-recepcion-biblioteca.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/recepcion-biblioteca/form-recepcion-biblioteca-action.php" autocomplete="off" onsubmit="return verificarDisponibilidad()">
        <div class="as-form-margin">
            <h2><?php echo $titulo; ?> Recepción de Biblioteca</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="nombre_proyecto">Nombre del Proyecto</label>
                    <input type="text" name="nombre_proyecto" id="nombre_proyecto" 
                           value="<?= htmlspecialchars($recepcionAula->getNombre_proyecto()) ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="numero_computadores">Número de Computadores</label>
                    <input type="text" name="numero_computadores" id="numero_computadores" 
                           value="<?= htmlspecialchars($recepcionAula->getNumero_computadores()) ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="numero_estudiantes">Número de Estudiantes</label>
                    <input type="text" name="numero_estudiantes" id="numero_estudiantes" 
                           value="<?= htmlspecialchars($recepcionAula->getNumero_estudiantes()) ?>" required>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="nombre_estudiantes">Nombre de Estudiantes</label>
                    <input type="text" name="nombre_estudiantes" id="nombre_estudiantes" 
                           value="<?= htmlspecialchars($recepcionAula->getNombre_estudiantes()) ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="institucion_educativa_id">Institución Educativa</label>
                    <select class="as-form-select" name="institucion_educativa_id" id="institucion_educativa_id" required>
                        <option value="">Seleccione una institución</option>
                        <?php foreach ($instituciones as $institucion): ?>
                            <option value="<?= $institucion->getId() ?>" 
                                <?= ($recepcionAula->getInstitucionEducativaId() == $institucion->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($institucion->getNombre()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="tiempo_asignado">Tiempo Asignado (horas)</label>
                    <select class="as-form-select" name="tiempo_asignado" id="tiempo_asignado" required>
                        <option value="">Seleccione tiempo</option>
                        <option value="1" <?= ($recepcionAula->getTiempoAsignado() == '1') ? 'selected' : '' ?>>1 hora</option>
                        <option value="2" <?= ($recepcionAula->getTiempoAsignado() == '2') ? 'selected' : '' ?>>2 horas</option>
                        <option value="3" <?= ($recepcionAula->getTiempoAsignado() == '3') ? 'selected' : '' ?>>3 horas</option>
                        <option value="4" <?= ($recepcionAula->getTiempoAsignado() == '4') ? 'selected' : '' ?>>4 horas</option>
                        <option value="4" <?= ($recepcionAula->getTiempoAsignado() == '4') ? 'selected' : '' ?>>5 horas</option>
                    </select>
                </div>

                <div class="as-form-input">
                    <label class="label" for="hora_inicio">Hora de Inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" 
                           value="<?= $recepcionAula->getHora_inicio() ?: '08:00' ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="hora_fin">Hora de Fin</label>
                    <input type="time" name="hora_fin" id="hora_fin" 
                           value="<?= $recepcionAula->getHora_fin() ?: '09:00' ?>" required>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="fecha_solicitud">Fecha de Solicitud</label>
                    <input type="date" name="fecha_solicitud" id="fecha_solicitud" 
                           value="<?= date('Y-m-d', strtotime($recepcionAula->getFechaSolicitud() ?: 'now')) ?>" required>
                </div>
                
                <div class="as-form-button">
                    <button class="as-color-btn-green" type="submit">
                        <?php echo $titulo; ?>
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $recepcionAula->getId() ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>