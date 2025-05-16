<?php
require_once 'logica/clasesGenericas/ConectorBD.php';
require_once 'logica/clases/Rotaciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'asignar') {
        $estudiante_id = $_POST['estudiante_id'];
        $especialidad_id = $_POST['especialidad_id'];

        // Obtener duración de la especialidad
        $especialidad = ConectorBD::ejecutarQuery("SELECT duracion_dias FROM especialidades WHERE id = ?", [$especialidad_id]);
        $duracion_dias = $especialidad ? $especialidad[0]['duracion_dias'] : 30;

        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d', strtotime("+$duracion_dias days"));

        $rotacion = new Rotaciones();
        $rotacion->setEstudiante_id($estudiante_id)
                 ->setEspecialidad_id($especialidad_id)
                 ->setFecha_inicio($fecha_inicio)
                 ->setFecha_fin($fecha_fin)
                 ->setEstado('en_curso');

        if ($rotacion->guardar()) {
            header("Location: lista-rotacion.php");
            exit;
        } else {
            echo "Error al asignar la rotación.";
        }
    } elseif ($accion === 'completar') {
        $rotacion_id = $_POST['rotacion_id'];
        $resultado = Rotaciones::completarRotacion($rotacion_id);

        if (isset($resultado['error'])) {
            echo "Error: " . $resultado['error'];
        } else {
            header("Location: lista-rotacion.php");
            exit;
        }
    } else {
        echo "Acción no válida.";
    }
} else {
    echo "Método no permitido.";
}
?>
