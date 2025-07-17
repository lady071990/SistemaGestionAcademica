<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$selectMenuGrado = '';
$bandera = false;
$consulta = '';

// Configuración de paginación
$registrosPorPagina = 10; // Número de registros por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Obtener parámetros de búsqueda para mantenerlos en la paginación
$parametrosBusqueda = '';
if (isset($_REQUEST['buscar'])) {
    foreach ($_REQUEST as $key => $value) {
        if ($key != 'pagina' && $key != 'CONTENIDO' && !empty($value)) {
            $parametrosBusqueda .= '&'.$key.'='.urlencode($value);
        }
    }
}

// Obtener año escolar activo
$arrayAnioEscolar = AnioEscolar::getListaEnObjetos('estado=1', null)[0];
$idAnioEscolar = $arrayAnioEscolar->getId();

$arrayGrado = Grado::getListaEnObjetos(null, 'id');

// Obtener todos los registros (sin paginación) para calcular el total
if ($editar == 2) {
    $listaGruposPorEstudianteTotal = GrupoEstudiante::getListaEnObjetos("us.identificacion = {$USUARIO->getIdentificacion()} AND ge.id_anio_escolar = $idAnioEscolar", "us.nombres, u.identificacion, gd.nombre_grado, g.nombre_grupo");
} else {
    $listaGruposPorEstudianteTotal = GrupoEstudiante::getListaEnObjetos("ge.id_anio_escolar = $idAnioEscolar", "us.nombres, u.identificacion, gd.nombre_grado, g.nombre_grupo");
}

// Filtros de búsqueda
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

    // Asegurar filtro por año escolar
    $consulta .= $bandera ? " AND ge.id_anio_escolar = $idAnioEscolar" : "ge.id_anio_escolar = $idAnioEscolar";

    $listaGruposPorEstudianteTotal = GrupoEstudiante::getListaEnObjetos($consulta, "us.nombres, u.identificacion, gd.nombre_grado, g.nombre_grupo");
}

// Aplicar paginación
$totalRegistros = count($listaGruposPorEstudianteTotal);
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$listaGruposPorEstudiante = array_slice($listaGruposPorEstudianteTotal, $offset, $registrosPorPagina);

// Armar select de grados con valores seleccionados
foreach ($arrayGrado as $param_grado) {
    $selected = (isset($_REQUEST['id_grado']) && $_REQUEST['id_grado'] == $param_grado->getId()) ? 'selected' : '';
    $selectMenuGrado .= '<option value="' . $param_grado->getId() . '" '.$selected.'>' . $param_grado->getNombreGrado() . '</option>';
}

// Armar la tabla
foreach ($listaGruposPorEstudiante as $item) {
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . (($paginaActual - 1) * $registrosPorPagina + $count) . '</th>';
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
    $lista .= "<a class='as-add' href='principal.php?CONTENIDO=layout/components/notas/form-observacion-boletin.php&accion=crear&id={$item->getIdUsuarioEstudiante()}'>" . Generalidades::getTooltip(5, 'Agregar Observación') . "</a>";
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes por Grupo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .as-nav-header {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0.5rem 0 0;
            background-color: #39ab6c;
        }

        .as-nav-header .as-first-div {
            z-index: 9;
            height: 5.2rem;
        }

        .as-nav-header .as-logo {
            max-width: 6rem;
            transform: scale(1);
            transition: all .4s ease-out;
        }

        .as-nav-header .as-logo:hover {
            transform: scale(1.1);
            transition: all .4s ease-out;
        }

        .as-tab-content {
            margin-bottom: 20px;
        }
        .as-tab-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            cursor: pointer;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .as-tab-content-form {
            display: none;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .as-tab-content-form-show {
            display: block;
        }
        .as-form-margin {
            margin-bottom: 15px;
        }
        .as-form-fields {
            margin-bottom: 10px;
        }
        .as-form-input input, .as-form-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .as-form-button {
            margin-top: 15px;
        }
        .as-color-btn-green {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .as-color-btn-red {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        .as-layout-table {
            margin-top: 20px;
        }
        .as-title-table {
            text-align: center;
            margin-bottom: 15px;
        }
        .as-table-responsive {
            overflow-x: auto;
        }
        .as-table {
            width: 100%;
            border-collapse: collapse;
        }
        .as-table th, .as-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .as-table th {
            background-color: #f8f9fa;
        }
        .as-text-uppercase {
            text-transform: uppercase;
        }
        .as-text-left {
            text-align: left;
        }
        .as-text-center {
            text-align: center;
        }
        .as-edit, .as-add, .as-trash {
            margin: 0 5px;
            cursor: pointer;
        }
        .as-edit {
            color: #007bff;
        }
        .as-add {
            color: #28a745;
        }
        .as-trash {
            color: #dc3545;
        }
        .pagination {
            margin-top: 20px;
        }
        .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .page-link {
            color: #007bff;
        }
        .hide-label {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Filtros de búsqueda -->
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
                                    <input type="number" name="identificacion" id="identificacion" placeholder="Identificación" value="<?= isset($_REQUEST['identificacion']) ? htmlspecialchars($_REQUEST['identificacion']) : '' ?>">
                                </div>
                            </div>
                            <div class="as-form-fields">
                                <div class="as-form-input">
                                    <label class="hide-label" for="nombres">Nombres</label>
                                    <input type="text" name="nombres" id="nombres" placeholder="Nombres" value="<?= isset($_REQUEST['nombres']) ? htmlspecialchars($_REQUEST['nombres']) : '' ?>">
                                </div>
                            </div>
                            <div class="as-form-fields">
                                <div class="as-form-input">
                                    <label class="label" for="id_grado">Grados</label>
                                    <select class="as-form-select" name="id_grado" id="id_grado">
                                        <option value=""></option>
                                        <?= $selectMenuGrado ?>
                                    </select>
                                </div>
                            </div>
                            <div class="as-form-fields">
                                <div class="as-form-input">
                                    <label class="label" for="nombre_grupo">Grupo</label>
                                    <select class="as-form-select" name="nombre_grupo" id="nombre_grupo">
                                        <option value=""></option>
                                        <?php 
                                        $selectedGrupo = isset($_REQUEST['nombre_grupo']) ? $_REQUEST['nombre_grupo'] : '';
                                        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'] as $letra) {
                                            $selected = ($selectedGrupo == $letra) ? 'selected' : '';
                                            echo "<option value='$letra' $selected>$letra</option>";
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="buscar" value="buscar">
                            <div class="as-form-button">
                                <button class="as-color-btn-green" type="submit">Buscar</button>
                                <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de resultados -->
        <div class="as-layout-table">
            <div>
                <h3 class="as-title-table">LISTADO DE ESTUDIANTES ASIGNADOS A GRUPOS</h3>
            </div>

            <div class="as-table-responsive">
                <table class="as-table table-bordered">
                    <thead class="table-light">
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
                        <?= $lista ?>
                    </tbody>
                </table>
            </div>
            <!-- Paginación Bootstrap -->
            <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php
                    // Botón Anterior
                    if ($paginaActual > 1) {
                        echo '<li class="page-item">';
                        echo '<a class="page-link" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php&pagina='.($paginaActual - 1).$parametrosBusqueda.'" aria-label="Previous">';
                        echo '<span aria-hidden="true">&laquo;</span>';
                        echo '</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="page-item disabled">';
                        echo '<a class="page-link" href="#" aria-label="Previous">';
                        echo '<span aria-hidden="true">&laquo;</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                    
                    // Mostrar páginas cercanas a la actual
                    $inicio = max(1, $paginaActual - 2);
                    $fin = min($totalPaginas, $paginaActual + 2);
                    
                    // Mostrar primera página si no está en el rango
                    if ($inicio > 1) {
                        echo '<li class="page-item"><a class="page-link" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php&pagina=1'.$parametrosBusqueda.'">1</a></li>';
                        if ($inicio > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }
                    
                    // Números de página
                    for ($i = $inicio; $i <= $fin; $i++) {
                        if ($i == $paginaActual) {
                            echo '<li class="page-item active"><a class="page-link" href="#">'.$i.'</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php&pagina='.$i.$parametrosBusqueda.'">'.$i.'</a></li>';
                        }
                    }
                    
                    // Mostrar última página si no está en el rango
                    if ($fin < $totalPaginas) {
                        if ($fin < $totalPaginas - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php&pagina='.$totalPaginas.$parametrosBusqueda.'">'.$totalPaginas.'</a></li>';
                    }
                    
                    // Botón Siguiente
                    if ($paginaActual < $totalPaginas) {
                        echo '<li class="page-item">';
                        echo '<a class="page-link" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php&pagina='.($paginaActual + 1).$parametrosBusqueda.'" aria-label="Next">';
                        echo '<span aria-hidden="true">&raquo;</span>';
                        echo '</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="page-item disabled">';
                        echo '<a class="page-link" href="#" aria-label="Next">';
                        echo '<span aria-hidden="true">&raquo;</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </nav>
            <?php endif; ?>
            
            <!-- Información de paginación -->
            <div class="text-center text-muted mb-3">
                Mostrando registros del <?= $offset + 1 ?> al <?= min($offset + $registrosPorPagina, $totalRegistros) ?> de <?= $totalRegistros ?> registros encontrados
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script type="text/javascript">
        const eliminar = (id) => {
            if (confirm("¿Está seguro de eliminar este registro?")) {
                location = "principal.php?CONTENIDO=layout/components/estudiante/form-estudiante-grupo-action.php&accion=Eliminar&id=" + id;
            }
        };

        document.querySelector("#as-tab-header-click").addEventListener("click", () => {
            document.querySelector(".as-tab-content-form").classList.toggle("as-tab-content-form-show");
        });
    </script>
</body>
</html>