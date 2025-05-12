<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;

require_once 'logica/clases/RecepcionBiblioteca.php';
require_once 'logica/clases/InstitucionEducativa.php';

// Configuración de filtros
$filtro = '';
$parametros = [];

if (isset($_REQUEST['buscar'])) {
    $condiciones = [];
    
    if (!empty($_REQUEST['nombre_proyecto'])) {
        $condiciones[] = "nombre_proyecto LIKE ?";
        $parametros[] = '%'.$_REQUEST['nombre_proyecto'].'%';
    }

    if (!empty($_REQUEST['nombre_estudiantes'])) {
        $condiciones[] = "nombre_estudiantes LIKE ?";
        $parametros[] = '%'.$_REQUEST['nombre_estudiantes'].'%';
    }

    if (!empty($condiciones)) {
        $filtro = implode(' AND ', $condiciones);
    }
}

// Obtener lista de recepción de aulas
$listaRecepcionAulas = RecepcionBiblioteca::getListaEnObjetos($filtro, 'fecha_solicitud DESC', $parametros);

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
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNumero_computadores()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombre_proyecto()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNumero_estudiantes()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombre_estudiantes()}</td>";
    $lista .= "<td class='as-text-left'>$nombreInstitucion</td>";
    $lista .= "<td class='as-text-center'>{$item->getTiempoAsignado()} horas</td>";
    $lista .= "<td class='as-text-center'>" . substr($item->getHora_inicio(), 0, 5) . "</td>";
    $lista .= "<td class='as-text-center'>" . substr($item->getHora_fin(), 0, 5) . "</td>";
    $lista .= "<td class='as-text-center'>" . date('d/m/Y', strtotime($item->getFechaSolicitud())) . "</td>";
    $lista .= "<td class='as-text-center'>";
    if ($editar != 2) {
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/recepcion-biblioteca/form-recepcion-biblioteca.php&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
    }
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}
?>

<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar recepción de biblioteca por filtros
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/recepcion-biblioteca/lista-recepcion-biblioteca.php" autocomplete="off">
                <div class="as-form-margin">
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="nombre_proyecto">Nombre del Proyecto</label>
                            <input type="text" name="nombre_proyecto" id="nombre_proyecto" placeholder="Nombre del Proyecto" 
                                   value="<?= isset($_REQUEST['nombre_proyecto']) ? htmlspecialchars($_REQUEST['nombre_proyecto']) : '' ?>">
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="nombre_estudiantes">Nombre Estudiantes</label>
                            <input type="text" name="nombre_estudiantes" id="nombre_estudiantes" placeholder="Nombre Estudiantes"
                                   value="<?= isset($_REQUEST['nombre_estudiantes']) ? htmlspecialchars($_REQUEST['nombre_estudiantes']) : '' ?>">
                        </div>
                    </div>
                    <input type="hidden" name="buscar" value="buscar">
                    <div class="as-form-button">
                        <button class="as-color-btn-green" type="submit">
                            Buscar
                        </button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/recepcion-biblioteca/lista-recepcion-biblioteca.php">
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
        <h3 class="as-title-table">LISTADO DE RECEPCIÓN DE BIBLIOTECA</h3>
        <div class="as-form-button-back">
            <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/recepcion-biblioteca/form-recepcion-biblioteca.php">Agregar</a>
        </div>
    </div>

    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Número Computadores</th>
                    <th scope="col">Nombre Proyecto</th>
                    <th scope="col">Número Estudiantes</th>
                    <th scope="col">Nombre Estudiantes</th>
                    <th scope="col">Institución</th>
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
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/recepcion-biblioteca/form-recepcion-biblioteca-action.php&accion=Eliminar&id=" + id;
    }

    const clickTabShowHidden = document.querySelector("#as-tab-header-click");
    clickTabShowHidden.addEventListener("click", () => {
        const contentTab = clickTabShowHidden.nextElementSibling;
        contentTab.classList.toggle("as-tab-content-form-show");
    });
</script>