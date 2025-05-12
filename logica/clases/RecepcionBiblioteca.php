<?php

class RecepcionBiblioteca
{
    protected $id;
    protected $numero_computadores;
    protected $nombre_proyecto;
    protected $numero_estudiantes;
    protected $nombre_estudiantes;
    protected $institucion_educativa_id;
    protected $hora_inicio;
    protected $hora_fin;
    protected $tiempo_asignado;
    protected $fecha_solicitud;
    
    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, numero_computadores, nombre_proyecto, numero_estudiantes, nombre_estudiantes, institucion_educativa_id, hora_inicio, hora_fin, tiempo_asignado, fecha_solicitud FROM recepcion_biblioteca WHERE $campo = '$valor'";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                $campo = $resultado ? $resultado[0] : null;
            }

            if ($campo) {
                $this->id = $campo['id'];
                $this->numero_computadores = $campo['numero_computadores'];
                $this->nombre_proyecto = $campo['nombre_proyecto'];
                $this->numero_estudiantes = $campo['numero_estudiantes'];
                $this->nombre_estudiantes = $campo['nombre_estudiantes'];
                $this->institucion_educativa_id = $campo['institucion_educativa_id'];
                $this->hora_inicio = $campo['hora_inicio'];
                $this->hora_fin = $campo['hora_fin'];
                $this->tiempo_asignado = $campo['tiempo_asignado'];
                $this->fecha_solicitud = $campo['fecha_solicitud'];
            }
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNumero_computadores() {return $this->numero_computadores;}
    public function getNombre_proyecto() {return $this->nombre_proyecto;}
    public function getNumero_estudiantes() {return $this->numero_estudiantes;}
    public function getNombre_estudiantes() {return $this->nombre_estudiantes;}
    public function getInstitucionEducativaId() { return $this->institucion_educativa_id;}
    public function getHora_inicio() {return $this->hora_inicio;}
    public function getHora_fin() {return $this->hora_fin;}
    public function getTiempoAsignado() { return $this->tiempo_asignado;}
    public function getFechaSolicitud() { return $this->fecha_solicitud;}

    // Setters
    public function setId($id): void {$this->id = $id;}
    public function setNumero_computadores($numero_computadores): void {$this->numero_computadores = $numero_computadores;}
    public function setNombre_proyecto($nombre_proyecto): void {$this->nombre_proyecto = $nombre_proyecto;}
    public function setNumero_estudiantes($numero_estudiantes): void {$this->numero_estudiantes = $numero_estudiantes;}
    public function setNombre_estudiantes($nombre_estudiantes): void {$this->nombre_estudiantes = $nombre_estudiantes;}
    public function setInstitucionEducativaId($institucion_educativa_id) {$this->institucion_educativa_id = $institucion_educativa_id;}
    public function setHora_inicio($hora_inicio): void {$this->hora_inicio = $hora_inicio;}
    public function setHora_fin($hora_fin): void {$this->hora_fin = $hora_fin;}
    public function setTiempoAsignado($tiempo_asignado) {$this->tiempo_asignado = $tiempo_asignado;}
    public function setFecha_solicitud($fecha_solicitud): void {$this->fecha_solicitud = $fecha_solicitud;}

 
    public function __toString()
    {
        return $this->especialidad;
    }

     public function guardar()
    {
        $cadenaSQL = "INSERT INTO recepcion_biblioteca (numero_computadores, nombre_proyecto, numero_estudiantes, nombre_estudiantes, institucion_educativa_id, hora_inicio, hora_fin, tiempo_asignado, fecha_solicitud) VALUES ('$this->numero_computadores', '$this->nombre_proyecto', '$this->numero_estudiantes', '$this->nombre_estudiantes', '$this->institucion_educativa_id', '$this->hora_inicio', '$this->hora_fin', '$this->tiempo_asignado', '$this->fecha_solicitud')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE recepcion_biblioteca SET numero_computadores='{$this->numero_computadores}', nombre_proyecto='{$this->nombre_proyecto}', numero_estudiantes='{$this->numero_estudiantes}', nombre_estudiantes='{$this->nombre_estudiantes}', institucion_educativa_id='{$this->institucion_educativa_id}', hora_inicio='{$this->hora_inicio}', hora_fin='{$this->hora_fin}', tiempo_asignado='{$this->tiempo_asignado}', fecha_solicitud='{$this->fecha_solicitud}' WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM gestion_biblioteca WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, numero_computadores, nombre_proyecto, numero_estudiantes, nombre_estudiantes, institucion_educativa_id, hora_inicio, hora_fin, tiempo_asignado, fecha_solicitud FROM recepcion_biblioteca $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = RecepcionBiblioteca::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $cupo = new RecepcionBiblioteca($resultado[$i], null);
            $lista[$i] = $cupo;
        }
        return $lista;
    }
    
}