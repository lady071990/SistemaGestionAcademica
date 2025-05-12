<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Adicionar';
$nameCourse = '';
$idCouse = null;
if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $array = new Asignatura('id', $_REQUEST['id']);
    $nameCourse = $array->getNombreAsignatura();
    $idCouse = $array->getId();
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/asignatura/lista-asignatura.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/asignatura/form-asignatura-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Nombre de la asignatura</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre_asignatura">Nombre</label>
                    <input type="text" name="nombre_asignatura" id="nombre_asignatura" value="<?php echo $nameCourse; ?>" required placeholder="Nombre de la asignatura">
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