<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$consulta = '';
$bandera = false;
$selectMenuPeriodoAcademico = '';
$selectMenuGrado = '';
$arrayPeriodoAcademico = PeriodoAcademico::getListaEnObjetos(null, 'id');
$arrayGrado = Grado::getListaEnObjetos(null, 'id');
$listaNotas = Notas::getListaEnObjetos('', 'n.id_periodo_academico, n.id_usuario_estudiante, n.id_asignatura, n.id_tipo_actividad');

if (isset($_REQUEST['buscar'])) {
    if (!empty($_REQUEST['identificacion'])) {
        $consulta .= " u.identificacion LIKE '%{$_REQUEST['identificacion']}%'";
        $bandera = true;
    }

    if (!empty($_REQUEST['nombres'])) {
        $consulta .=  $bandera ? " AND u.nombres LIKE '%{$_REQUEST['nombres']}%'" : " u.nombres LIKE '%{$_REQUEST['nombres']}%'";
        $bandera = true;
    }

    if (!empty($_REQUEST['id_periodo_academico'])) {
        $consulta .=  $bandera ? " AND n.id_periodo_academico = {$_REQUEST['id_periodo_academico']}" : " n.id_periodo_academico = {$_REQUEST['id_periodo_academico']}";
        $bandera = true;
    }

    if (!empty($_REQUEST['id_grado'])) {
        $consulta .=  $bandera ? " AND g.id_grado = {$_REQUEST['id_grado']}" : " g.id_grado = {$_REQUEST['id_grado']}";
        $bandera = true;
    }

    if (!empty($_REQUEST['nombre_grupo'])) {
        $consulta .=  $bandera ? " AND g.nombre_grupo LIKE '%{$_REQUEST['nombre_grupo']}%'" : " g.nombre_grupo LIKE '%{$_REQUEST['nombre_grupo']}%'";
        $bandera = true;
    }

    if ($bandera) {
        $listaNotas = array();
        $listaNotas = Notas::getListaEnObjetos("{$consulta}", 'n.id_periodo_academico, n.id_usuario_estudiante, n.id_asignatura, n.id_tipo_actividad');
    }
}

foreach ($arrayPeriodoAcademico as $param_pa) {
    $selectMenuPeriodoAcademico .= '<option value="' . $param_pa->getId() . '">' . $param_pa->getNombre() . '</option>';
}

foreach ($arrayGrado as $param_grado) {
    $selectMenuGrado .= '<option value="' . $param_grado->getId() . '">' . $param_grado->getNombreGrado() . '</option>';
}

foreach ($listaNotas as $item) {
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td>{$item->getPeriodoAcademico()}</td>";
    $lista .= "<td>{$item->getNombreGrado()}</td>";
    $lista .= "<td>{$item->getNombreGrupo()}</td>";
    $lista .= $editar == 1 || $editar == 6 ? "<td class='as-text-uppercase as-text-left'><a href='principal.php?CONTENIDO=layout/components/estudiante/form-estudiante.php&accion=Modificar&id={$item->getIdUsuarioEstudiante()}'>{$item->getNombreEstudiante()}</a></td>" : "<td class='as-text-uppercase as-text-left'>{$item->getNombreEstudiante()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreAsignatura()}</td>";
    $lista .= "<td>{$item->getNombreTipoActividad()}</td>";
    $lista .= "<td>{$item->getNota()}</td>";
    if ($editar != 4) {
        $lista .= "<td class='as-text-center'>";
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/notas/form-notas-edit.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
        $lista .= "<a class='as-add' href='principal.php?CONTENIDO=layout/components/notas/form-notas-create-array.php&accion=Crear&id={$item->getIdUsuarioEstudiante()}'>" . Generalidades::getTooltip(3, 'Agregar notas') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
        $lista .= "</td>";
    }
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
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/notas/lista-notas.php" autocomplete="off">
                <div class="as-form-margin">
                    <h2>Buscar notas de un estudiante</h2>
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
                            <label class="label" for="id_periodo_academico">Periodo académico</label>
                            <select class="as-form-select" name="id_periodo_academico" id="id_periodo_academico">
                                <option value=""></option>
                                <?php
                                echo $selectMenuPeriodoAcademico;
                                ?>
                            </select>
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
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/notas/lista-notas.php">
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
        <h3 class="as-title-table">LISTA DE CALIFICACIONES</h3>
    </div>
    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Periodo académico</th>
                    <th scope="col">Grado</th>
                    <th scope="col">Grupo</th>
                    <th scope="col">Estudiante</th>
                    <th scope="col">Asignatura</th>
                    <th scope="col">Tipo actividad</th>
                    <th scope="col">Nota</th>
                    <?php
                    if ($editar != 4) {
                    ?>
                        <th scope="col">Opciones</th>
                    <?php }
                    ?>
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
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/notas/form-notas-action.php&accion=Eliminar&id=" + id;
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