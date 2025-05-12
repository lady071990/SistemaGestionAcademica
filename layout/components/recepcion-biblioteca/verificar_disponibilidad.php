<?php
require_once 'logica/clases/RecepcionAulas.php';

$nombre_aula = $_POST['aula'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';
$id_excluir = $_POST['id_excluir'] ?? null;

// Verificar disponibilidad
$disponible = RecepcionAulas::verificarDisponibilidad($nombre_aula, $fecha, $hora_inicio, $hora_fin, $id_excluir);

// Si no está disponible, obtener información del conflicto
$conflicto = '';
if (!$disponible) {
    $filtro = "nombre_aula = '$nombre_aula' AND fecha_solicitud = '$fecha' AND (
        (hora_inicio BETWEEN '$hora_inicio' AND '$hora_fin') OR 
        (hora_fin BETWEEN '$hora_inicio' AND '$hora_fin') OR 
        ('$hora_inicio' BETWEEN hora_inicio AND hora_fin)
    )";
    
    if ($id_excluir) {
        $filtro .= " AND id != $id_excluir";
    }
    
    $reservas = RecepcionAulas::getLista($filtro, null);
    if (count($reservas) > 0) {
        $conflicto = 'Fecha: ' . $reservas[0]['fecha_solicitud'] . '\nHora: ' . 
                     $reservas[0]['hora_inicio'] . ' a ' . $reservas[0]['hora_fin'] . 
                     '\nDocente: ' . $reservas[0]['nombre_docente'];
    }
}

header('Content-Type: application/json');
echo json_encode([
    'disponible' => $disponible,
    'conflicto' => $conflicto
]);
?>