<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$lista = '';
$count = 1;

$institution = InstitucionEducativa::getListaEnObjetos('id=1', null)[0];
$anioEscolar = AnioEscolar::getListaEnObjetos('estado=1', null)[0];
$USUARIO = unserialize($_SESSION['usuario']);
$institucionesList = InstitucionEducativa::getListaEnObjetos(null, 'nombre');


foreach ($institucionesList as $item) {
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombre()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getTipo()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getDireccion()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getEmail()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getTelefono()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombreDirectora()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getPaginaWeb()}</td>";
    
    // Mostrar programas correctamente
    $programas = $item->getProgramas();
    $textoProgramas = is_array($programas) ? implode(', ', array_column($programas, 'tipo')) : $programas;
    $lista .= "<td class='as-text-uppercase as-text-left'>" . htmlspecialchars($textoProgramas) . "</td>";
    
    // Mostrar especialidades médicas
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getEspecialidadesMedicas()}</td>";
    
    $lista .= "<td class='as-text-center'>";
    $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/institucion/form-institution.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
    $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
    $lista .= "<a class='as-checklist' href='principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php&id_universidad={$item->getId()}'>Lista Chequeo</a>";
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
}
?>
<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">GESTIÓN DE INSTITUCIONES EDUCATIVAS</h3>
    </div>
    <div class="as-form-button-back">
        <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/institucion/form-institution.php">Agregar Institución</a>
    </div>
    <div class="as-table-responsive">
        <table id="tablaEstudiantes" class="as-table display">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Email</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Directora</th>
                    <th scope="col">Página Web</th>
                    <th scope="col">Programas</th>
                    <th scope="col">Especialidades Médicas</th>
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
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/institucion/form-institution-action.php&accion=Eliminar&id=" + id;
    }
</script>