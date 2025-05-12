<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Adicionar';
$nameActividad = '';
$idCouse = null;
if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $array = new TipoActividad('id', $_REQUEST['id']);
    $nameActividad = $array->getNombreActividad();
    $idCouse = $array->getId();
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/tipo-actividad/lista-tipo-actividad.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/tipo-actividad/form-tipo-actividad-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Nombre de la actividad</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre_actividad">Nombre</label>
                    <input type="text" name="nombre_actividad" id="nombre_actividad" value="<?php echo $nameActividad; ?>" required placeholder="Nombre del tipo de actividad">
                </div>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $idCouse; ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>