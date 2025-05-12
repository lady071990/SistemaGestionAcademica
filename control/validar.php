<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../logica/clases/Usuario.php';
require_once '../logica/clasesGenericas/ConectorBD.php';

$usuario = $_REQUEST['usuario'];
$clave = $_REQUEST['clave'];
$usuario = Usuario::validar($usuario, $clave);

if ($usuario == null) {
    header('location:../index.php?mensaje=usuario o contraseña no valido');
} else {
    session_start();
    $_SESSION['usuario'] = serialize($usuario);
    header('location: ../principal.php?CONTENIDO=layout/inicio.php');
}

