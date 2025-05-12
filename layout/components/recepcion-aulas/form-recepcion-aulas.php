<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
require_once 'logica/clases/RecepcionAulas.php';
require_once 'logica/clases/InstitucionEducativa.php';

$titulo = isset($_REQUEST['id']) ? 'Modificar' : 'Adicionar';

if (isset($_REQUEST['id'])) {
    $recepcionAula = new RecepcionAulas('id', $_REQUEST['id']);
} else {
    $recepcionAula = new RecepcionAulas(null, null);
}

$instituciones = InstitucionEducativa::getListaEnObjetos(null, 'nombre');
$semestres = ['1° Semestre', '2° Semestre', '3° Semestre', '4° Semestre', '5° Semestre', 
              '6° Semestre', '7° Semestre', '8° Semestre', '9° Semestre', '10° Semestre'];

?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/recepcion-aulas/lista-recepcion-aulas.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/recepcion-aulas/form-recepcion-aulas-action.php" autocomplete="off" onsubmit="return verificarDisponibilidad()">
        <div class="as-form-margin">
            <h2><?php echo $titulo; ?> Recepción de Aula</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="nombre_aula">Nombre del Aula</label>
                    <input type="text" name="nombre_aula" id="nombre_aula" 
                           value="<?= htmlspecialchars($recepcionAula->getNombreAula()) ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="nombre_estudiante">Nombre del Estudiante</label>
                    <input type="text" name="nombre_estudiante" id="nombre_estudiante" 
                           value="<?= htmlspecialchars($recepcionAula->getNombreEstudiante()) ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="nombre_docente">Nombre del Docente</label>
                    <input type="text" name="nombre_docente" id="nombre_docente" 
                           value="<?= htmlspecialchars($recepcionAula->getNombreDocente()) ?>" required>
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
                    <label class="label" for="nombre_tema">Tema a tratar</label>
                    <input type="text" name="nombre_tema" id="nombre_tema" 
                           value="<?= htmlspecialchars($recepcionAula->getNombreTema()) ?>" required>
                </div>

                <div class="as-form-input">
                    <label class="label" for="semestre">Semestre</label>
                    <select class="as-form-select" name="semestre" id="semestre" required>
                        <option value="">Seleccione un semestre</option>
                        <?php foreach ($semestres as $sem): ?>
                            <option value="<?= $sem ?>" 
                                <?= ($recepcionAula->getSemestre() == $sem) ? 'selected' : '' ?>>
                                <?= $sem ?>
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
<script>
function verificarDisponibilidad() {
    const aula = document.getElementById('nombre_aula').value;
    const fecha = document.getElementById('fecha_solicitud').value;
    const horaInicio = document.getElementById('hora_inicio').value;
    const horaFin = document.getElementById('hora_fin').value;
    const idOriginal = document.getElementById('id_original').value;

    // Verificar si los campos requeridos están llenos
    if (!aula || !fecha || !horaInicio || !horaFin) {
        return true; // Dejar que HTML5 validation maneje esto
    }

    // Validar que hora fin sea mayor a hora inicio
    if (horaInicio >= horaFin) {
        alert('La hora de fin debe ser posterior a la hora de inicio');
        return false;
    }

    // Hacer petición AJAX para verificar disponibilidad
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'verificar_disponibilidad.php', false); // Sincrónico para simplificar
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`aula=${encodeURIComponent(aula)}&fecha=${encodeURIComponent(fecha)}&hora_inicio=${encodeURIComponent(horaInicio)}&hora_fin=${encodeURIComponent(horaFin)}&id_excluir=${encodeURIComponent(idOriginal)}`);

    if (xhr.status === 200) {
        const respuesta = JSON.parse(xhr.responseText);
        if (!respuesta.disponible) {
            alert(`¡Atención! El aula ${aula} ya está reservada en ese horario.\n\nReserva existente:\n${respuesta.conflicto}`);
            return false;
        }
    }
    
    return true;
}

// Validación en tiempo real al cambiar campos relevantes
document.getElementById('nombre_aula').addEventListener('change', verificarDisponibilidadEnTiempoReal);
document.getElementById('fecha_solicitud').addEventListener('change', verificarDisponibilidadEnTiempoReal);
document.getElementById('hora_inicio').addEventListener('change', verificarDisponibilidadEnTiempoReal);
document.getElementById('hora_fin').addEventListener('change', verificarDisponibilidadEnTiempoReal);

function verificarDisponibilidadEnTiempoReal() {
    // Similar a verificarDisponibilidad() pero con AJAX asíncrono
    // Puedes implementar notificaciones en tiempo real si lo deseas
}
</script>