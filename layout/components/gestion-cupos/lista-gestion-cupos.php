<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$bandera = false;
$consulta = '';

require_once 'logica/clases/GestionCupos.php';

$listaGestionCupos = GestionCupos::getListaEnObjetos(null, 'fecha_registro DESC');

if (isset($_REQUEST['buscar'])) {
    if (!empty($_REQUEST['especialidad'])) {
        $consulta .= " especialidad LIKE '%{$_REQUEST['especialidad']}%'";
        $bandera = true;
    }

    if (!empty($_REQUEST['nombre_estudiante'])) {
        $consulta .= $bandera ? " AND nombre_estudiante LIKE '%{$_REQUEST['nombre_estudiante']}%'" : " nombre_estudiante LIKE '%{$_REQUEST['nombre_estudiante']}%'";
        $bandera = true;
    }

    if (!empty($_REQUEST['turno'])) {
        $consulta .= $bandera ? " AND turno = '{$_REQUEST['turno']}'" : " turno = '{$_REQUEST['turno']}'";
        $bandera = true;
    }

    if ($bandera) {
        $listaGestionCupos = GestionCupos::getListaEnObjetos($consulta, 'fecha_registro DESC');
    }
}

foreach ($listaGestionCupos as $item) {
    foreach ($listaGestionCupos as $item) {
    // Obtener nombre de la universidad
    $nombreUniversidad = 'No asignada';
    if ($item->getInstitucionEducativaId()) {
        $universidad = new InstitucionEducativa('id', $item->getInstitucionEducativaId());
        $nombreUniversidad = $universidad->getNombre();
    }
    
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getEspecialidad()}</td>";
    $lista .= "<td class='as-text-center'>{$item->getNumeroEstudiantes()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>$nombreUniversidad</td>"; // Nueva columna
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreEstudiante()}</td>";
    $lista .= "<td class='as-text-center'>" . ucfirst($item->getTurno()) . "</td>";
    $lista .= "<td class='as-text-center'>" . date('d/m/Y', strtotime($item->getFechaRegistro())) . "</td>";
    $lista .= "<td class='as-text-center'>";
    if ($editar != 2) {
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/gestion-cupos/form-gestion-cupos-edit.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
    }
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}
}
?>

<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar gestión de cupos por filtros
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/gestion-cupos/lista-gestion-cupos.php" autocomplete="off">
                <div class="as-form-margin">
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="especialidad">Especialidad</label>
                            <input type="text" name="especialidad" id="especialidad" placeholder="Especialidad">
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="nombre_estudiante">Nombre Estudiante</label>
                            <input type="text" name="nombre_estudiante" id="nombre_estudiante" placeholder="Nombre Estudiante">
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="label" for="turno">Turno</label>
                            <select class="as-form-select" name="turno" id="turno">
                                <option value=""></option>
                                <option value="mañana">Mañana</option>
                                <option value="tarde">Tarde</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="buscar" value="buscar">
                    <div class="as-form-button">
                        <button class="as-color-btn-green" type="submit">
                            Buscar
                        </button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/gestion-cupos/lista-gestion-cupos.php">
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
        <h3 class="as-title-table">LISTADO DE GESTIÓN DE CUPOS</h3>
        <div class="as-form-button-back">
            <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/gestion-cupos/form-gestion-cupos-create.php">Agregar</a>
        </div>
    </div>

    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">N° Estudiantes</th>
                    <th scope="col">Universidad</th>
                    <th scope="col">Nombre Estudiante</th>
                    <th scope="col">Turno</th>
                    <th scope="col">Fecha Registro</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php print_r($lista); ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    const eliminar = (id) => {
        let respuesta = confirm("¿Está seguro de eliminar este registro?");
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/gestion-cupos/form-gestion-cupos-action.php&accion=Eliminar&id=" + id;
    }

    const clickTabShowHidden = document.querySelector("#as-tab-header-click");
    clickTabShowHidden.addEventListener("click", () => {
        const contentTab = clickTabShowHidden.nextElementSibling;
        contentTab.classList.toggle("as-tab-content-form-show");
    });
</script>