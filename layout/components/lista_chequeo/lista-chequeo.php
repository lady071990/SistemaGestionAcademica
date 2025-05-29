<?php
@session_start();
if (!isset($_SESSION['usuario'])) header('location:../../index.php?mensaje=Acceso no autorizado');

require_once 'logica/clases/ListaChequeo.php';
require_once 'logica/clases/InstitucionEducativa.php';
require_once 'logica/clasesGenericas/ConectorBD.php';
require_once 'logica/clasesGenericas/Generalidades.php';

// Obtener el ID de la universidad desde la URL
$idUniversidad = isset($_GET['id_universidad']) ? $_GET['id_universidad'] : null;

// Obtener datos filtrados
if ($idUniversidad) {
    // Reemplazo de ConectorBD::escape() por una función de escape segura
    $idUniversidad = addslashes($idUniversidad); // Método básico de escape
    $filtro = "institucion_educativa_id = '$idUniversidad'";
    $listaChequeoItems = ListaChequeo::getListaEnObjetos($filtro, 'id');
    
    // Obtener nombre de la universidad
    $universidad = new InstitucionEducativa('id', $idUniversidad);
    $nombreUniversidad = $universidad->getNombre();
    $titulo = "LISTA DE CHEQUEO - ".htmlspecialchars($nombreUniversidad);
    $mostrarColumnaUniversidad = false;
} else {
    $listaChequeoItems = ListaChequeo::getListaEnObjetos(null, 'id');
    $titulo = "LISTA DE CHEQUEO - TODAS LAS UNIVERSIDADES";
    $mostrarColumnaUniversidad = true;
}

$count = 1;
$lista = '';

if (!empty($listaChequeoItems)) {
    foreach ($listaChequeoItems as $item) {
        $lista .= "<tr>";
        $lista .= '<th scope="row">' . $count . '</th>';
        
        // Mostrar universidad solo si estamos viendo todas
        if ($mostrarColumnaUniversidad) {
            $idInstitucion = $item->getInstitucion_educativa_id();
            $universidad = new InstitucionEducativa('id', $idInstitucion);
            $nombreUniversidad = $universidad->getNombre();
            $lista .= "<td class='as-text-center'>" . htmlspecialchars($nombreUniversidad) . "</td>";
        }
        
        // Documentos con icono PDF
        $lista .= "<td><a href='documentos/archivos/{$item->getConvenio()}' target='_blank' title='Ver convenio'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getObjetivo_convenio()}' target='_blank' title='Ver Objetivo del convenio'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getVigencia_convenio()}' target='_blank' title='Ver la vigencia del convenio'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getDeberes()}' target='_blank' title='Ver los deberes'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getPoliza_responsabilidad()}' target='_blank' title='Ver poliza de responsabilidad'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getPoliza_riesgo_biologico()}' target='_blank' title='Ver poliza de riesgo biologico'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getFormas_compensacion()}' target='_blank' title='Ver formas de compensacion'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getAnexo_tecnico()}' target='_blank' title='Ver anexo tecnico'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getCronograma()}' target='_blank' title='Ver cronograma'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getEsquema_vacunacion()}' target='_blank' title='Ver esquema de vacunacion'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getSsst()}' target='_blank' title='Ver certificado SSST'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td><a href='documentos/archivos/{$item->getArl()}' target='_blank' title='Ver certificado de ARL'><button type='button' class='btn btn-outline-danger'><img src='layout/img/PDF2.png' alt='PDF' style='width:20px; height:20px;'></button></a></td>";
        $lista .= "<td class='as-text-center'>" . htmlspecialchars($item->getFecha_subida()) . "</td>";
        // Opciones
        $idInstitucion = $item->getInstitucion_educativa_id();
        $universidad = new InstitucionEducativa('id', $idInstitucion);
        $nombreUniversidad = $universidad->getNombre();
        
        $lista .= "<td class='as-text-center'>";
        $lista .= "<a class='as-edit' href='principal.php?CONTENIDO=layout/components/lista_chequeo/form-chequeo.php&id={$item->getId()}&idUniversidad={$idInstitucion}&nombreUniversidad=" . urlencode($nombreUniversidad) . "'>" . Generalidades::getTooltip(1, 'Editar') . "</a>";
        $lista .= "<span class='as-trash' onClick='eliminar({$item->getId()})'>" . Generalidades::getTooltip(2, 'Eliminar') . "</span>";
        $lista .= "</td>";
        
        $lista .= "</tr>";
        $count++;
    }
} else {
    $colspan = $mostrarColumnaUniversidad ? 16 : 15;
    $lista = "<tr><td colspan='{$colspan}' style='text-align:center'>No hay documentos registrados".($idUniversidad ? " para esta universidad" : "")."</td></tr>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <style>
        .as-text-center {
            text-align: center;
        }
        .as-title-table {
            text-align: center;
            margin: 20px 0;
            color: #2c3e50;
        }
        .as-table-responsive {
            overflow-x: auto;
        }
        .as-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .as-table th, .as-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .as-table th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
        .as-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .as-table tr:hover {
            background-color: #f1f1f1;
        }
        .as-btn-back {
            display: inline-block;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .as-btn-back:hover {
            background-color: #2980b9;
        }
        .as-edit {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }
        .as-edit:hover {
            text-decoration: underline;
        }
        .as-trash {
            color: #e74c3c;
            cursor: pointer;
        }
        .as-trash:hover {
            text-decoration: underline;
        }
        .as-form-button-back {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="as-layout-table">
        <div>
            <h3 class="as-title-table"><?= $titulo ?></h3>
        </div>
        <div class="as-form-button-back">
            <a href="principal.php?CONTENIDO=layout/components/institucion/lista-institucion.php" class="as-btn-back">
                Regresar
            </a>
        </div>
        <div class="as-form-button-back">
            <a href="principal.php?CONTENIDO=layout/components/lista_chequeo/form-chequeo.php<?= $idUniversidad ? '&idUniversidad='.$idUniversidad : '' ?>" class="as-btn-back">
                Agregar Lista De Chequeo
            </a>
            <?php if ($idUniversidad): ?>
            <a href="principal.php?CONTENIDO=layout/components/lista_chequeo/lista-chequeo.php" class="as-btn-back" style="margin-left: 10px;">
                Ver todas las universidades
            </a>
            <?php endif; ?>
        </div>
        <div class="as-table-responsive">
            <table class="as-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <?php if ($mostrarColumnaUniversidad): ?>
                        <th>Universidad</th>
                        <?php endif; ?>
                        <th>Convenio</th>
                        <th>Objetivo</th>
                        <th>Vigencia</th>
                        <th>Deberes</th>
                        <th>Póliza Resp.</th>
                        <th>Póliza Riesgo</th>
                        <th>Compensación</th>
                        <th>Anexo</th>
                        <th>Cronograma</th>
                        <th>Vacunación</th>
                        <th>SSST</th>
                        <th>ARL</th>
                        <th>Fecha</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $lista ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function eliminar(id) {
        if(confirm("¿Está seguro de eliminar este registro?")) {
            location.href = "principal.php?CONTENIDO=layout/components/lista_chequeo/form-chequeo-action.php&accion=Eliminar&id=" + id;
        }
    }
    </script>
</body>
</html>