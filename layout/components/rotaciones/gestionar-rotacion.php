<?php
require_once 'logica/clasesGenericas/ConectorBD.php';
require_once 'logica/clases/Usuario.php';

$estudiante = null;
$estudianteId = null;

if (isset($_GET['estudiante_id'])) {
    $estudianteId = $_GET['estudiante_id'];
    $estudiante = Usuario::getListaEnObjetos("id=$estudianteId", null);
    $estudiante = $estudiante ? $estudiante[0] : null;
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estudianteId = $_POST['estudiante_id'];
    $especialidadId = $_POST['especialidad_id'];
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];
    $estado = 'en_curso';

    $conexion = ConectorBD::getConexion();
    $query = "INSERT INTO historial_rotaciones (estudiante_id, especialidad_id, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$estudianteId, $especialidadId, $fechaInicio, $fechaFin, $estado]);

    echo "<div class='alert alert-success'>Rotación asignada con éxito.</div>";
}

// Obtener rotaciones y especialidades
$conexion = ConectorBD::getConexion();
$rotaciones = [];
$especialidades = [];

if ($estudianteId) {
    $stmt = $conexion->prepare("
        SELECT hr.fecha_inicio, hr.fecha_fin, hr.estado, e.nombre AS especialidad_nombre
        FROM historial_rotaciones hr
        JOIN especialidades e ON hr.especialidad_id = e.id
        WHERE hr.estudiante_id = ?
    ");
    $stmt->execute([$estudianteId]);
    $rotaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $conexion->prepare("SELECT id, nombre FROM especialidades");
$stmt->execute();
$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML con Bootstrap -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Rotaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Gestión de Rotaciones</h2>
        <a href="principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php" class="btn btn-secondary">
        Regresar
    </a>
    </div>

    <?php if ($estudiante) : ?>
        <div class="mb-4">
            <h5><strong>Estudiante:</strong> <?= $estudiante->getNombres() . ' ' . $estudiante->getApellidos() ?></h5>
        </div>

        <!-- Rotaciones existentes -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Rotaciones Asignadas</div>
            <div class="card-body">
                <?php if (count($rotaciones) > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Especialidad</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rotaciones as $rot): ?>
                            <tr>
                                <td><?= htmlspecialchars($rot['especialidad_nombre']) ?></td>
                                <td><?= htmlspecialchars($rot['fecha_inicio']) ?></td>
                                <td><?= htmlspecialchars($rot['fecha_fin']) ?></td>
                                <td><span class="badge bg-<?= $rot['estado'] === 'completada' ? 'success' : 'warning' ?>"><?= htmlspecialchars($rot['estado']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No hay rotaciones asignadas aún.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulario -->
        <div class="card">
            <div class="card-header bg-success text-white">Asignar Nueva Rotación</div>
            <div class="card-body">
                <form method="POST" class="row g-3">
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

