<?php

class NotasConsulta
{
    protected $id;
    protected $id_usuario_estudiante;
    protected $id_periodo_academico;
    protected $id_asignatura;
    protected $id_tipo_actividad;
    protected $fecha_creacion;
    protected $fecha_modificacion;
    protected $nota;
    protected $nombre_grado;
    protected $nombre_grupo;
    protected $iden_estudiante;
    protected $institucion_educativa_id;
    protected $foto;
    protected $programa_academico;

    public function __construct($campo, $suma)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT  n.id, n.id_usuario_estudiante, n.id_periodo_academico, n.id_asignatura, n.id_tipo_actividad, n.nota, n.fecha_creacion, n.fecha_modificacion,
                            u.identificacion as iden_estudiante, u.nombres, u.apellidos, u.estado, u.foto, u.programa_academico,
                            pa.inicio_periodo, pa.finalizacion_periodo, pa.nombre as nombre_periodo, pa.id_anio_escolar,
                            a.nombre_asignatura,
                            ta.nombre_actividad,
                            ge.id_grupo, g.nombre_grupo, g.id_grado, gd.nombre_grado,
                            u.institucion_educativa_id, ie.nombre as nombre_institucion
                            FROM nota n
                            JOIN usuario u ON n.id_usuario_estudiante = u.id 
                            JOIN periodo_academico pa ON n.id_periodo_academico = pa.id
                            JOIN asignatura a ON n.id_asignatura = a.id
                            JOIN tipo_actividad ta ON n.id_tipo_actividad = ta.id 
                            JOIN grupo_estudiante ge ON u.id = ge.id_usuario_estudiante 
                            JOIN grupo g ON ge.id_grupo = g.id
                            JOIN grado gd ON g.id_grado = gd.id
                            JOIN institucion_educativa ie ON u.institucion_educativa_id = ie.id $campo";

                $cadenaSQLSuma = "SELECT n.id, n.id_usuario_estudiante, n.id_periodo_academico, n.id_asignatura, n.id_tipo_actividad, SUM(n.nota) as nota, n.fecha_creacion, n.fecha_modificacion,
                            u.identificacion as iden_estudiante, u.nombres, u.apellidos, u.estado, u.foto, u.programa_academico,
                            pa.inicio_periodo, pa.finalizacion_periodo, pa.nombre as nombre_periodo, pa.id_anio_escolar,
                            a.nombre_asignatura,
                            ta.nombre_actividad,
                            ge.id_grupo, g.nombre_grupo, g.id_grado, gd.nombre_grado,
                            u.institucion_educativa_id, ie.nombre as nombre_institucion
                            FROM nota n
                            JOIN usuario u ON n.id_usuario_estudiante = u.id 
                            JOIN periodo_academico pa ON n.id_periodo_academico = pa.id
                            JOIN asignatura a ON n.id_asignatura = a.id
                            JOIN tipo_actividad ta ON n.id_tipo_actividad = ta.id 
                            JOIN grupo_estudiante ge ON u.id = ge.id_usuario_estudiante 
                            JOIN grupo g ON ge.id_grupo = g.id
                            JOIN grado gd ON g.id_grado = gd.id
                            JOIN institucion_educativa ie ON u.institucion_educativa_id = ie.id $campo";
                
                if ($suma) {
                    $campo = ConectorBD::ejecutarQuery($cadenaSQLSuma)[0];
                } else {
                    $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                }
            }
            
            $this->id = $campo['id'];
            $this->id_usuario_estudiante = $campo['id_usuario_estudiante'];
            $this->id_asignatura = $campo['id_asignatura'];
            $this->id_periodo_academico = $campo['id_periodo_academico'];
            $this->id_tipo_actividad = $campo['id_tipo_actividad'];
            $this->nota = $campo['nota'];
            $this->nombre_grado = $campo['nombre_grado'];
            $this->nombre_grupo = $campo['nombre_grupo'];
            $this->fecha_creacion = $campo['fecha_creacion'];
            $this->fecha_modificacion = $campo['fecha_modificacion'];
            $this->iden_estudiante = $campo['iden_estudiante'];
            $this->institucion_educativa_id = $campo['institucion_educativa_id'] ?? null;
            $this->foto = $campo['foto'] ?? null;
            $this->programa_academico = $campo['programa_academico'] ?? null;
        }
    }

    // Métodos getters existentes
    public function getId() { return $this->id; }
    public function getIdUsuarioEstudiante() { return $this->id_usuario_estudiante; }
    public function getIdentificacionEstudiante() { return $this->iden_estudiante; }
    public function getIdAsignatura() { return $this->id_asignatura; }
    public function getIdPeriodoAcademico() { return $this->id_periodo_academico; }
    public function getIdTipoActividad() { return $this->id_tipo_actividad; }
    public function getNota() { return $this->nota; }
    public function getNombreGrado() { return $this->nombre_grado; }
    public function getNombreGrupo() { return $this->nombre_grupo; }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function getFechaModificacion() { return $this->fecha_modificacion; }

    // Nuevos métodos getters
    public function getInstitucionEducativaId() { return $this->institucion_educativa_id; }
    public function getFoto() { return $this->foto; }
    public function getProgramaAcademico() { return $this->programa_academico; }

    // Setters
    public function setInstitucionEducativaId($institucion_educativa_id) { 
        $this->institucion_educativa_id = $institucion_educativa_id; 
    }
    public function setFoto($foto) { 
        $this->foto = $foto; 
    }
    public function setProgramaAcademico($programa_academico) {
        $this->programa_academico = $programa_academico;
    }

    // Métodos existentes para obtener objetos relacionados
    public function getNombreEstudiante() {
        return new Usuario('id', $this->id_usuario_estudiante);
    }

    public function getPeriodoAcademico() {
        return new PeriodoAcademico('id', $this->id_periodo_academico);
    }

    public function getNombreTipoActividad() {
        return new TipoActividad('id', $this->id_tipo_actividad);
    }

    public function getNombreAsignatura() {
        return new Asignatura('id', $this->id_asignatura);
    }

    public function getNombreInstitucion() {
        if ($this->institucion_educativa_id) {
            $query = "SELECT nombre FROM institucion_educativa WHERE id='{$this->institucion_educativa_id}'";
            $result = ConectorBD::ejecutarQuery($query);
            if ($result && count($result) > 0) {
                return $result[0]['nombre'];
            }
        }
        return 'No asignada';
    }

    public function __toString() {
        return $this->id_tipo_actividad;
    }

    public static function getLista($filtro, $suma) {
        $cadenaSQL = "SELECT n.id, n.id_usuario_estudiante, n.id_periodo_academico, n.id_asignatura, n.id_tipo_actividad, n.nota, n.fecha_creacion, n.fecha_modificacion,
                            u.identificacion as iden_estudiante, u.nombres, u.apellidos, u.estado, u.foto, u.programa_academico,
                            pa.inicio_periodo, pa.finalizacion_periodo, pa.nombre as nombre_periodo, pa.id_anio_escolar,
                            a.nombre_asignatura,
                            ta.nombre_actividad,
                            ge.id_grupo, g.nombre_grupo, g.id_grado, gd.nombre_grado,
                            u.institucion_educativa_id, ie.nombre as nombre_institucion
                            FROM nota n
                            JOIN usuario u ON n.id_usuario_estudiante = u.id 
                            JOIN periodo_academico pa ON n.id_periodo_academico = pa.id
                            JOIN asignatura a ON n.id_asignatura = a.id
                            JOIN tipo_actividad ta ON n.id_tipo_actividad = ta.id 
                            JOIN grupo_estudiante ge ON u.id = ge.id_usuario_estudiante 
                            JOIN grupo g ON ge.id_grupo = g.id
                            JOIN grado gd ON g.id_grado = gd.id
                            JOIN institucion_educativa ie ON u.institucion_educativa_id = ie.id $filtro";

        $cadenaSQLSuma = "SELECT n.id, n.id_usuario_estudiante, n.id_periodo_academico, n.id_asignatura, n.id_tipo_actividad, SUM(n.nota) as nota, n.fecha_creacion, n.fecha_modificacion,
                            u.identificacion as iden_estudiante, u.nombres, u.apellidos, u.estado, u.foto, u.programa_academico,
                            pa.inicio_periodo, pa.finalizacion_periodo, pa.nombre as nombre_periodo, pa.id_anio_escolar,
                            a.nombre_asignatura,
                            ta.nombre_actividad,
                            ge.id_grupo, g.nombre_grupo, g.id_grado, gd.nombre_grado,
                            u.institucion_educativa_id, ie.nombre as nombre_institucion
                            FROM nota n
                            JOIN usuario u ON n.id_usuario_estudiante = u.id 
                            JOIN periodo_academico pa ON n.id_periodo_academico = pa.id
                            JOIN asignatura a ON n.id_asignatura = a.id
                            JOIN tipo_actividad ta ON n.id_tipo_actividad = ta.id 
                            JOIN grupo_estudiante ge ON u.id = ge.id_usuario_estudiante 
                            JOIN grupo g ON ge.id_grupo = g.id
                            JOIN grado gd ON g.id_grado = gd.id
                            JOIN institucion_educativa ie ON u.institucion_educativa_id = ie.id $filtro";
        
        if ($suma) {
            return ConectorBD::ejecutarQuery($cadenaSQLSuma);
        } else {
            return ConectorBD::ejecutarQuery($cadenaSQL);
        }
    }

    public static function getListaEnObjetos($filtro, $suma) {
        $resultado = NotasConsulta::getLista($filtro, $suma);
        $lista = array();
        foreach ($resultado as $key) {
            $notas = new NotasConsulta($key, $suma);
            array_push($lista, $notas);
        }
        return $lista;
    }
}
