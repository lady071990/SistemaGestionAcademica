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

<div class="as-form-button-back">
    <?php
    if ($editar == 1 || $editar == 6) {
        echo '<a href="principal.php?CONTENIDO=layout/components/docente/lista-docente.php" class="as-btn-back">
                Regresar
            </a>';
    } else {
        echo '<a href="principal.php?CONTENIDO=layout/components/docente/lista-asignacion-docente.php" class="as-btn-back">
                Regresar
            </a>';
    }
    ?>
</div>

<div class="as-form-content">
    <form name="formulario" method="post" action="principal.php?CONTENIDO=layout/components/docente/form-docente-action.php" autocomplete="off" enctype="multipart/form-data">
        <div class="as-form-margin">
            <h2>Docentes</h2>
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
                    <label for="form_name">Hoja de Vida</label>
                    <input name="hojaVida" type="file" id="hojaVida" class="form-control" required="required">
                </div>
                
                <div class="as-form-input"> 
                    <label for="form_name">Documentos</label>
                    <input name="documentos" type="file" id="documentos" class="form-control" required="required">
                </div>
                
                <div class="as-form-input">
                    <label for="tipo_vinculacion">Tipo de Vinculación</label>
                    <select name="tipo_vinculacion" id="tipo_vinculacion" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="Tiempo Completo" <?= $array->getTipoVinculacion() == 'Tiempo Completo' ? 'selected' : '' ?>>Tiempo Completo</option>
                        <option value="Medio Tiempo" <?= $array->getTipoVinculacion() == 'Medio Tiempo' ? 'selected' : '' ?>>Medio Tiempo</option>
                        <option value="Nomina" <?= $array->getTipoVinculacion() == 'Nomina' ? 'selected' : '' ?>>Nomina</option>
                        <option value="Prestacion de Servicios" <?= $array->getTipoVinculacion() == 'Prestación de Servicios' ? 'selected' : '' ?>>Prestación de Servicios</option>
                        <option value="Nombramiento Temporal" <?= $array->getTipoVinculacion() == 'Nombramiento Temporal' ? 'selected' : '' ?>>Nombramiento Temporal</option>
                        <option value="Libre Nombramiento y Remoción" <?= $array->getTipoVinculacion() == 'Libre Nombramiento y remoción' ? 'selected' : '' ?>>Libre Nombramiento y Remoción</option>
                    </select>
                </div>

                <div class="as-form-input">
                    <label for="experiencia_laboral">Experiencia Laboral Relacionada</label>
                    <select name="experiencia_laboral" id="experiencia_laboral" class="form-control" required>
                        <option value="">Seleccione años de experiencia</option>
                        <?php
                        for ($i = 1; $i <= 50; $i++) {
                            $selected = $array->getExperienciaLaboral() == $i ? 'selected' : '';
                            echo "<option value='$i' $selected>$i año" . ($i > 1 ? 's' : '') . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="as-form-input">
                    <label>Certificación de Postgrado en Docencia</label>
                    <div>
                        <input type="radio" name="certificacion_postgrado" id="certificacion_si" value="1" <?= $array->getCertificacionPostgrado() ? 'checked' : '' ?>>
                        <label for="certificacion_si" style="display: inline; margin-right: 15px;">Sí</label>

                        <input type="radio" name="certificacion_postgrado" id="certificacion_no" value="0" <?= !$array->getCertificacionPostgrado() ? 'checked' : '' ?>>
                        <label for="certificacion_no" style="display: inline;">No</label>
                    </div>
                </div>

                <div class="as-form-input" id="fecha_certificacion_container" style="<?= !$array->getCertificacionPostgrado() ? 'display: none;' : '' ?>">
                    <label for="fecha_certificacion">Fecha de Certificación</label>
                    <input type="date" name="fecha_certificacion" id="fecha_certificacion" value="<?= $array->getFechaCertificacion() ?>" class="form-control">
                </div>

                <div class="as-form-input">
                    <label for="perfil_profesional">Perfil Profesional</label>
                    <textarea name="perfil_profesional" id="perfil_profesional" class="form-control" rows="3"><?= $array->getPerfilProfesional() ?></textarea>
                </div>

                <script>
                document.querySelectorAll('input[name="certificacion_postgrado"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        document.getElementById('fecha_certificacion_container').style.display = 
                            this.value === '1' ? 'block' : 'none';
                    });
                });
                </script>
                
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
        <input type="hidden" name="accion" value="<?php echo $titulo ?>">
    </form>
</div>