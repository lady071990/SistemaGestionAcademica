<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: ../../index.php?mensaje=Acceso no autorizado');
    exit;
}

require_once 'logica/clases/ListaChequeo.php';
require_once 'logica/clases/InstitucionEducativa.php';
require_once 'logica/clasesGenericas/ConectorBD.php';

$titulo = 'Agregar';
$idLista = '';
$idUniversidad = $_REQUEST['idUniversidad'] ?? '';
$nombreUniversidad = $_REQUEST['nombreUniversidad'] ?? '';
$listaChequeo = null;

if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $listaChequeo = new ListaChequeo('id', $_REQUEST['id']);
    $idLista = $listaChequeo->getId();
    $idUniversidad = $listaChequeo->getInstitucion_educativa_id();

    // ⚠️ Validación: evitar que otras instituciones editen
    if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'institucion') {
        $institucionIdSesion = $_SESSION['usuario']['institucion_id'] ?? null;
        if ($idUniversidad != $institucionIdSesion) {
            header('location: principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php&mensaje=Acceso denegado');
            exit;
        }
    }

    if ($idUniversidad) {
        $institucion = new InstitucionEducativa('id', $idUniversidad);
        $nombreUniversidad = $institucion->getNombre();
    }
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php<?= $idUniversidad ? '&id_universidad=' . $idUniversidad : '' ?>" class="as-btn-back">Regresar</a>
</div>


<div class="as-form-content">
    <form name="formulario" method="post" enctype="multipart/form-data" 
        action="principal.php?CONTENIDO=layout/components/lista_chequeo/form-chequeo-action.php" autocomplete="off">
        <input type="hidden" name="id_universidad" value="<?= $idUniversidad ?>">
        <div class="as-form-margin">
            <h2><?= $titulo ?> Lista de Chequeo</h2>

            <!-- Sección Información Básica -->
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="institucion_educativa_id">Institución Educativa</label>
                    <select name="institucion_educativa_id" id="institucion_educativa_id" required>
                        <option value="">Seleccione una institución</option>
                        <?php
                        $instituciones = InstitucionEducativa::getListaEnObjetos(null, 'nombre');
                        foreach ($instituciones as $institucion) {
                            $selected = ($institucion->getId() == $idUniversidad) ? 'selected' : '';
                            echo "<option value='{$institucion->getId()}' $selected>{$institucion->getNombre()}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="fecha_subida">Fecha de Subida</label>
                    <input type="date" name="fecha_subida" id="fecha_subida" 
                           value="<?= $listaChequeo ? $listaChequeo->getFecha_subida() : date('Y-m-d') ?>" required>
                </div>
            </div>

            <!-- Sección Documentos Principales -->
            <h3 class="as-section-title">Documentos Principales</h3>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="convenio">Convenio</label>
                    <input type="file" name="convenio" id="convenio" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getConvenio()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>

            <!-- Sección Pólizas y Seguros -->
            <h3 class="as-section-title">Pólizas y Seguros</h3>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="poliza_responsabilidad">Póliza de Responsabilidad</label>
                    <input type="file" name="poliza_responsabilidad" id="poliza_responsabilidad" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getPoliza_responsabilidad()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="poliza_riesgo_biologico">Póliza de Riesgo Biológico</label>
                    <input type="file" name="poliza_riesgo_biologico" id="poliza_riesgo_biologico" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getPoliza_riesgo_biologico()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sección Documentos Complementarios -->
            <h3 class="as-section-title">Documentos Complementarios</h3>
            <div class="as-form-fields">  
                <div class="as-form-input">
                    <label class="label" for="anexo_tecnico">Anexo Técnico</label>
                    <input type="file" name="anexo_tecnico" id="anexo_tecnico" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getAnexo_tecnico()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="cronograma">Cronograma de Rotación</label>
                    <input type="file" name="cronograma" id="cronograma" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getCronograma()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="esquema_vacunacion">Esquema de Vacunación</label>
                    <input type="file" name="esquema_vacunacion" id="esquema_vacunacion" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getEsquema_vacunacion()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sección Seguridad Social -->
            <h3 class="as-section-title">Seguridad Social</h3>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="ssst">Afiliación al SSST</label>
                    <input type="file" name="ssst" id="ssst" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getSsst()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="arl">Afiliación a ARL</label>
                    <input type="file" name="arl" id="arl" accept="application/pdf" <?= $titulo === 'Agregar' ? 'required' : '' ?>>
                    <?php if ($listaChequeo && $listaChequeo->getArl()): ?>
                        <p class="as-info-text">Documento actual: Cargado</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit"><?= $titulo ?></button>
            </div>

            <input type="hidden" name="id" value="<?= $idLista ?>">
            <input type="hidden" name="accion" value="<?= $titulo ?>">
        </div>
    </form>
</div>