<?php

class RecepcionAulas
{
    protected $id;
    protected $nombre_aula;
    protected $nombre_estudiante;
    protected $nombre_docente;
    protected $institucion_educativa_id;
    protected $nombre_tema;
    protected $semestre;
    protected $hora_inicio;
    protected $hora_fin;
    protected $tiempo_asignado;
    protected $fecha_solicitud;
    
    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre_aula, nombre_estudiante, nombre_docente, institucion_educativa_id, nombre_tema, semestre, hora_inicio, hora_fin, tiempo_asignado, fecha_solicitud FROM recepcion_aulas WHERE $campo = '$valor'";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                $campo = $resultado ? $resultado[0] : null;
            }

            if ($campo) {
                $this->id = $campo['id'];
                $this->nombre_aula = $campo['nombre_aula'];
                $this->nombre_estudiante = $campo['nombre_estudiante'];
                $this->nombre_docente = $campo['nombre_docente'];
                $this->institucion_educativa_id = $campo['institucion_educativa_id'];
                $this->nombre_tema = $campo['nombre_tema'];
                $this->semestre = $campo['semestre'];
                $this->hora_inicio = $campo['hora_inicio'];
                $this->hora_fin = $campo['hora_fin'];
                $this->tiempo_asignado = $campo['tiempo_asignado'];
                $this->fecha_solicitud = $campo['fecha_solicitud'];
            }
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombreAula() { return $this->nombre_aula; }
    public function getNombreEstudiante() { return $this->nombre_estudiante; }
    public function getNombreDocente() { return $this->nombre_docente; }
    public function getInstitucionEducativaId() { return $this->institucion_educativa_id; }
    public function getNombreTema() { return $this->nombre_tema; }
    public function getSemestre() { return $this->semestre; }
    public function getHora_inicio() {return $this->hora_inicio;}
    public function getHora_fin() {return $this->hora_fin;}
    public function getTiempoAsignado() { return $this->tiempo_asignado; }
    public function getFechaSolicitud() { return $this->fecha_solicitud; }

    // Setters
    public function setId($id): void {$this->id = $id;}
    public function setNombreAula($nombre_aula) { $this->nombre_aula = $nombre_aula; }
    public function setNombreEstudiante($nombre_estudiante) { $this->nombre_estudiante = $nombre_estudiante; }
    public function setNombreDocente($nombre_docente) { $this->nombre_docente = $nombre_docente; }
    public function setInstitucionEducativaId($institucion_educativa_id) { $this->institucion_educativa_id = $institucion_educativa_id; }
    public function setNombreTema($nombre_tema) { $this->nombre_tema = $nombre_tema; }
    public function setSemestre($semestre) { $this->semestre = $semestre; }
    public function setHora_inicio($hora_inicio): void {$this->hora_inicio = $hora_inicio;}
    public function setHora_fin($hora_fin): void {$this->hora_fin = $hora_fin;}
    public function setTiempoAsignado($tiempo_asignado) { $this->tiempo_asignado = $tiempo_asignado; }
    public function setFecha_solicitud($fecha_solicitud): void {$this->fecha_solicitud = $fecha_solicitud;}

 
    public function __toString()
    {
        return $this->especialidad;
    }

     public function guardar()
    {
        $cadenaSQL = "INSERT INTO recepcion_aulas (nombre_aula, nombre_estudiante, nombre_docente, institucion_educativa_id, nombre_tema, semestre, hora_inicio, hora_fin, tiempo_asignado, fecha_solicitud) VALUES ('$this->nombre_aula', '$this->nombre_estudiante', '$this->nombre_docente', '$this->institucion_educativa_id', '$this->nombre_tema', '$this->semestre', '$this->hora_inicio', '$this->hora_fin', '$this->tiempo_asignado', '$this->fecha_solicitud')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE recepcion_aulas SET nombre_aula='{$this->nombre_aula}', nombre_estudiante='{$this->nombre_estudiante}', nombre_docente='{$this->nombre_docente}', institucion_educativa_id='{$this->institucion_educativa_id}', nombre_tema='{$this->nombre_tema}', semestre='{$this->semestre}', hora_inicio='{$this->hora_inicio}', hora_fin='{$this->hora_fin}', tiempo_asignado='{$this->tiempo_asignado}', fecha_solicitud='{$this->fecha_solicitud}' WHERE id={$ID}";
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
        $cadenaSQL = "SELECT id, nombre_aula, nombre_estudiante, nombre_docente, institucion_educativa_id, nombre_tema, semestre, hora_inicio, hora_fin, tiempo_asignado, fecha_solicitud FROM recepcion_aulas $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = RecepcionAulas::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $cupo = new RecepcionAulas($resultado[$i], null);
            $lista[$i] = $cupo;
        }
        return $lista;
    }  
    
}