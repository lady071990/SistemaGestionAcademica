<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso no autorizado');
    exit;
}

include 'logica/clasesGenericas/Librerias.php';

$rolUsuario = $USUARIO->getRolId();
$institucionId = $USUARIO->getInstitucion_educativa_id();

if ($rolUsuario != 7) {
    echo "<p class='as-alert'>Acceso denegado. Solo disponible para universidades.</p>";
    exit;
}

$lista = '';
$count = 1;
$consulta = '';
$bandera = false;

$identificacion = $_REQUEST['identificacion'] ?? '';
$nombres = $_REQUEST['nombres'] ?? '';

if (isset($_REQUEST['buscar']) && (!empty($identificacion) || !empty($nombres))) {
    $bandera = true;
    $condiciones = [];

    if (!empty($identificacion)) {
        $condiciones[] = "u.identificacion LIKE '%" . addslashes($identificacion) . "%'";
    }

    if (!empty($nombres)) {
        $condiciones[] = "u.nombres LIKE '%" . addslashes($nombres) . "%'";
    }

    // Filtro obligatorio por institución educativa
    $condiciones[] = "u.institucion_educativa_id = $institucionId";

    $consulta = implode(" AND ", $condiciones);
}

// Consultar solo si hay filtros válidos
$listaNotas = $bandera ? NotasConsulta::getListaEnObjetos($consulta, 'u.nombres') : [];

foreach ($listaNotas as $item) {
    $lista .= "<tr>";
    $lista .= "<th scope='row'>{$count}</th>";
    $lista .= "<td>{$item->getPeriodoAcademico()}</td>";
    $lista .= "<td>{$item->getNombreGrado()}</td>";
    $lista .= "<td>{$item->getNombreGrupo()}</td>";
    $lista .= "<td class='as-text-uppercase'>{$item->getNombreEstudiante()}</td>";
    $lista .= "<td>{$item->getNombreAsignatura()}</td>";
    $lista .= "<td>{$item->getNombreTipoActividad()}</td>";
    $lista .= "<td>{$item->getNota()}</td>";
    $lista .= "</tr>";
    $count++;
}
?>

<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar notas por estudiante
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form method="post" action="principal.php?CONTENIDO=layout/components/notas/lista-notas-consulta.php" autocomplete="off">
                <div class="as-form-margin">
                    <h2>Consulta de notas por Universidad</h2>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="hide-label" for="identificacion">Identificación</label>
                            <input type="number" name="identificacion" id="identificacion" placeholder="Identificación" value="<?= htmlspecialchars($identificacion) ?>">
                        </div>
                        <div class="as-form-input">
                            <label class="hide-label" for="nombres">Nombres</label>
                            <input type="text" name="nombres" id="nombres" placeholder="Nombres" value="<?= htmlspecialchars($nombres) ?>">
                        </div>
                    </div>
                    <input type="hidden" name="buscar" value="buscar">
                    <div class="as-form-button">
                        <button class="as-color-btn-green" type="submit">Buscar</button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/notas/lista-notas-consulta.php">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($bandera && empty($listaNotas)): ?>
    <p class="as-alert">No se encontraron resultados para su búsqueda.</p>
<?php endif; ?>

<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">LISTA DE CALIFICACIONES</h3>
    </div>
    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Periodo académico</th>
                    <th>Grado</th>
                    <th>Grupo</th>
                    <th>Estudiante</th>
                    <th>Asignatura</th>
                    <th>Tipo actividad</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                <?= $lista ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelector("#as-tab-header-click").addEventListener("click", () => {
        document.querySelector(".as-tab-content-form").classList.toggle("as-tab-content-form-show");
    });
</script>