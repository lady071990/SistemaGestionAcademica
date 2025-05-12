<?php
require_once '../../../logica/clasesGenericas/ConectorBD.php';
require_once '../../../logica/clases/Grupo.php';
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');
$lista = '';
$count = 1;
$selectMenuGrupo = '';
$arrayGrupo = Grupo::getListaEnObjetos(null, 'nombre_grupo');

if (isset($_POST["id"])) {
    $arrayGrupo = Grupo::getListaEnObjetos("id_grado={$_POST['id']}", 'nombre_grupo');
}

foreach ($arrayGrupo as $paramGr) {
    $selectMenuGrupo .= '<option value="' . $paramGr->getId() . '">' . $paramGr->getNombreGrupo() . '</option>';
}

echo $selectMenuGrupo;


