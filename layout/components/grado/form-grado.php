<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Adicionar';
$nameGrado = '';
$idGrado = null;
$idInstitucion = '';
$selected = '';
$selectMenu = '';

if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $array = new Grado('id', $_REQUEST['id']);
    $nameGrado = $array->getNombreGrado();
    $idInstitucion = $array->getIdInstitucion();
    $idGrado = $array->getId();
}

$totalInstituciones = InstitucionEducativa::getListaEnObjetos(null, null);

foreach ($totalInstituciones as $param) {
    $selected = $param->getId() == $idInstitucion ? 'selected' : '';
    $selectMenu .= '<option value="' . $param->getId() . '" ' . $selected . ' >' . $param->getNombre() . '</option>';
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/grado/lista-grado.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/grado/form-grado-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Nombre del grado</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre_grado">Nombre</label>
                    <input type="text" name="nombre_grado" id="nombre_grado" value="<?php echo $nameGrado; ?>" required placeholder="Nombre del grado">
                </div>
            </div>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="label" for="fin">Intitución</label>
                    <select class="as-form-select" name="id_institucion" id="id_institucion" required>
                        <?php
                        echo $selectMenu;
                        ?>
                    </select>
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $idGrado; ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>