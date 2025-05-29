<?php 
require_once 'logica/clasesGenericas/ConectorBD.php';
require_once 'logica/clases/Usuario.php';

$estudiante = null;
$estudianteId = null;

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 5;
$offset = ($pagina - 1) * $limite;

if (isset($_GET['estudiante_id'])) {
    $estudianteId = $_GET['estudiante_id'];
    $estudiante = Usuario::getListaEnObjetos("id=$estudianteId", null);
    $estudiante = $estudiante ? $estudiante[0] : null;
}

// Procesar creación de nueva rotación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $estudianteId = $_POST['estudiante_id'];
    $especialidadId = $_POST['especialidad_id'];
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];
    $estado = 'en_curso';

    $conexion = ConectorBD::getConexion();

    // Validar solapamiento
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM historial_rotaciones 
        WHERE estudiante_id = ? AND (
            (fecha_inicio <= ? AND fecha_fin >= ?) OR
            (fecha_inicio <= ? AND fecha_fin >= ?) OR
            (fecha_inicio >= ? AND fecha_fin <= ?)
        )");
    $stmt->execute([$estudianteId, $fechaInicio, $fechaInicio, $fechaFin, $fechaFin, $fechaInicio, $fechaFin]);
    $solapadas = $stmt->fetchColumn();

    if ($solapadas > 0) {
        echo "<div class='alert alert-danger'>Error: Las fechas se solapan con otra rotación.</div>";
    } else {
        $query = "INSERT INTO historial_rotaciones (estudiante_id, especialidad_id, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$estudianteId, $especialidadId, $fechaInicio, $fechaFin, $estado]);
        echo "<div class='alert alert-success'>Rotación asignada con éxito.</div>";
    }
}

// Procesar eliminación
if (isset($_GET['eliminar_id'])) {
    $conexion = ConectorBD::getConexion();
    $stmt = $conexion->prepare("DELETE FROM historial_rotaciones WHERE id = ?");
    $stmt->execute([$_GET['eliminar_id']]);
    echo "<div class='alert alert-danger'>Rotación eliminada.</div>";
}

// Obtener rotaciones y especialidades
$conexion = ConectorBD::getConexion();
$rotaciones = [];
$especialidades = [];

if ($estudianteId) {
    $fechaActual = date('Y-m-d');

    // Actualizar estados para todas las rotaciones del estudiante antes de obtenerlas
    $updateStmt = $conexion->prepare("SELECT id, fecha_inicio, fecha_fin, estado FROM historial_rotaciones WHERE estudiante_id = ?");
    $updateStmt->execute([$estudianteId]);
    $rotacionesActualizar = $updateStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rotacionesActualizar as $rot) {
        $nuevoEstado = $rot['estado'];
        if ($rot['fecha_fin'] < $fechaActual) {
            $nuevoEstado = 'completada';
        } elseif ($rot['fecha_inicio'] > $fechaActual) {
            $nuevoEstado = 'pendiente';
        } else {
            $nuevoEstado = 'en_curso';
        }
        if ($nuevoEstado !== $rot['estado']) {
            $update = $conexion->prepare("UPDATE historial_rotaciones SET estado = ? WHERE id = ?");
            $update->execute([$nuevoEstado, $rot['id']]);
        }
    }

    // Luego continúa con la paginación y obtención de rotaciones con estados ya actualizados
    $totalStmt = $conexion->prepare("SELECT COUNT(*) FROM historial_rotaciones WHERE estudiante_id = ?");
    $totalStmt->execute([$estudianteId]);
    $totalRotaciones = $totalStmt->fetchColumn();
    $totalPaginas = ceil($totalRotaciones / $limite);

    $stmt = $conexion->prepare("
        SELECT hr.id, hr.fecha_inicio, hr.fecha_fin, hr.estado, e.nombre AS especialidad_nombre
        FROM historial_rotaciones hr
        JOIN especialidades e ON hr.especialidad_id = e.id
        WHERE hr.estudiante_id = ?
        ORDER BY hr.fecha_inicio DESC
        LIMIT $limite OFFSET $offset
    ");
    $stmt->execute([$estudianteId]);
    $rotaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if ($estudianteId) {
    $fechaActual = date('Y-m-d');

    // Actualizar estados para todas las rotaciones del estudiante antes de obtenerlas
    $updateStmt = $conexion->prepare("SELECT id, fecha_inicio, fecha_fin, estado FROM historial_rotaciones WHERE estudiante_id = ?");
    $updateStmt->execute([$estudianteId]);
    $rotacionesActualizar = $updateStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rotacionesActualizar as $rot) {
        $nuevoEstado = $rot['estado'];
        if ($rot['fecha_fin'] < $fechaActual) {
            $nuevoEstado = 'completada';
        } elseif ($rot['fecha_inicio'] > $fechaActual) {
            $nuevoEstado = 'pendiente';
        } else {
            $nuevoEstado = 'en_curso';
        }
        if ($nuevoEstado !== $rot['estado']) {
            $update = $conexion->prepare("UPDATE historial_rotaciones SET estado = ? WHERE id = ?");
            $update->execute([$nuevoEstado, $rot['id']]);
        }
    }

    // Luego continúa con la paginación y obtención de rotaciones con estados ya actualizados
    $totalStmt = $conexion->prepare("SELECT COUNT(*) FROM historial_rotaciones WHERE estudiante_id = ?");
    $totalStmt->execute([$estudianteId]);
    $totalRotaciones = $totalStmt->fetchColumn();
    $totalPaginas = ceil($totalRotaciones / $limite);

    $stmt = $conexion->prepare("
        SELECT hr.id, hr.fecha_inicio, hr.fecha_fin, hr.estado, e.nombre AS especialidad_nombre
        FROM historial_rotaciones hr
        JOIN especialidades e ON hr.especialidad_id = e.id
        WHERE hr.estudiante_id = ?
        ORDER BY hr.fecha_inicio DESC
        LIMIT $limite OFFSET $offset
    ");
    $stmt->execute([$estudianteId]);
    $rotaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $conexion->prepare("SELECT id, nombre FROM especialidades");
$stmt->execute();
$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Rotaciones</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Encabezados */
        h2 {
            color: #000000; /* Verde principal */
            margin-bottom: 20px;
        }
        
        h5 {
            margin: 15px 0;
            color: #000000; /* Verde principal */
        }
        
        /* Botones */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            font-size: 14px;
        }
        
        .btn-secondary {
            background-color: #455A64; /* Gris azulado */
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #37474F;
        }
        
        .btn-success {
            background-color: #2E7D32; /* Verde principal */
            color: white;
        }
        
        .btn-success:hover {
            background-color: #1B5E20; /* Verde más oscuro */
        }
        
        .btn-danger {
            background-color: #C62828; /* Rojo */
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #B71C1C;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        /* Tarjetas */
        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .card-header {
            padding: 12px 20px;
            font-weight: bold;
            color: white;
        }
        
        .bg-primary {
            background-color: #2E7D32; /* Verde principal */
        }
        
        .bg-success {
            background-color: #388E3C; /* Variante de verde */
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Tablas */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        .table th {
            background-color: #E8F5E9; /* Verde muy claro */
            color: #1B5E20; /* Verde oscuro */
            font-weight: bold;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .bg-success {
            background-color: #2E7D32; /* Verde principal */
            color: white;
        }
        
        .bg-warning {
            background-color: #FFA000; /* Ámbar */
            color: #212529;
        }
        
        .bg-secondary {
            background-color: #455A64; /* Gris azulado */
            color: white;
        }
        
        /* Formularios */
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2E7D32; /* Verde principal */
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #BDBDBD;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #2E7D32;
            outline: none;
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.2);
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .col-md-6 {
            width: 50%;
            padding: 0 10px;
            box-sizing: border-box;
        }
        
        .col-md-3 {
            width: 25%;
            padding: 0 10px;
            box-sizing: border-box;
        }
        
        .col-12 {
            width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
        }
        
        .text-end {
            text-align: right;
        }
        
        /* Alertas */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF9A9A;
        }
        
        /* Paginación */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            justify-content: center;
        }
        
        .page-item {
            margin: 0 5px;
        }
        
        .page-link {
            display: block;
            padding: 8px 16px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #2E7D32; /* Verde principal */
            border-radius: 4px;
        }
        
        .page-item.active .page-link {
            background-color: #2E7D32;
            color: white;
            border-color: #2E7D32;
        }
        
        /* Flex utilities */
        .d-flex {
            display: flex;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .mb-4 {
            margin-bottom: 20px;
        }
        
        .mt-5 {
            margin-top: 30px;
        }
        
        .text-muted {
            color: #757575;
        }
        
        .text-primary {
            color: #2E7D32; /* Verde principal */
        }
        
        /* Estilo para el mensaje destacado */
        .motto {
            font-style: italic;
            color: #2E7D32;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Rotaciones</h2>
        <a href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php" class="btn btn-secondary">Regresar</a>
    </div>

    <?php if ($estudiante) : ?>
        <div class="mb-4">
            <h5><strong>Estudiante:</strong> <?= htmlspecialchars($estudiante->getNombres()) . ' ' . htmlspecialchars($estudiante->getApellidos()) ?></h5>
        </div>

        <!-- Rotaciones existentes -->
        <div class="card mb-4">
            <div class="card-header bg-primary">Rotaciones Asignadas</div>
            <div class="card-body">
                <?php if (count($rotaciones) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Especialidad</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rotaciones as $rot): ?>
                            <tr>
                                <td><?= htmlspecialchars($rot['especialidad_nombre']) ?></td>
                                <td><?= htmlspecialchars($rot['fecha_inicio']) ?></td>
                                <td><?= htmlspecialchars($rot['fecha_fin']) ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $rot['estado'] === 'completada' ? 'bg-success' : 
                                            ($rot['estado'] === 'en_curso' ? 'bg-warning' : 'bg-secondary') ?>">
                                        <?= strtoupper($rot['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Botón de eliminar -->
                                    <a href="?CONTENIDO=layout/components/rotaciones/gestionar-rotacion.php&estudiante_id=<?= $estudianteId ?>&eliminar_id=<?= $rot['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta rotación?')">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?CONTENIDO=layout/components/rotaciones/gestionar-rotacion.php&estudiante_id=<?= $estudianteId ?>&pagina=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
                <?php else: ?>
                    <p class="text-muted">No hay rotaciones asignadas aún.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulario de nueva rotación -->
        <div class="card">
            <div class="card-header bg-success">Asignar Nueva Rotación</div>
            <div class="card-body">
                <form method="POST" class="row">
                    <input type="hidden" name="accion" value="crear">
                    <input type="hidden" name="estudiante_id" value="<?= $estudianteId ?>">

                    <div class="col-md-6">
                        <label for="especialidad_id" class="form-label">Especialidad</label>
                        <select name="especialidad_id" id="especialidad_id" class="form-select" required>
                            <option value="">Seleccione una especialidad</option>
                            <?php foreach ($especialidades as $esp): ?>
                                <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success">Asignar Rotación</button>
                    </div>
                </form>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-danger">No se ha seleccionado un estudiante válido.</div>
        <a href="lista-estudiante.php" class="btn btn-secondary">← Regresar</a>
    <?php endif; ?>
</div>

</body>
</html>