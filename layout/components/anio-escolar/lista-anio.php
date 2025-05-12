<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

try {
    $lista = '';
    $count = 1;
    $anioEscolarList = AnioEscolar::getListaEnObjetos(null, 'inicio DESC');
    
    if (empty($anioEscolarList)) {
        $lista = "<tr><td colspan='6'>No hay años escolares registrados</td></tr>";
    } else {
        foreach ($anioEscolarList as $item) {
            $institucion = $item->getNombreInstitucion();
            $nombreInstitucion = method_exists($institucion, 'getNombre') ? $institucion->getNombre() : 'Institución no disponible';
            
            $lista .= "<tr>";
            $lista .= '<th scope="row">' . $count . '</th>';
            $lista .= "<td>" . htmlspecialchars(Generalidades::convertDate($item->getInicio(), false)) . "</td>";
            $lista .= "<td>" . htmlspecialchars(Generalidades::convertDate($item->getFin(), false)) . "</td>";
            $lista .= "<td>" . htmlspecialchars($nombreInstitucion) . "</td>";
            $lista .= "<td>" . htmlspecialchars(Generalidades::getEstadoUsuario($item->getEstado())) . "</td>";
            $lista .= "<td class='as-text-center'>";
            $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/anio-escolar/form-anio.php&accion=Modificar&id=".htmlspecialchars($item->getId())."'>" . Generalidades::getTooltip(1, '') . "</a>";
            $lista .= "<span class='as-trash' onClick='eliminar(".htmlspecialchars($item->getId()).")'>" . Generalidades::getTooltip(2, '') . "</span>";
            $lista .= "</td>";
            $lista .= "</tr>";
            $count++;
        }
    }
} catch (Exception $e) {
    $lista = "<tr><td colspan='6'>Error al cargar los datos: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>

<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">AÑOS ESCOLARES</h3>
    </div>
    <div class="as-form-button-back">
        <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/anio-escolar/form-anio.php">Agregar año escolar</a>
    </div>
    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Inicia</th>
                    <th scope="col">Finaliza</th>
                    <th scope="col">Institución</th>
                    <th scope="col">Estado</th>
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
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/anio-escolar/form-anio-action.php&accion=Eliminar&id=" + id;
    }
</script>