<?php

class GestionCupos
{
    protected $id;
    protected $especialidad;
    protected $numero_estudiantes;
    protected $institucion_educativa_id;
    protected $nombre_estudiante;
    protected $turno;
    protected $fecha_registro;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, especialidad, numero_estudiantes, institucion_educativa_id, nombre_estudiante, turno, fecha_registro FROM gestion_cupos WHERE $campo = $valor";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                $campo = $resultado ? $resultado[0] : null;
            }

            if ($campo) {
                $this->id = $campo['id'];
                $this->especialidad = $campo['especialidad'];
                $this->numero_estudiantes = $campo['numero_estudiantes'];
                $this->institucion_educativa_id = $campo['institucion_educativa_id'];
                $this->nombre_estudiante = $campo['nombre_estudiante'];
                $this->turno = $campo['turno'];
                $this->fecha_registro = $campo['fecha_registro'];
            }
        }
    }

    // Métodos getter
    public function getId() { return $this->id; }
    public function getEspecialidad() { return $this->especialidad; }
    public function getNumeroEstudiantes() { return $this->numero_estudiantes; }
    public function getInstitucionEducativaId() { return $this->institucion_educativa_id; }
    public function getNombreEstudiante() { return $this->nombre_estudiante; }
    public function getTurno() { return $this->turno; }
    public function getFechaRegistro() { return $this->fecha_registro; }

    // Métodos setter
    public function setId($id): void { $this->id = $id; }
    public function setEspecialidad($especialidad): void { $this->especialidad = $especialidad; }
    public function setNumeroEstudiantes($numero_estudiantes): void { $this->numero_estudiantes = $numero_estudiantes; }
    public function setInstitucionEducativaId($institucion_educativa_id): void { $this->institucion_educativa_id = $institucion_educativa_id; }
    public function setNombreEstudiante($nombre_estudiante): void { $this->nombre_estudiante = $nombre_estudiante; }
    public function setTurno($turno): void { $this->turno = $turno; }
    public function setFechaRegistro($fecha_registro): void {$this->fecha_registro = $fecha_registro;}

    public function __toString()
    {
        return $this->especialidad;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO gestion_cupos (especialidad, numero_estudiantes, institucion_educativa_id, nombre_estudiante, fecha_registro, turno) VALUES ('$this->especialidad', '$this->numero_estudiantes', '$this->institucion_educativa_id', '$this->nombre_estudiante', '$this->fecha_registro', '$this->turno')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE gestion_cupos SET especialidad='{$this->especialidad}', numero_estudiantes='{$this->numero_estudiantes}', institucion_educativa_id='{$this->institucion_educativa_id}', nombre_estudiante='{$this->nombre_estudiante}', fecha_registro='{$this->fecha_registro}', turno='{$this->turno}' WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM gestion_cupos WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, especialidad, numero_estudiantes, institucion_educativa_id, nombre_estudiante, turno, fecha_registro FROM gestion_cupos $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = GestionCupos::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $cupo = new GestionCupos($resultado[$i], null);
            $lista[$i] = $cupo;
        }
        return $lista;
    }
}