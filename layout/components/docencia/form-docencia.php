<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Acceso no autorizado');
$array = new InfoDocencia(null, null);
if (isset($_REQUEST['id'])) {
    $titulo = 'modificar';
    $array = new InfoDocencia('id', $_REQUEST['id']);
    $institution = InfoDocencia::getListaEnObjetos("id={$array->getId()}", null)[0];
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/inicio.php" class="as-btn-back">
        Atrás
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/docencia/form-docencia-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Actualizar datos del Hospital</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="<?= $institution->getNombre() ?>" required placeholder="Nombre">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="<?= $institution->getDireccion() ?>" required placeholder="Dirección">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="email">Correo electrónico</label>
                    <input type="email" name="email" id="email" value="<?= $institution->getEmail() ?>" required placeholder="Correo electrónico">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="telefono">Teléfono</label>
                    <input type="number" name="telefono" id="telefono" value="<?= $institution->getTelefono() ?>" required placeholder="# telefónico">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="director">Nombre del Coordinador/a</label>
                    <input type="text" name="director" id="director" value="<?= $institution->getNombreCoordinador()  ?>" required placeholder="Nombre del director/a">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="ulrWeb">Página Web</label>
                    <input type="url" name="ulrWeb" id="ulrWeb" value="<?= $institution->getPaginaWeb() ?>" required placeholder="Página web">
                </div>

            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    Actualizar
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $institution->getId() ?>">
        <input type="hidden" name="accion" value="<?= $titulo ?>">
    </form>
</div>