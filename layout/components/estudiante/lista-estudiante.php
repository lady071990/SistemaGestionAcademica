<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$consulta = '';
$listaUsuarios = Usuario::getListaEnObjetos('rol_id=4', '');

if (isset($_REQUEST['identificacion']) || isset($_REQUEST['nombres']) || isset($_REQUEST['apellidos'])) {
    $consulta .= !empty($_REQUEST['identificacion']) ? " AND identificacion LIKE '%{$_REQUEST['identificacion']}%'" : "";
    $consulta .= !empty($_REQUEST['nombres']) ? " AND nombres LIKE '%{$_REQUEST['nombres']}%'" : "";
    $consulta .= !empty($_REQUEST['apellidos']) ? " AND apellidos LIKE '%{$_REQUEST['apellidos']}%'" : "";
    $listaUsuarios = Usuario::getListaEnObjetos("rol_id=4" . $consulta, '');
}

foreach ($listaUsuarios as $item) {
    $foto = !empty($item->getFoto()) ? "documentos/fotos/{$item->getFoto()}" : "documentos/fotos/foto1.jpeg";
    if (!file_exists($foto)) {
        $foto = "documentos/fotos/foto1.jpeg";
    }

    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td>{$item->getIdentificacion()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombres()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getApellidos()}</td>";
    $lista .= "<td>{$item->getTelefono()}</td>";
    $lista .= "<td>{$item->getEmail()}</td>";
    $lista .= "<td>{$item->getDireccion()}</td>";
    $lista .= "<td>{$item->getInstitucionEducativaNombre()}</td>";
    $lista .= "<td><a href='documentos/hojaVida/{$item->getHojaVida()}' target='_blank' title='Ver la hoja de vida'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
    $lista .= "<td><a href='documentos/soportes/{$item->getDocumentos()}' target='_blank' title='Ver documentos'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
    $lista .= "<td><img class='fotos' src='{$foto}' width='50' height='70' onerror=\"this.src='documentos/fotos/foto1.jpeg'\" /></td>";
    $lista .= "<td>" . Generalidades::getEstadoUsuario($item->getEstado()) . "</td>";
    if ($editar != 2) {
        $lista .= "<td class='as-text-center'>";
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/estudiante/form-estudiante.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
        $lista .= $item->getEstado() == 1 ? "<a class='as-add' href='principal.php?CONTENIDO=layout/components/estudiante/form-estudiante-grupo-create.php&accion=crear&id={$item->getId()}'>" . Generalidades::getTooltip(3, 'Agregar a grupo') . "</a>" : "";
        $lista .= "</td>";
    }
    $lista .= "</tr>";
    $count++;
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar estudiante
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php" autocomplete="off">
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
                            <label class="hide-label" for="apellidos">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos">
                        </div>
                    </div>
                    <div class="as-form-button">
                        <button class="as-color-btn-green" type="submit">Buscar</button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">LISTADO DE ESTUDIANTES</h3>
    </div>
    <?php if ($editar != 2) { ?>
        <div class="as-form-button-back">
            <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/estudiante/form-estudiante.php">Agregar estudiante</a>
        </div>
    <?php } ?>
    <div class="as-table-responsive">
        <table id="tablaEstudiantes" class="as-table display">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Identificación</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Universidad</th>
                    <th>Hoja de vida</th>
                    <th>Soportes</th>
                    <th>Foto</th>
                    <th>Estado</th>
                    <?php if ($editar != 2) { ?>
                        <th>Opciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php echo $lista; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Agrega jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#tablaEstudiantes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    });

    const eliminar = (id) => {
        let respuesta = confirm("¿Está seguro de eliminar este registro?");
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/estudiante/form-estudiante-action.php&accion=Eliminar&id=" + id;
    }

    const clickTabShowHidden = document.querySelector("#as-tab-header-click");
    clickTabShowHidden.addEventListener("click", () => {
        const contentTab = clickTabShowHidden.nextElementSibling;
        contentTab.classList.toggle("as-tab-content-form-show");
    });
</script>