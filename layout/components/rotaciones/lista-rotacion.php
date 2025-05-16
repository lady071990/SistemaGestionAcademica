<?php
require_once 'logica/clasesGenericas/ConectorBD.php';
require_once 'logica/clases/Rotaciones.php';


// Obtener todas las rotaciones
$rotaciones = Rotaciones::getListaEnObjetos();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Rotaciones</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Lista de Rotaciones</h1>
    <a href="form-rotacion.php">Asignar Nueva Rotación</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Estudiante ID</th>
                <th>Especialidad</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rotaciones as $rotacion): ?>
                <tr>
                    <td><?php echo $rotacion->getId(); ?></td>
                    <td><?php echo $rotacion->getEstudiante_id(); ?></td>
                    <td><?php echo Rotaciones::obtenerNombreEspecialidad($rotacion->getEspecialidad_id()); ?></td>
                    <td><?php echo $rotacion->getFecha_inicio(); ?></td>
                    <td><?php echo $rotacion->getFecha_fin(); ?></td>
                    <td><?php echo $rotacion->getEstado(); ?></td>
                    <td>
                        <?php if ($rotacion->getEstado() === 'en_curso'): ?>
                            <form action="form-rotacion-action.php" method="post" style="display:inline;">
                                <input type="hidden" name="accion" value="completar">
                                <input type="hidden" name="rotacion_id" value="<?php echo $rotacion->getId(); ?>">
                                <button type="submit">Completar</button>
                            </form>
                        <?php else: ?>
                            Completada
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
