<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();

$lista = '';
$count = 1;
$selectMenuAsignatura = '';
$selectMenuGrado = '';
$bandera = false;
$consulta = '';

$arrayAsignatura = Asignatura::getListaEnObjetos(null, 'nombre_asignatura');
$arrayGrado = Grado::getListaEnObjetos(null, 'id');
$listaAsignacionesDocente = array();

if ($editar == 1 || $editar == 6) {
    $listaAsignacionesDocente = AsignacionDocente::getListaEnObjetos(null, 'gd.id, ad.id_usuario_docente, ad.id_grupo, ad.id_asignatura');
} else {
    $listaAsignacionesDocente = AsignacionDocente::getListaEnObjetos("u.identificacion = {$USUARIO->getIdentificacion()}", 'gd.id, ad.id_usuario_docente, ad.id_grupo, ad.id_asignatura');
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
        $consulta .=  $bandera ? " AND gr.nombre_grupo LIKE '%{$_REQUEST['nombre_grupo']}%'" : " gr.nombre_grupo LIKE '%{$_REQUEST['nombre_grupo']}%'";
        $bandera = true;
    }

    if (!empty($_REQUEST['id_asignatura'])) {
        $consulta .=  $bandera ? " AND ad.id_asignatura = {$_REQUEST['id_asignatura']}" : " ad.id_asignatura = {$_REQUEST['id_asignatura']}";
        $bandera = true;
    }

    if ($bandera) {
        $listaAsignacionesDocente = array();
        $listaAsignacionesDocente = AsignacionDocente::getListaEnObjetos("{$consulta}", 'gd.id, ad.id_usuario_docente, ad.id_grupo, ad.id_asignatura');
    }
}

foreach ($arrayAsignatura as $paramA) {
    $selectMenuAsignatura .= '<option value="' . $paramA->getId() . '">' . $paramA->getNombreAsignatura() . '</option>';
}
foreach ($arrayGrado as $paramG) {
    $selectMenuGrado .= '<option value="' . $paramG->getId() . '">' . $paramG->getNombreGrado() . '</option>';
}

foreach ($listaAsignacionesDocente as $item) {
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getIdentificacionDocente()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'><a href='principal.php?CONTENIDO=layout/components/docente/form-docente.php&accion=Modificar&id={$item->getIdUsuarioDocente()}'>{$item->getNombreDocente()}</a></td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreGrado()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreGrupo()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreAsignatura()}</td>";
    $link = $item->getLinkClaseVirtual();
        if (!empty($link) && $link != '#') {
            $lista .= "<td><a href='{$link}' target='_blank'>Ver enlace</a></td>";
        } else {
            $lista .= "<td>No asignado</td>";
        }
    $lista .= "<td>{$item->getIntensidadHoraria()}</td>";
    $lista .= "<td class='as-text-center'>";
    $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/docente/form-asignacion-docente-edit.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
    $lista .=  $editar == 1 || $editar == 6 ? "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>" : "";
    $lista .=  $editar == 1 || $editar == 6 ? "<a class='as-add' href='principal.php?CONTENIDO=layout/components/docente/form-asignacion-docente-create.php&accion=crear&id={$item->getIdUsuarioDocente()}'>" . Generalidades::getTooltip(3, 'Asignación docente') . "</a>" : "";
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}
?>
<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar asignación docente
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/docente/lista-asignacion-docente.php" autocomplete="off">
                <div class="as-form-margin">
                    <?php
                    if ($editar == 1 || $editar == 6) {
                    ?>
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
                    <?php
                    }
                    else {
                        echo '<input type="hidden" name="identificacion" value="'.$USUARIO->getIdentificacion().'">';
                    }
                    ?>
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
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="label" for="id_asignatura">Asignaturas</label>
                            <select class="as-form-select" name="id_asignatura" id="id_asignatura">
                                <option></option>
                                <?php
                                echo $selectMenuAsignatura;
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="buscar" value="buscar">
                    <div class="as-form-button">
                        <button class="as-color-btn-green" type="submit">
                            Buscar
                        </button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/docente/lista-asignacion-docente.php">
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
        <h3 class="as-title-table">LISTA DE ASIGNACIONES</h3>
    </div>
    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Identificación</th>
                    <th scope="col">Docente</th>
                    <th scope="col">Grado</th>
                    <th scope="col">Grupo</th>
                    <th scope="col">Asignatura</th>
                    <th scope="col">Clase virtual</th>
                    <th scope="col">Intensidad horaria</th>
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
        let respuesta = confirm("Esta seguro de eliminar este registro?");
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/docente/form-asignacion-docente-action.php&accion=Eliminar&id=" + id;
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