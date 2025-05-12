<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso_no_autorizado');
    exit();
}

// Inicializar $lista con valor por defecto
$lista = '<tr><td colspan="4">No se encontraron grupos</td></tr>';
$count = 1;

try {
    // Búsqueda segura
    $filtro = '';
    $parametros = [];
    
    if (!empty($_REQUEST['nombre'])) {
        $nombre = htmlspecialchars($_REQUEST['nombre']);
        $filtro = "WHERE gr.nombre_grado LIKE ?";
        $parametros = ["%$nombre%"];
    }

    $sql = "SELECT g.id, g.nombre_grupo, g.id_grado, gr.nombre_grado 
            FROM grupo g 
            JOIN grado gr ON g.id_grado = gr.id 
            $filtro 
            ORDER BY gr.nombre_grado, g.nombre_grupo";
    
    $grupos = ConectorBD::ejecutarQuery($sql, $parametros);
    
    if (!empty($grupos)) {
        $lista = '';
        foreach ($grupos as $grupo) {
            $lista .= "<tr>
                <th scope='row'>".$count."</th>
                <td class='as-text-uppercase as-text-left'>".htmlspecialchars($grupo['nombre_grado'])."</td>
                <td>".htmlspecialchars($grupo['nombre_grupo'])."</td>
                <td class='as-text-center'>
                    <a class='as-edit' href='principal.php?CONTENIDO=layout/components/grupo/form-grupo.php&id=".$grupo['id']."'>Editar</a>
                    <span class='as-trash' onclick='eliminar(".$grupo['id'].")'>Eliminar</span>
                </td>
            </tr>";
            $count++;
        }
    }
} catch (Exception $e) {
    $lista = "<tr><td colspan='4'>Error al cargar grupos: ".htmlspecialchars($e->getMessage())."</td></tr>";
}
?>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/grupo/lista-grupo.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Buscar por nombre del grado</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre">Nombre del grado</label>
                    <input type="text" name="nombre" id="nombre" required placeholder="Nombre del grado">
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    Buscar
                </button>
                <a class="as-color-btn-red" href="principal.php?CONTENIDO=layout/components/grupo/lista-grupo.php">
                    Limpiar
                </a>
            </div>
        </div>
    </form>
</div>

<div class="as-layout-table">
    <div>
        <h3 class="as-title-table">LISTADO DE GRUPOS</h3>
    </div>
    <div class="as-form-button-back">
        <a class="as-btn-back" href="principal.php?CONTENIDO=layout/components/grupo/form-grupo.php">Agregar grupo</a>
    </div>
    <div class="as-table-responsive">
        <table class="as-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre Grado</th>
                    <th scope="col">Nombre Grupo</th>
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
        if (respuesta) location = "principal.php?CONTENIDO=layout/components/grupo/form-grupo-action.php&accion=Eliminar&id=" + id;
    }
</script>