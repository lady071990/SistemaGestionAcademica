<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso no autorizado');
    exit;
}

require_once 'logica/clases/InfoDocencia.php';
require_once 'logica/clases/AnioEscolar.php';

$USUARIO = unserialize($_SESSION['usuario']);

// Cargar la institución (puedes cambiar el ID dinámicamente si no es siempre 1)
$instituciones = InfoDocencia::getListaEnObjetos('id=1', null);
$institution = !empty($instituciones) ? $instituciones[0] : null;

// Cargar el año escolar activo
$aniosEscolares = AnioEscolar::getListaEnObjetos('estado=1', null);
$anioEscolar = !empty($aniosEscolares) ? $aniosEscolares[0] : null;
?>

<div style="text-align: center; margin-top: 20px;">
    <h2 style="margin-top: 20px;">Bienvenido al Sistema</h2>
    <p>Seleccione una opción del menú para continuar</p>
    <img src="layout/img/logoDU.png" alt="Logo del Sistema" style="max-width: 300px;">
</div>

<?php if ($institution): ?>
    <div class="as-layout-institucion">
        <div class="as-card-institucion">
            <div class="as-institution">
                <div class="as-institution-title"><h4>Nombres</h4></div>
                <div class="as-institution-description"><p><?= $institution->getNombre() ?></p></div>
            </div>
            <div class="as-institution">
                <div class="as-institution-title"><h4>Dirección</h4></div>
                <div class="as-institution-description"><p><?= $institution->getDireccion() ?></p></div>
            </div>
            <div class="as-institution">
                <div class="as-institution-title"><h4>Correo electrónico</h4></div>
                <div class="as-institution-description"><p><?= $institution->getEmail() ?></p></div>
            </div>
            <div class="as-institution">
                <div class="as-institution-title"><h4>Teléfono</h4></div>
                <div class="as-institution-description"><p><?= $institution->getTelefono() ?></p></div>
            </div>
            <div class="as-institution">
                <div class="as-institution-title"><h4>Directora</h4></div>
                <div class="as-institution-description"><p><?= $institution->getNombreCoordinador() ?></p></div>
            </div>
            <div class="as-institution">
                <div class="as-institution-title"><h4>Página Web</h4></div>
                <div class="as-institution-description"><p><?= $institution->getPaginaWeb() ?></p></div>
            </div>
            <div class="as-institution">
                <div class="as-institution-title"><h4>Año Escolar</h4></div>
                <div class="as-institution-description">
                    <p><?= $anioEscolar ? $anioEscolar->__toString() : 'No disponible' ?></p>
                </div>
            </div>
        </div>

        <?php if ($USUARIO->getRolId() == 1 || $USUARIO->getRolId() == 6): ?>
            <div class="as-form-button">
                <a href="principal.php?CONTENIDO=layout/components/docencia/form-docencia.php&id=<?= $institution->getId(); ?>" class="as-color-btn-green">
                    Editar información
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php else: ?>
    <p style="color: red; text-align: center;">No se encontró la información de la institución.</p>
<?php endif; ?>
