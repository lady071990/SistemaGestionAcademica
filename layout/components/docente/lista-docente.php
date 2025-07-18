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
    $lista .= "<td>{$item->getTipoVinculacion()}</td>";
    $lista .= "<td>{$item->getExperienciaLaboral()}</td>";
    $lista .= "<td>" . ($item->getCertificacionPostgrado() ? 'Sí' : 'No') . "</td>";
    $lista .= "<td>" . ($item->getFechaCertificacion() ? $item->getFechaCertificacion() : '-') . "</td>";
    $perfil = $item->getPerfilProfesional();
    $resumen = (strlen($perfil) > 100) ? substr($perfil, 0, 100) . '...' : $perfil;
    $perfilEscapado = htmlspecialchars($perfil, ENT_QUOTES, 'UTF-8');

    $lista .= "<td>{$resumen}";
    if (strlen($perfil) > 100) {
        $lista .= " <button type='button' class='as-btn-small as-btn-ver-perfil' data-perfil='{$perfilEscapado}'>Ver</button>";
    }
    $lista .= "</td>";
    $lista .= "<td><a href='documentos/hojaVida/{$item->getHojaVida()}' target='_blank' title='Ver la hoja de vida'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
    $lista .= "<td><a href='documentos/soportes/{$item->getDocumentos()}' target='_blank' title='Ver documentos'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
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
                    <th scope="col">Tipo Vinculación</th>
                    <th scope="col">Experiencia Laboral</th>
                    <th scope="col">Certificación Postgrado</th>
                    <th scope="col">Fecha Certificación</th>
                    <th scope="col">Perfil Profesional</th>
                    <th scope="col">Hoja de Vida</th>
                    <th scope="col">Soportes</th>
                    <th scope="col">Estado</th>
                    <?php if ($editar != 4) { ?>
                        <th scope="col">Opciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php print_r($lista); ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para ver perfil profesional completo -->
<div id="modalPerfil" class="as-modal" style="display:none;">
    <div class="as-modal-content">
        <span class="as-modal-close" onclick="cerrarModalPerfil()">&times;</span>
        <h3>Perfil Profesional Completo</h3>
        <p id="modalPerfilContenido"></p>
    </div>
</div>

<style>
.as-modal {
    position: fixed;
    z-index: 999;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.6);
    display: flex; align-items: center; justify-content: center;
}
.as-modal-content {
    background-color: #fff;
    padding: 20px;
    width: 60%;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    position: relative;
}
.as-modal-close {
    position: absolute;
    top: 10px; right: 15px;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}
.as-btn-small.as-btn-ver-perfil {
    margin-left: 5px;
    background-color: #0056b3;
    color: white;
    border: none;
    padding: 3px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.as-btn-ver-perfil').forEach(btn => {
        btn.addEventListener('click', function () {
            const perfil = this.dataset.perfil;
            document.getElementById('modalPerfilContenido').textContent = perfil;
            document.getElementById('modalPerfil').style.display = 'flex';
        });
    });
});

function cerrarModalPerfil() {
    document.getElementById('modalPerfil').style.display = 'none';
}
</script>


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