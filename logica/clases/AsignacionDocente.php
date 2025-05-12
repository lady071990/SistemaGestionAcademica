<?php

class AsignacionDocente
{
    protected $id;
    protected $id_usuario_docente;
    protected $id_anio_escolar;
    protected $id_asignatura;
    protected $id_grupo;
    protected $link_clase_virtual;
    protected $intensidad_horaria;
    protected $id_gd;
    protected $nombre_grado;
    protected $iden_docente;

    public function __construct($campo, $valor)
    {
    if ($campo != null) {
        if (!is_array($campo)) {
            $cadenaSQL = "SELECT ad.id, ad.id_usuario_docente, ad.id_anio_escolar, ad.id_asignatura, ad.id_grupo, ad.link_clase_virtual, ad.intensidad_horaria, 
                        u.identificacion as iden_docente, u.nombres, u.apellidos, 
                        gd.id as id_gd, gd.nombre_grado, 
                        gr.nombre_grupo, 
                        a.nombre_asignatura 
                        FROM asignatura a 
                        JOIN asignacion_docente ad ON a.id = ad.id_asignatura 
                        JOIN usuario u ON ad.id_usuario_docente = u.id 
                        JOIN grupo gr ON ad.id_grupo = gr.id 
                        JOIN grado gd ON gr.id_grado = gd.id WHERE $campo=$valor";
            $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
            $campo = isset($resultado[0]) ? $resultado[0] : array();
        }
        $this->id = isset($campo['id']) ? $campo['id'] : null;
        $this->id_usuario_docente = isset($campo['id_usuario_docente']) ? $campo['id_usuario_docente'] : null;
        $this->id_anio_escolar = isset($campo['id_anio_escolar']) ? $campo['id_anio_escolar'] : null;
        $this->id_asignatura = isset($campo['id_asignatura']) ? $campo['id_asignatura'] : null;
        $this->id_grupo = isset($campo['id_grupo']) ? $campo['id_grupo'] : null;
        $this->link_clase_virtual = isset($campo['link_clase_virtual']) ? $campo['link_clase_virtual'] : null;
        $this->intensidad_horaria = isset($campo['intensidad_horaria']) ? $campo['intensidad_horaria'] : null;
        $this->id_gd = isset($campo['id_gd']) ? $campo['id_gd'] : null;
        $this->nombre_grado = isset($campo['nombre_grado']) ? $campo['nombre_grado'] : null;
        $this->iden_docente = isset($campo['iden_docente']) ? $campo['iden_docente'] : null;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdUsuarioDocente()
    {
        return $this->id_usuario_docente;
    }

    public function getIdentificacionDocente()
    {
        return $this->iden_docente;
    }

    public function getIdAnioEscolar()
    {
        return $this->id_anio_escolar;
    }

    public function getIdAsignatura()
    {
        return $this->id_asignatura;
    }

    public function getIdGrado()
    {
        return $this->id_gd;
    }

    public function getNombreGrado()
    {
        return $this->nombre_grado;
    }

    public function getIdGrupo()
    {
        return $this->id_grupo;
    }

    public function getLinkClaseVirtual()
    {
        return $this->link_clase_virtual;
    }

    public function getIntensidadHoraria()
    {
        return $this->intensidad_horaria;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setIdUsuarioDocente($id_usuario_docente): void
    {
        $this->id_usuario_docente = $id_usuario_docente;
    }

    public function setIdAnioEscolar($id_anio_escolar): void
    {
        $this->id_anio_escolar = $id_anio_escolar;
    }

    public function setIdAsignatura($id_asignatura): void
    {
        $this->id_asignatura = $id_asignatura;
    }

    public function setIdGrupo($id_grupo): void
    {
        $this->id_grupo = $id_grupo;
    }

    public function setLinkClaseVirtual($link_clase_virtual): void
    {
        $this->link_clase_virtual = $link_clase_virtual;
    }

    public function setIntensidadHoraria($intensidad_horaria): void
    {
        $this->intensidad_horaria = $intensidad_horaria;
    }

    public function getNombreDocente()
    {
        return new Usuario('id', $this->id_usuario_docente);
    }

    public function getAnioEscolar()
    {
        return new AnioEscolar('id', $this->id_anio_escolar);
    }

    public function getNombreGrupo()
    {
        return new Grupo('id', $this->id_grupo);
    }

    public function getNombreAsignatura()
    {
        return new Asignatura('id', $this->id_asignatura);
    }

    public function __toString()
    {
        return $this->id_grupo;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO asignacion_docente (id_usuario_docente, id_anio_escolar, id_asignatura, id_grupo, link_clase_virtual, intensidad_horaria) VALUES ('$this->id_usuario_docente','$this->id_anio_escolar','$this->id_asignatura','$this->id_grupo','$this->link_clase_virtual','$this->intensidad_horaria')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE asignacion_docente SET id_usuario_docente='{$this->id_usuario_docente}', id_anio_escolar='{$this->id_anio_escolar}', id_asignatura='{$this->id_asignatura}', id_grupo='{$this->id_grupo}', link_clase_virtual='{$this->link_clase_virtual}', intensidad_horaria ='{$this->intensidad_horaria}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM asignacion_docente WHERE id='{$this->id}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT ad.id, ad.id_usuario_docente, ad.id_anio_escolar, ad.id_asignatura, ad.id_grupo, ad.link_clase_virtual, ad.intensidad_horaria, 
                    u.identificacion as iden_docente, u.nombres, u.apellidos, 
                    gd.id as id_gd, gd.nombre_grado, 
                    gr.nombre_grupo, 
                    a.nombre_asignatura 
                    FROM asignatura a 
                    JOIN asignacion_docente ad ON a.id = ad.id_asignatura 
                    JOIN usuario u ON ad.id_usuario_docente = u.id 
                    JOIN grupo gr ON ad.id_grupo = gr.id 
                    JOIN grado gd ON gr.id_grado = gd.id $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = AsignacionDocente::getLista($filtro, $orden);
        $lista = array();
        foreach ($resultado as $key) {
            $asignacion = new AsignacionDocente($key, null);
            array_push($lista, $asignacion);
        }
        return $lista;
    }
}
