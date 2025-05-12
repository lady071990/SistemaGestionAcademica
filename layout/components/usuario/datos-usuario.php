<?php

@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$editar = $USUARIO->getRolId();
$lista = '';
$count = 1;
$listaUsuarios = array();
$listaUsuarios = Usuario::getListaEnObjetos("id={$USUARIO->getId()}", '')[0];
?>

<div class="as-layout-institucion">
    <div class="as-card-institucion">
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Identificación</h4>
            </div>
            <div class="as-institution-description">
                <p><?= $listaUsuarios->getIdentificacion() ?></p>
            </div>
        </div>
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Nombres</h4>
            </div>
            <div class="as-institution-description">
                <p><?= $listaUsuarios->__toString() ?></p>
            </div>
        </div>
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Dirección</h4>
            </div>
            <div class="as-institution-description">
                <p><?= $listaUsuarios->getDireccion() ?></p>
            </div>
        </div>
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Correo electrónico</h4>
            </div>
            <div class="as-institution-description">
                <p><?= $listaUsuarios->getEmail() ?></p>
            </div>
        </div>
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Teléfono</h4>
            </div>
            <div class="as-institution-description">
                <p><?= $listaUsuarios->getTelefono() ?></p>
            </div>
        </div>
        
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Hoja de Vida</h4>
            </div>
            <div class="as-institution-description">
                <?php if ($listaUsuarios->getHojaVida()): ?>
                    <a href="../documentos/<?= $listaUsuarios->getHojaVida() ?>" target="_blank">Ver Hoja de Vida</a>
                <?php else: ?>
                    <p>No registrada</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Documentos</h4>
            </div>
            <div class="as-institution-description">
                <?php if ($listaUsuarios->getDocumentos()): ?>
                    <a href="../documentos/<?= $listaUsuarios->getDocumentos() ?>" target="_blank">Ver Documentos</a>
                <?php else: ?>
                    <p>No registrados</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Foto</h4>
            </div>
            <div class="as-institution-description">
                <?php if ($listaUsuarios->getFoto()): ?>
                    <img src="../layout/components/img/<?= $listaUsuarios->getFoto() ?>" alt="Foto del usuario" width="100">
                <?php else: ?>
                    <p>No disponible</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Rol</h4>
            </div>
            <div class="as-institution-description">
                <p><?= $listaUsuarios->getRolNombre() ?></p>
            </div>
        </div>
        <div class="as-institution">
            <div class="as-institution-title">
                <h4>Estado</h4>
            </div>
            <div class="as-institution-description">
                <p><?= Generalidades::getEstadoUsuario($listaUsuarios->getEstado()) ?></p>
            </div>
        </div>
        
    </div>
</div>