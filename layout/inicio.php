<?php
@session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../../index.php?mensaje=Acceso no autorizado');
    exit();
}
?>

<?php
$idUniversidad = $_SESSION['institucion_educativa_id'] ?? null;
$universidad = $idUniversidad ? new InstitucionEducativa('id', $idUniversidad) : null;
if ($universidad) {
    echo "<div class='as-universidad'>Universidad: " . $universidad->getNombre() . "</div>";
}
?>


<div style="text-align: center; margin-top: 100px;">
    <!-- Reemplaza "logo.png" con la ruta de tu logo -->
    <img src="layout/img/logoDU.png" alt="Logo del Sistema" style="max-width: 300px;">
    
    <h2 style="margin-top: 20px;">Bienvenido al Sistema</h2>
    <p>Seleccione una opción del menú para continuar</p>
</div>