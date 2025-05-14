<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$consulta = '';
$listaUsuarios = array();

if (isset($_REQUEST['buscar'])) {
    $consulta .= !empty($_REQUEST['identificacion']) ? " AND identificacion LIKE '%{$_REQUEST['identificacion']}%'" : "";
    $consulta .= !empty($_REQUEST['nombres']) ? " AND nombres LIKE '%{$_REQUEST['nombres']}%'" : "";
    $listaUsuarios = Usuario::getListaEnObjetos("rol_id=4" . $consulta, '');

    foreach ($listaUsuarios as $item) {
    $foto = $item->getFoto(); // Asegúrate de que este método exista
    $rutaFoto = !empty($foto) ? "documentos/fotos/{$foto}" : "documentos/fotos/default.png";

    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td>{$item->getIdentificacion()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombres()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getApellidos()}</td>";
    $lista .= "<td>{$item->getTelefono()}</td>";
    $lista .= "<td>{$item->getEmail()}</td>";
    $lista .= "<td>{$item->getDireccion()}</td>";
    $lista .= "<td><img src='{$rutaFoto}' alt='Foto' width='50' height='50' style='border-radius:50%; object-fit:cover;'></td>"; // FOTO
    $lista .= "<td>" . Generalidades::getEstadoUsuario($item->getEstado()) . "</td>";
    $lista .= "<td class='as-text-center'>";
    $lista .= "<a class='as-edit' href='layout/boletin.php?identificacion={$item->getIdentificacion()}' target='blank'>" . Generalidades::getTooltip(5, 'Imprimir Boletín') . "</a>";
    $lista .= "</td>";
    $lista .= "</tr>";
    $count++;
    }

}


?>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/notas/lista-notas-imprimir.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Buscar estudiante para imprimir su boletin</h2>
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
            <input type="hidden" name="buscar" value="buscar">
            <div class="as-form-button">
                <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/notas/lista-notas-imprimir.php">
                    Limpiar
                </a>
                <button class="as-color-btn-green" type="submit">
                    Buscar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">IMPRIMIR BOLETIN</h3>
    </div>
    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Identificación</th>
                    <th scope="col">Nombres</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Email</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php print_r($lista); ?>
            </tbody>
        </table>
    </div>
</div>