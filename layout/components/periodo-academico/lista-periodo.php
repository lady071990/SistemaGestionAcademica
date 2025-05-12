<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$lista = '';
$count = 1;
$anioEscolar = AnioEscolar::getListaEnObjetos('estado=1', null)[0];
$periodoList = PeriodoAcademico::getListaEnObjetos(null, null);

foreach ($periodoList as $item) {
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td>" . $item->getNombre() . "</td>";
    $lista .= "<td>[" . Generalidades::convertDate($item->getInicioPeriodo(), false) . " - " . Generalidades::convertDate($item->getFinalizacionPeriodo(), false) . "]</td>";
    $lista .= "<td>" . $item->getAnioEscolar() . "</td>";
    $lista .= "<td class='as-text-center'>";
    $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/periodo-academico/form-periodo.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
    $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}

?>
<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">PERIODOS ACADEMICOS</h3>
        <h4 class="as-title-table">Año escolar activo esta comprendido entre el <?= Generalidades::convertDate($anioEscolar->getInicio(), false) ?> y <?= Generalidades::convertDate($anioEscolar->getFin(), false) ?></h4>
    </div>
    <div class="as-form-button-back">
        <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/periodo-academico/form-periodo.php">Agregar Periodo</a>
    </div>
    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Periodo académico</th>
                    <th scope="col">Año Escolar</th>
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
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/periodo-academico/form-periodo-action.php&accion=Eliminar&id=" + id;
    }
</script>