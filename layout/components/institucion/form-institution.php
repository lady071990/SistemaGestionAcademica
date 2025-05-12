<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: ../../index.php?mensaje=Acceso no autorizado');
    exit;
}

require_once 'logica/clases/InstitucionEducativa.php';

$institucion = new InstitucionEducativa(null, null);
$titulo = 'Agregar';
$nombre = '';
$direccion = '';
$telefono = '';
$email = '';
$tipo = 'Universidad';
$nombreDirectora = '';
$paginaWeb = '';
$especialidadesMedicas = false;
$idInstitucion = null;

if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $institucion = new InstitucionEducativa('id', $_REQUEST['id']);
    $nombre = $institucion->getNombre();
    $direccion = $institucion->getDireccion();
    $telefono = $institucion->getTelefono();
    $email = $institucion->getEmail();
    $tipo = $institucion->getTipo();
    $nombreDirectora = $institucion->getNombreDirectora();
    $paginaWeb = $institucion->getPaginaWeb();
    $programas = $institucion->getProgramas();
    $especialidadesMedicas = (bool)$institucion->getEspecialidadesMedicas();
    $idInstitucion = $institucion->getId();
}
?>
<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/institucion/lista-institucion.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/institucion/form-institution-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2><?= $titulo ?> Institución Educativa</h2>
            
            <!-- Campos básicos de la institución -->
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($nombre) ?>" required placeholder="Nombre de la institución">
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="tipo">Tipo de Institución</label>
                    <select class="as-form-select" name="tipo" id="tipo" required>
                        <option value="Universidad" <?= $tipo == 'Universidad' ? 'selected' : '' ?>>Universidad</option>
                        <option value="Instituto" <?= $tipo == 'Instituto' ? 'selected' : '' ?>>Instituto</option>
                        <option value="Colegio" <?= $tipo == 'Colegio' ? 'selected' : '' ?>>Colegio</option>
                        <option value="Escuela" <?= $tipo == 'Escuela' ? 'selected' : '' ?>>Escuela</option>
                        <option value="Hospital" <?= $tipo == 'Hospital' ? 'selected' : '' ?>>Hospital</option>
                    </select>
                </div>
            </div>
            
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($direccion) ?>" placeholder="Dirección completa">
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="telefono">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" value="<?= htmlspecialchars($telefono) ?>" placeholder="Formato: +XX XXX XXX XXXX">
                </div>
            </div>
            
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required placeholder="correo@institucion.edu">
                </div>
                
                <div class="as-form-input">
                    <label class="label" for="nombreDirectora">Nombre del Director(a)</label>
                    <input type="text" name="nombreDirectora" id="nombreDirectora" value="<?= htmlspecialchars($nombreDirectora) ?>" placeholder="Nombre completo">
                </div>
            </div>
            
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="paginaWeb">Página Web</label>
                    <input type="url" name="paginaWeb" id="paginaWeb" value="<?= htmlspecialchars($paginaWeb) ?>" placeholder="https://www.ejemplo.com">
                </div>
            </div>
            
            <div id="programas-academicos-section">
                <h3>Oferta Educativa</h3>
                <div id="lista-programas">
                    <!-- Aquí se cargarían los programas existentes en modo edición -->
                </div>

                <div class="as-form-fields">
                    <div class="as-form-input">
                        <label class="label" for="tipo-programa">Tipo de Programa</label>
                        <select class="as-form-select" name="tipo_programa" id="tipo-programa">
                            <option value="">Seleccione un tipo</option>
                            <?php if ($tipo == 'Universidad'): ?>
                                <option value="Pregrado">Pregrado</option>
                                <option value="Posgrado">Posgrado</option>
                                <option value="Maestría">Maestría</option>
                                <option value="Doctorado">Doctorado</option>
                                <option value="Residencias Médicas">Residencias Médicas</option>
                            <?php elseif ($tipo == 'Instituto'): ?>
                                <option value="Técnico">Técnico</option>
                                <option value="Tecnológico">Tecnológico</option>
                                <option value="Certificación">Certificación</option>
                            <?php elseif ($tipo == 'Colegio'): ?>
                                <option value="Bachillerato">Bachillerato</option>
                                <option value="Media Técnica">Media Técnica</option>
                                <option value="Básica Secundaria">Básica Secundaria</option>
                             <?php elseif ($tipo == 'Hospital'): ?>
                                <option value="Residencias medicas">Residencias</option>
                                <option value="Internado Rotatorio">Internado Rotatorio</option>   
                            <?php elseif ($tipo == 'Escuela'): ?>
                                <option value="Básica Primaria">Básica Primaria</option>
                                <option value="Pre-escolar">Pre-escolar</option>
                                <option value="Educación para adultos">Educación para adultos</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="as-form-input">
                        <label class="label" for="nombre-programa">Nombre del Programa</label>
                        <input type="text" name="nombre_programa" id="nombre-programa" placeholder="Ej: Medicina, Bachillerato técnico, etc.">
                    </div>
                    <div class="as-form-input">
                        <button type="button" id="agregar-programa" class="as-btn-small">Agregar Programa</button>
                    </div>
                </div>
            
            
            <div class="as-form-input">
                <label class="label" for="especialidadesMedicas">Especialidades Médicas</label>
                <select class="as-form-select" name="especialidadesMedicas" id="especialidadesMedicas">
                    <option value="">Seleccione una opción</option>
                    <option value="Medicina General" <?= $especialidadesMedicas == 'Medicina General' ? 'selected' : '' ?>>Medicina General</option>
                    <option value="Pediatría" <?= $especialidadesMedicas == 'Pediatría' ? 'selected' : '' ?>>Pediatría</option>
                    <option value="Cirugía" <?= $especialidadesMedicas == 'Cirugía' ? 'selected' : '' ?>>Cirugía</option>
                    <option value="Ginecología" <?= $especialidadesMedicas == 'Ginecología' ? 'selected' : '' ?>>Ginecología</option>
                    <option value="Cardiología" <?= $especialidadesMedicas == 'Cardiología' ? 'selected' : '' ?>>Cardiología</option>
                    <option value="Medicina Interna" <?= $especialidadesMedicas == 'Medicina Interna' ? 'selected' : '' ?>>Medicina Interna</option>
                    <option value="Psiquiatría" <?= $especialidadesMedicas == 'Psiquiatría' ? 'selected' : '' ?>>Psiquiatría</option>
                    <option value="Enfermería" <?= $especialidadesMedicas == 'Enfermería' ? 'selected' : '' ?>>Enfermería</option>
                    <option value="Ortopedia" <?= $especialidadesMedicas == 'Ortopedia' ? 'selected' : '' ?>>Ortopedia</option>
                    <option value="Medicina Crítica" <?= $especialidadesMedicas == 'Medicina Crítica' ? 'selected' : '' ?>>Medicina Crítica</option>
                    <option value="Neurocirugía" <?= $especialidadesMedicas == 'Neurocirugía' ? 'selected' : '' ?>>Neurocirugía</option>
                    <option value="Anestesiología" <?= $especialidadesMedicas == 'Anestesiología' ? 'selected' : '' ?>>Anestesiología</option>
                    <option value="Enfermería en cuidado crítico" <?= $especialidadesMedicas == 'Enfermería en cuidado crítico' ? 'selected' : '' ?>>Enfermería en cuidado crítico</option>
                    <option value="Enfermería materno perinatal" <?= $especialidadesMedicas == 'Enfermería materno perinatal' ? 'selected' : '' ?>>Enfermería materno perinatal</option>
                    <option value="Enfermería oncológica" <?= $especialidadesMedicas == 'Enfermería oncológica' ? 'selected' : '' ?>>Enfermería oncológica</option>
                    <option value="Fisioterapia" <?= $especialidadesMedicas == 'Fisioterapia' ? 'selected' : '' ?>>Fisioterapia</option>
                    <option value="Psicología" <?= $especialidadesMedicas == 'Psicología' ? 'selected' : '' ?>>Psicología</option>
                    <option value="Nutrición" <?= $especialidadesMedicas == 'Nutrición' ? 'selected' : '' ?>>Nutrición</option>
                    <option value="Auxiliar de enfermería" <?= $especialidadesMedicas == 'Auxiliar de enfermería' ? 'selected' : '' ?>>Auxiliar de enfermería</option>
                    <option value="Regencia en farmacia" <?= $especialidadesMedicas == 'Regencia en farmacia' ? 'selected' : '' ?>>Regencia en farmacia</option>
                    <option value="Radiodiagnóstico" <?= $especialidadesMedicas == 'Radiodiagnóstico' ? 'selected' : '' ?>>Radiodiagnóstico</option>
                </select>
            </div>  
        </div>
            
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?= $titulo ?>
                </button>
            </div>
            
            <input type="hidden" name="id" value="<?= $idInstitucion ?>">
            <input type="hidden" name="accion" value="<?= $titulo ?>">
            <input type="hidden" name="programas_eliminados" id="programas-eliminados" value="">
            <input type="hidden" name="programas_agregados" id="programas-agregados" value="">
        </div>
    </form>
</div>

<script>
// Array para almacenar programas temporalmente
let programasTemporales = [];
let programasAEliminar = [];

// Actualizar opciones de programas cuando cambia el tipo de institución
document.getElementById('tipo').addEventListener('change', function() {
    const tipoProgramaSelect = document.getElementById('tipo-programa');
    const tipoSeleccionado = this.value;
    
    // Limpiar opciones actuales
    tipoProgramaSelect.innerHTML = '<option value="">Seleccione un tipo</option>';
    
    // Definir opciones según tipo de institución
    const opciones = {
        'Universidad': [
            {value: 'Pregrado', text: 'Pregrado'},
            {value: 'Posgrado', text: 'Posgrado'},
            {value: 'Maestría', text: 'Maestría'},
            {value: 'Doctorado', text: 'Doctorado'},
            {value: 'Residencias Médicas', text: 'Residencias Médicas'}
        ],
        'Instituto': [
            {value: 'Técnico', text: 'Técnico'},
            {value: 'Tecnológico', text: 'Tecnológico'},
            {value: 'Certificación', text: 'Certificación'}
        ],
        'Colegio': [
            {value: 'Bachillerato', text: 'Bachillerato'},
            {value: 'Media Técnica', text: 'Media Técnica'},
            {value: 'Básica Secundaria', text: 'Básica Secundaria'}
        ],
        'Escuela': [
            {value: 'Básica Primaria', text: 'Básica Primaria'},
            {value: 'Pre-escolar', text: 'Pre-escolar'},
            {value: 'Educación para adultos', text: 'Educación para adultos'}
        ]
    };
    
    // Agregar nuevas opciones
    if (opciones[tipoSeleccionado]) {
        opciones[tipoSeleccionado].forEach(opcion => {
            const optionElement = document.createElement('option');
            optionElement.value = opcion.value;
            optionElement.textContent = opcion.text;
            tipoProgramaSelect.appendChild(optionElement);
        });
    }
});

// Manejar la adición de programas
document.getElementById('agregar-programa').addEventListener('click', function() {
    const tipo = document.getElementById('tipo-programa').value;
    const nombre = document.getElementById('nombre-programa').value.trim();
    
    if (!tipo || !nombre) {
        alert('Por favor complete el tipo y nombre del programa');
        return;
    }
    
    // Agregar al array temporal
    const programa = {
        tipo: tipo,
        nombre: nombre,
        temporalId: Date.now() // ID temporal para identificación
    };
    
    programasTemporales.push(programa);
    actualizarListaProgramas();
    actualizarInputOculto();
    
    // Limpiar campos
    document.getElementById('nombre-programa').value = '';
});

// Resto del código JavaScript permanece igual...
function actualizarListaProgramas() {
    const lista = document.getElementById('lista-programas');
    lista.innerHTML = '';
    
    programasTemporales.forEach(programa => {
        const item = document.createElement('div');
        item.className = 'programa-item';
        item.dataset.id = programa.temporalId;
        item.innerHTML = `
            <span><strong>${programa.tipo}:</strong> ${programa.nombre}</span>
            <button type="button" class="eliminar-programa" data-id="${programa.temporalId}">×</button>
        `;
        lista.appendChild(item);
    });
}

// Manejar eliminación de programas
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('eliminar-programa')) {
        const programaId = e.target.dataset.id;
        
        // Eliminar del array temporal
        programasTemporales = programasTemporales.filter(p => p.temporalId != programaId);
        actualizarListaProgramas();
        actualizarInputOculto();
    }
});

// Actualizar input oculto con los programas en formato JSON
function actualizarInputOculto() {
    const programasParaGuardar = programasTemporales.map(p => ({
        tipo: p.tipo,
        nombre: p.nombre
    }));
    document.getElementById('programas-agregados').value = JSON.stringify(programasParaGuardar);
}
</script>