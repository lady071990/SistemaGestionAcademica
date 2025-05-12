<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;

require_once 'logica/clases/RecepcionAulas.php';
require_once 'logica/clases/InstitucionEducativa.php';

// Configuración de filtros
$filtro = '';
$parametros = [];

if (isset($_REQUEST['buscar'])) {
    $condiciones = [];
    
    if (!empty($_REQUEST['nombre_aula'])) {
        $condiciones[] = "nombre_aula LIKE ?";
        $parametros[] = '%'.$_REQUEST['nombre_aula'].'%';
    }

    if (!empty($_REQUEST['nombre_estudiante'])) {
        $condiciones[] = "nombre_estudiante LIKE ?";
        $parametros[] = '%'.$_REQUEST['nombre_estudiante'].'%';
    }

    if (!empty($_REQUEST['semestre'])) {
        $condiciones[] = "semestre = ?";
        $parametros[] = $_REQUEST['semestre'];
    }

    if (!empty($condiciones)) {
        $filtro = implode(' AND ', $condiciones);
    }
}

// Obtener lista de recepción de aulas
$listaRecepcionAulas = RecepcionAulas::getListaEnObjetos($filtro, 'fecha_solicitud DESC', $parametros);

// Generar filas de la tabla
foreach ($listaRecepcionAulas as $item) {
    // Obtener nombre de la institución educativa
    $nombreInstitucion = 'No asignada';
    if ($item->getInstitucionEducativaId()) {
        $institucion = new InstitucionEducativa('id', $item->getInstitucionEducativaId());
        $nombreInstitucion = $institucion->getNombre();
    }
    
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreAula()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreEstudiante()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreDocente()}</td>";
    $lista .= "<td class='as-text-left'>$nombreInstitucion</td>";
    $lista .= "<td class='as-text-left'>{$item->getNombreTema()}</td>";
    $lista .= "<td class='as-text-center'>{$item->getSemestre()}</td>";
    $lista .= "<td class='as-text-center'>{$item->getTiempoAsignado()} h</td>";
    $lista .= "<td class='as-text-center'>" . substr($item->getHora_inicio(), 0, 5) . "</td>";
    $lista .= "<td class='as-text-center'>" . substr($item->getHora_fin(), 0, 5) . "</td>";
    $lista .= "<td class='as-text-center'>" . date('d/m/Y', strtotime($item->getFechaSolicitud())) . "</td>";
    $lista .= "<td class='as-text-center'>";
    if ($editar != 2) {
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/recepcion-aulas/form-recepcion-aulas.php&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
    }
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}

// Lista de semestres para el select
$semestres = ['1° Semestre', '2° Semestre', '3° Semestre', '4° Semestre', '5° Semestre', 
              '6° Semestre', '7° Semestre', '8° Semestre', '9° Semestre', '10° Semestre', '11° Semestre', '12° Semestre'];
?>

<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar recepción de aulas por filtros
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/recepcion-aulas/lista-recepcion-aulas.php" autocomplete="off">
                <div class="as-form-margin">
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="nombre_aula">Nombre del Aula</label>
                            <input type="text" name="nombre_aula" id="nombre_aula" placeholder="Nombre del Aula" 
                                   value="<?= isset($_REQUEST['nombre_aula']) ? htmlspecialchars($_REQUEST['nombre_aula']) : '' ?>">
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="nombre_estudiante">Nombre Estudiante</label>
                            <input type="text" name="nombre_estudiante" id="nombre_estudiante" placeholder="Nombre Estudiante"
                                   value="<?= isset($_REQUEST['nombre_estudiante']) ? htmlspecialchars($_REQUEST['nombre_estudiante']) : '' ?>">
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="label" for="semestre">Semestre</label>
                            <select class="as-form-select" name="semestre" id="semestre">
                                <option value="">Todos</option>
                                <?php foreach ($semestres as $sem): ?>
                                    <option value="<?= $sem ?>" <?= (isset($_REQUEST['semestre']) && $_REQUEST['semestre'] == $sem) ? 'selected' : '' ?>>
                                        <?= $sem ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="buscar" value="buscar">
                    <div class="as-form-button">
                        <button class="as-color-btn-green" type="submit">
                            Buscar
                        </button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/recepcion-aulas/lista-recepcion-aulas.php">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">LISTADO DE RECEPCIÓN DE AULAS</h3>
        <div class="as-form-button-back">
            <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/recepcion-aulas/form-recepcion-aulas.php">Agregar</a>
        </div>
    </div>

    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Aula</th>
                    <th scope="col">Estudiante</th>
                    <th scope="col">Docente</th>
                    <th scope="col">Institución</th>
                    <th scope="col">Tema</th>
                    <th scope="col">Semestre</th>
                    <th scope="col">Duración</th>
                    <th scope="col">Hora Inicio</th>
                    <th scope="col">Hora Fin</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?= $lista ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    const eliminar = (id) => {
        let respuesta = confirm("¿Está seguro de eliminar este registro?");
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/recepcion-aulas/form-recepcion-aulas-action.php&accion=Eliminar&id=" + id;
    }

    const clickTabShowHidden = document.querySelector("#as-tab-header-click");
    clickTabShowHidden.addEventListener("click", () => {
        const contentTab = clickTabShowHidden.nextElementSibling;
        contentTab.classList.toggle("as-tab-content-form-show");
    });
</script>