<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$selectMenuGrado = '';
$bandera = false;
$banderaAsignacion = false;
$consulta = '';

$arrayGrado = Grado::getListaEnObjetos(null, 'id');
if ($editar == 2) {
    $listaGruposPorEstudiante = GrupoEstudiante::getListaEnObjetos("us.identificacion = {$USUARIO->getIdentificacion()}", "us.nombres, u.identificacion, gd.nombre_grado, g.nombre_grupo");
}
else {
    $listaGruposPorEstudiante = GrupoEstudiante::getListaEnObjetos(null, "us.nombres, u.identificacion, gd.nombre_grado, g.nombre_grupo");
}

if (isset($_REQUEST['buscar'])) {
    if (!empty($_REQUEST['identificacion'])) {
        $consulta .= " u.identificacion LIKE '%{$_REQUEST['identificacion']}%'";
        $bandera = true;
    }

    if (!empty($_REQUEST['nombres'])) {
        $consulta .=  $bandera ? " AND u.nombres LIKE '%{$_REQUEST['nombres']}%'" : " u.nombres LIKE '%{$_REQUEST['nombres']}%'";
        $bandera = true;
    }

    if (!empty($_REQUEST['id_grado'])) {
        $consulta .=  $bandera ? " AND gd.id = {$_REQUEST['id_grado']}" : " gd.id = {$_REQUEST['id_grado']}";
        $bandera = true;
    }

    if (!empty($_REQUEST['nombre_grupo'])) {
        $consulta .=  $bandera ? " AND g.nombre_grupo LIKE '%{$_REQUEST['nombre_grupo']}%'" : " g.nombre_grupo LIKE '%{$_REQUEST['nombre_grupo']}%'";
        $bandera = true;
    }

    if ($bandera) {
        $listaGruposPorEstudiante = array();
        $listaGruposPorEstudiante = GrupoEstudiante::getListaEnObjetos("{$consulta}", "us.nombres, u.identificacion, gd.nombre_grado, g.nombre_grupo");
    }
}

foreach ($arrayGrado as $param_grado) {
    $selectMenuGrado .= '<option value="' . $param_grado->getId() . '">' . $param_grado->getNombreGrado() . '</option>';
}

foreach ($listaGruposPorEstudiante as $item) {
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getIdentificacionEstudiante()}</td>";
    $lista .= $editar == 1 || $editar == 6 ? "<td class='as-text-uppercase as-text-left'><a href='principal.php?CONTENIDO=layout/components/estudiante/form-estudiante.php&accion=Modificar&id={$item->getIdUsuarioEstudiante()}'>{$item->getNombreUsuarioEstudiante()}</a></td>" : "<td class='as-text-uppercase as-text-left'>{$item->getNombreUsuarioEstudiante()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreGrado()}</td>";
    $lista .= "<td>{$item->getNombreGrupo()}</td>";
    $lista .= "<td class='as-text-center'>";
    if ($editar != 2) {
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/estudiante/form-estudiante-grupo-edit.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
    }
    $lista .= "<a class='as-add' href='principal.php?CONTENIDO=layout/components/inasistencias/form-inasistencias-create.php&accion=crear&id={$item->getIdUsuarioEstudiante()}'>" . Generalidades::getTooltip(3, 'Registrar inasistencia') . "</a>";
    $lista .= "<a class='as-add' href='principal.php?CONTENIDO=layout/components/notas/form-notas-create-array.php&accion=crear&id={$item->getIdUsuarioEstudiante()}'>" . Generalidades::getTooltip(4, 'Agregar Calificación') . "</a>";
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}
?>

<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar estudiantes por filtros
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php" autocomplete="off">
                <div class="as-form-margin">
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="identificacion">Identificación</label>
                            <input type="number" name="identificacion" id="identificacion" placeholder="Identificación">
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="nombres">Nombres</label>
                            <input type="text" name="nombres" id="nombres" placeholder="Nombres">
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="label" for="id_grado">Grados</label>
                            <select class="as-form-select" name="id_grado" id="id_grado">
                                <option value=""></option>
                                <?php
                                echo $selectMenuGrado;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="label" for="nombre_grupo">Grupo</label>
                            <select class="as-form-select" name="nombre_grupo" id="nombre_grupo">
                                <option value=""></option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="G">G</option>
                                <option value="H">H</option>
                                <option value="I">I</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="buscar" value="buscar">
                    <div class="as-form-button">
                        <button class="as-color-btn-green" type="submit">
                            Buscar
                        </button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php">
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
        <h3 class="as-title-table">LISTADO DE ESTUDIANTES ASIGNADOS A GRUPOS</h3>
    </div>

    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Identificación</th>
                    <th scope="col">Estudiante</th>
                    <th scope="col">Grado</th>
                    <th scope="col">Grupo</th>
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
        console.log(id);
        let respuesta = confirm("Esta seguro de eliminar este registro?");
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/estudiante/form-estudiante-grupo-action.php&accion=Eliminar&id=" + id;
    }

    const clickTabShowHidden = document.querySelector("#as-tab-header-click");
    clickTabShowHidden.addEventListener("click", () => {
        const contentTab = clickTabShowHidden.nextElementSibling;

        if (contentTab.classList.contains("as-tab-content-form-show")) {
            contentTab.classList.remove("as-tab-content-form-show");
        } else {
            contentTab.classList.add("as-tab-content-form-show");
        }
    });
</script>