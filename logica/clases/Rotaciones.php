<?php
class Rotaciones {
    protected $id;
    protected $estudiante_id;
    protected $especialidad_id;
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $estado;

    public function __construct($campo = null, $valor = null) {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, estudiante_id, especialidad_id, fecha_inicio, fecha_fin, estado FROM historial_rotaciones WHERE $campo = ?";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL, [$valor]);
                if ($resultado && count($resultado) > 0) {
                    $campo = $resultado[0];
                } else {
                    throw new Exception("No se encontró la rotación con $campo = $valor");
                }
            }
            $this->id = $campo['id'];
            $this->estudiante_id = $campo['estudiante_id'];
            $this->especialidad_id = $campo['especialidad_id'];
            $this->fecha_inicio = $campo['fecha_inicio'];
            $this->fecha_fin = $campo['fecha_fin'];
            $this->estado = $campo['estado'];
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getEstudiante_id() {
        return $this->estudiante_id;
    }

    public function getEspecialidad_id() {
        return $this->especialidad_id;
    }

    public function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    public function getFecha_fin() {
        return $this->fecha_fin;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    // Cambia todos los setters para que retornen $this (para permitir method chaining)
    public function setEstudiante_id($estudiante_id) {
        $this->estudiante_id = $estudiante_id;
        return $this;
    }

    public function setEspecialidad_id($especialidad_id) {
        $this->especialidad_id = $especialidad_id;
        return $this;
    }

    public function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
        return $this;
    }

    public function setFecha_fin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
        return $this;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
        return $this;
    }

    
    public function guardar() {
        $cadenaSQL = "INSERT INTO historial_rotaciones (estudiante_id, especialidad_id, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?)";
        $parametros = [
            $this->estudiante_id,
            $this->especialidad_id,
            $this->fecha_inicio,
            $this->fecha_fin,
            $this->estado
        ];
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL, $parametros);
        
        if ($resultado) {
            $this->id = ConectorBD::getLastInsertId();
            return true;
        }
        return false;
    }

    public function modificar() {
        if (!$this->id) {
            throw new Exception("No se puede modificar una rotación sin ID");
        }
        
        $cadenaSQL = "UPDATE historial_rotaciones SET 
                     estudiante_id = ?, 
                     especialidad_id = ?, 
                     fecha_inicio = ?, 
                     fecha_fin = ?, 
                     estado = ? 
                     WHERE id = ?";
        $parametros = [
            $this->estudiante_id,
            $this->especialidad_id,
            $this->fecha_inicio,
            $this->fecha_fin,
            $this->estado,
            $this->id
        ];
        return (bool) ConectorBD::ejecutarQuery($cadenaSQL, $parametros);
    }

    public function eliminar() {
        if (!$this->id) {
            throw new Exception("No se puede eliminar una rotación sin ID");
        }
        
        $cadenaSQL = "DELETE FROM historial_rotaciones WHERE id = ?";
        return (bool) ConectorBD::ejecutarQuery($cadenaSQL, [$this->id]);
    }

    public static function getLista($filtro = '', $orden = '') {
        $where = ($filtro != null && $filtro != '') ? " WHERE $filtro" : '';
        $orderBy = ($orden != null && $orden != '') ? " ORDER BY $orden" : '';
        
        $cadenaSQL = "SELECT * FROM historial_rotaciones $where $orderBy";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro = '', $orden = '') {
        $resultados = self::getLista($filtro, $orden);
        $lista = array();
        
        foreach ($resultados as $registro) {
            $rotacion = new Rotaciones($registro, null);
            array_push($lista, $rotacion);
        }
        
        return $lista;
    }

    // Método adicional para obtener rotación actual
    public static function obtenerRotacionActual($estudiante_id) {
        $cadenaSQL = "SELECT hr.*, e.nombre as especialidad 
                     FROM historial_rotaciones hr
                     JOIN especialidades e ON hr.especialidad_id = e.id
                     WHERE hr.estudiante_id = ? AND hr.estado = 'en_curso'
                     ORDER BY hr.fecha_inicio DESC LIMIT 1";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL, [$estudiante_id]);
        return $resultado ? $resultado[0] : null;
    }
    
    public static function obtenerNombreEspecialidad($especialidad_id) {
    $cadenaSQL = "SELECT nombre FROM especialidades WHERE id = ?";
    $resultado = ConectorBD::ejecutarQuery($cadenaSQL, [$especialidad_id]);
    return $resultado ? $resultado[0]['nombre'] : 'Desconocida';
    }

    public static function completarRotacion($rotacion_id) {
    try {
        // 1. Marcar la rotación como completada
        $cadenaSQL = "UPDATE historial_rotaciones SET 
                     estado = 'completada', 
                     fecha_fin = CURDATE() 
                     WHERE id = ?";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL, [$rotacion_id]);
        
        if (!$resultado) {
            return ['error' => 'No se pudo completar la rotación'];
        }
        
        // 2. Obtener el estudiante_id para asignar nueva rotación
        $cadenaSQL = "SELECT estudiante_id FROM historial_rotaciones WHERE id = ?";
        $datos = ConectorBD::ejecutarQuery($cadenaSQL, [$rotacion_id]);
        
        if ($datos && count($datos) > 0) {
            $estudiante_id = $datos[0]['estudiante_id'];
            return self::asignarProximaRotacion($estudiante_id);
        }
        
        return ['error' => 'No se encontró la rotación'];
    } catch (Exception $e) {
        error_log("Error en completarRotacion: " . $e->getMessage());
        return ['error' => 'Error al procesar la solicitud'];
    }
}
    
    public static function asignarProximaRotacion($estudiante_id) {
    try {
        // 1. Obtener la última rotación completada del estudiante
        $cadenaSQL = "SELECT e.orden 
                     FROM historial_rotaciones hr
                     JOIN especialidades e ON hr.especialidad_id = e.id
                     WHERE hr.estudiante_id = ? AND hr.estado = 'completada'
                     ORDER BY e.orden DESC LIMIT 1";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL, [$estudiante_id]);
        
        $ultimo_orden = $resultado ? $resultado[0]['orden'] : 0;
        
        // 2. Obtener la siguiente especialidad en orden
        $cadenaSQL = "SELECT id, nombre, duracion_dias 
                     FROM especialidades 
                     WHERE orden > ? 
                     ORDER BY orden ASC LIMIT 1";
        $especialidad = ConectorBD::ejecutarQuery($cadenaSQL, [$ultimo_orden]);
        
        if (!$especialidad) {
            return ['error' => 'El estudiante ha completado todas las rotaciones'];
        }
        
        // 3. Crear la nueva rotación
        $nuevaRotacion = new Rotaciones();
        $nuevaRotacion->setEstudiante_id($estudiante_id)
                     ->setEspecialidad_id($especialidad[0]['id'])
                     ->setFecha_inicio(date('Y-m-d'))
                     ->setFecha_fin(date('Y-m-d', strtotime('+' . $especialidad[0]['duracion_dias'] . ' days')))
                     ->setEstado('en_curso');
        
        if ($nuevaRotacion->guardar()) {
            return ['mensaje' => 'Rotación asignada: ' . $especialidad[0]['nombre']];
        } else {
            return ['error' => 'Error al guardar la nueva rotación'];
        }
    } catch (Exception $e) {
        error_log("Error en asignarProximaRotacion: " . $e->getMessage());
        return ['error' => 'Error al procesar la solicitud'];
    }
}


}
?>