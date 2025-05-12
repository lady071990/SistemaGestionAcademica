<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$consulta = '';
$listaUsuarios = Usuario::getListaEnObjetos('rol_id=2', '');

if (isset($_REQUEST['identificacion']) || isset($_REQUEST['nombres']) || isset($_REQUEST['apellidos'])) {
    $consulta .= !empty($_REQUEST['identificacion']) ? " AND identificacion LIKE '%{$_REQUEST['identificacion']}%'" : "";
    $consulta .= !empty($_REQUEST['nombres']) ? " AND nombres LIKE '%{$_REQUEST['nombres']}%'" : "";
    $consulta .= !empty($_REQUEST['apellidos']) ? " AND apellidos LIKE '%{$_REQUEST['apellidos']}%'" : "";
    $listaUsuarios = Usuario::getListaEnObjetos("rol_id=2" . $consulta, '');
}

foreach ($listaUsuarios as $item) {
    $lista .= "<tr>";
    $lista .= '<th scope="row">' . $count . '</th>';
    $lista .= "<td>{$item->getIdentificacion()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getNombres()}</td>";
    $lista .= "<td class='as-text-uppercase as-text-left'>{$item->getApellidos()}</td>";
    $lista .= "<td>{$item->getTelefono()}</td>";
    $lista .= "<td>{$item->getEmail()}</td>";
    $lista .= "<td>{$item->getDireccion()}</td>";
    
    $lista .= "<td>" . Generalidades::getEstadoUsuario($item->getEstado()) . "</td>";
    if ($editar != 4) {
        $lista .= "<td class='as-text-center'>";
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/docente/form-docente.php&accion=Modificar&id={$item->getId()}'>" . Generalidades::getTooltip(1, '') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, '') . "</span>";
        $lista .= $item->getEstado() == 1 ? "<a class='as-add' href='principal.php?CONTENIDO=layout/components/docente/form-asignacion-docente-create.php&accion=crear&id={$item->getId()}'>" . Generalidades::getTooltip(3, 'Asignación docente') . "</a>" : "";
        $lista .= "</td>";
    }
    $lista .= "</tr>";
    $count++;
}

?>
<div class="as-tab-content">
    <div class="as-tab-header" id="as-tab-header-click">
        <i class='fas fa-search'></i> Buscar docente
    </div>
    <div class="as-tab-content-form">
        <div class="as-form-content">
            <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/docente/lista-docente.php" autocomplete="off">
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
                        <button class="as-color-btn-green" type="submit">
                            Buscar
                        </button>
                        <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/docente/lista-docente.php">
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
        <h3 class="as-title-table">LISTADO DE DOCENTES</h3>
    </div>
    <?php
    if ($editar != 4) {
    ?>
        <div class="as-form-button-back">
            <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/docente/form-docente.php">Agregar docente</a>
        </div>
    <?php
    }
    ?>
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
                    <th scope="col">Estado</th>
                    <?php
                    if ($editar != 4) {
                    ?>
                        <th scope="col">Opciones</th>
                    <?php
                    }
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
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/docente/form-docente-action.php&accion=Eliminar&id=" + id;
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