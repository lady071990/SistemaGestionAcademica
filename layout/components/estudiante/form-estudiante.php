<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$titulo = 'Adicionar';
$selected = '';
$selectMenu = '';
$selectMenuGrado = '';
$selectMenuGrupo = '';
$array = new Usuario(null, null);
if (isset($_REQUEST['id'])) {
    $titulo = 'Modificar';
    $array = new Usuario('id', $_REQUEST['id']);
}
?>

<div class="as-form-button-back">
    <a href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php" class="as-btn-back">
        Regresar
    </a>
</div>

<div class="as-form-content">
    <form name="formulario" method="post"  enctype="multipart/form-data" action="principal.php?CONTENIDO=layout/components/estudiante/form-estudiante-action.php" autocomplete="off">
        <div class="as-form-margin">
            <h2>Estudiantes</h2>
            <div class="as-form-fields">
                <div class="as-form-input">
                    <label class="hide-label" for="identificacion">Identificación</label>
                    <input type="number" name="identificacion" id="identificacion" value="<?= $array->getIdentificacion() ?>" required placeholder="Identificación" maxlength="10">
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
                    <label for="institucion_educativa_id">Institución Educativa</label>
                    <select name="institucion_educativa_id" id="institucion_educativa_id">
                        <?php
                        $instituciones = ConectorBD::ejecutarQuery("
                          SELECT id, nombre 
                          FROM institucion_educativa 
                          WHERE tipo IN ('Universidad', 'Instituto', 'Colegio', 'Escuela', 'Hospital')
                          ORDER BY nombre
                        ");

                        foreach ($instituciones as $inst) {
                          echo "<option value='{$inst['id']}'>{$inst['nombre']}</option>";
                        }
                        ?>
                      </select>
                </div>
                
                <div class="as-form-input"> 
                    <label for="form_name">Hoja de Vida</label>
                    <input name="hojaVida" type="file" id="hojaVida" value="<?= $array->getHojaVida()  ?>" type="file" class="form-control" required="required" data-error="Firstname is required.">
                </div>
                
                <div class="as-form-input"> 
                    <label for="form_name">Documentos</label>
                    <input name="documentos" type="file" id="documentos" value="<?= $array->getDocumentos()  ?>" type="file" class="form-control" required="required" data-error="Firstname is required.">
                </div>
                
                <div class="as-form-input"> 
                    <label for="form_name">Foto</label>
                    <input type="file" name="foto" id="foto" value="<?= $array->getFoto() ?>" onchange="mostrarFoto();" class="form-control" required="required" data-error="Firstname is required.">
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

            </div>
            <div class="as-form-button">
                <button class="as-color-btn-green" type="submit">
                    <?php echo $titulo; ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $array->getId() ?>">
        <input type="hidden" name="accion" value="<?php echo $titulo; ?>">
    </form>
</div>
<script type="text/javascript">
    function mostrarFoto() {
        var lector=new FileReader();
        lector.readAsDataURL(document.formulario.foto.files[0]);
        lector.onloadend = function (){
            document.getElementById("foto").src=lector.result;
        };
    }
</script>