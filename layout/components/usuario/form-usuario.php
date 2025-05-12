<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$titulo = 'Adicionar';
$array = new Usuario(null, null);
$selected = '';
$selectMenu = '';
if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $array = new Usuario('id', $_REQUEST['id']);
}
?>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/usuario/form-usuario-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>USUARIO</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="identificacion">Identificación</label>
                    <input type="number" name="identificacion" id="identificacion" value="<?= $array->getIdentificacion() ?>" required maxlength="10" size="10" placeholder="Identificación">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="nombres">Nombres</label>
                    <input type="text" name="nombres" id="nombres" value="<?= $array->getNombres() ?>" required placeholder="Nombres">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="apellidos">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" value="<?= $array->getApellidos() ?>" required placeholder="Apellidos">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="telefono">Teléfono</label>
                    <input type="number" name="telefono" id="telefono" value="<?= $array->getTelefono() ?>" required placeholder="# telefónico">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="email">Correo electrónico</label>
                    <input type="email" name="email" id="email" value="<?= $array->getEmail() ?>" required placeholder="Correo electrónico">
                </div>

                <div class="as-form-input">
                    <label class="hide-label" for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="<?= $array->getDireccion() ?>" required placeholder="Dirección">
                </div>
                
                <div class="as-form-input">
                    <div class="form-group">
                        <label class="hide-label" for="hojavida">Hoja de Vida</label>
                        <input type="file" name="hojaVida" value="<?= $array->getHojaVida()  ?>" class="form-control" required placeholder="Hoja Vida"> 
                    </div>
                </div>
                
                <div class="as-form-input">
                    <div class="form-group">
                        <label class="hide-label" for="documentos">Documentos</label>
                        <input type="file" name="documentos" value="<?= $array->getDocumentos()  ?>" class="form-control" required placeholder="Documentos"> 
                    </div>
                </div>
                
                <div class="as-form-input">
                    <div class="form-group">
                        <label class="hide-label" for="foto">Foto</label>
                        <input type="file" name="foto" value="<?= $array->getFoto() ?>" class="form-control" required placeholder="Foto"> 
                    </div>
                </div>
                

                <?php
                if ($titulo == 'Modificar') {
                ?>
                    <div class="as-form-input">
                        <label class="hide-label" for="pass">Contraseña</label>
                        <input type="text" name="pass" id="pass" placeholder="Actualizar contraseña">
                    </div>
                <?php
                }
                if ($editar == 1 || $editar == 6) {
                ?>
                    <div class="as-form-fields">
                        <div class="as-form-input">
                            <label class="label" for="estado">Estado</label>
                            <select class="as-form-select" name="estado" id="estado" required>
                                <?php
                                for ($i = 1; $i < 3; $i++) {
                                    $selected = $array->getEstado() == $i ? 'selected' : '';
                                    $selectMenu .= '<option value="' . $i . '" ' . $selected . ' >' . Generalidades::getEstadoUsuario($i) . ' </option>';
                                }
                                echo $selectMenu;
                                ?>
                            </select>
                        </div>
                    </div>
                <?php
                } else {
                    echo '<input type="hidden" name="estado" value="' . $array->getEstado() . '">';
                }
                ?>
            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $array->getId() ?>">
        <input type="hidden" name="rol_id" value="<?= $array->getRolId() ?>">
        <input type="hidden" name="accion" value="<?php echo $titulo ?>">
    </form>
</div>