<?php

class GrupoEstudiante
{
    protected $id;
    protected $id_usuario_estudiante;
    protected $id_grupo;
    protected $nombre_grado;
    protected $id_anio_escolar;
    protected $id_grado;
    protected $iden_estudiante;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT ge.id, ge.id_usuario_estudiante, ge.id_grupo, ge.id_anio_escolar, 
                            u.id as id_usuario, u.identificacion as iden_estudiante, u.nombres, u.apellidos, 
                            gd.nombre_grado, 
                            g.id_grado as id_grado, g.nombre_grupo,
                            us.identificacion,us.nombres, us.apellidos 
                            FROM grupo_estudiante ge
                            JOIN usuario u ON ge.id_usuario_estudiante = u.id 
                            JOIN grupo g ON ge.id_grupo = g.id 
                            JOIN grado gd ON g.id_grado = gd.id 
                            JOIN asignacion_docente ad ON g.id = ad.id_grupo  
                            JOIN usuario us ON ad.id_usuario_docente = us.id
                            WHERE $campo=$valor 
                            GROUP BY u.identificacion";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }

            $this->id = $campo['id'];
            $this->id_usuario_estudiante = $campo['id_usuario_estudiante'];
            $this->id_grupo = $campo['id_grupo'];
            $this->id_anio_escolar = $campo['id_anio_escolar'];
            $this->nombre_grado = $campo['nombre_grado'];
            $this->id_grado = $campo['id_grado'];
            $this->iden_estudiante = $campo['iden_estudiante'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdUsuarioEstudiante()
    {
        return $this->id_usuario_estudiante;
    }

    public function getIdentificacionEstudiante()
    {
        return $this->iden_estudiante;
    }

    public function getIdGrupo()
    {
        return $this->id_grupo;
    }

    public function getIdGrado()
    {
        return $this->id_grado;
    }

    public function getNombreGrado()
    {
        return $this->nombre_grado;
    }

    public function getIdAnioEscolar()
    {
        return $this->id_anio_escolar;
    }

    public function getNombreUsuarioEstudiante()
    {
        return new Usuario('id', $this->id_usuario_estudiante);
    }

    public function getNombreGrupo()
    {
        return new Grupo('id', $this->id_grupo);
    }

    public function getNombreAnioEscolar()
    {
        return new AnioEscolar('id', $this->id_anio_escolar);
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setIdUsuarioEstudiante($id_usuario_estudiante): void
    {
        $this->id_usuario_estudiante = $id_usuario_estudiante;
    }

    public function setIdGrupo($id_grupo): void
    {
        $this->id_grupo = $id_grupo;
    }

    public function setIdAnioEscolar($id_anio_escolar): void
    {
        $this->id_anio_escolar = $id_anio_escolar;
    }

    public function __toString()
    {
        return $this->id_usuario_estudiante;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO grupo_estudiante (id_usuario_estudiante, id_grupo, id_anio_escolar) values ('$this->id_usuario_estudiante', '$this->id_grupo', '$this->id_anio_escolar')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE grupo_estudiante SET id_usuario_estudiante='{$this->id_usuario_estudiante}', id_grupo='{$this->id_grupo}', id_anio_escolar='{$this->id_anio_escolar}' WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM grupo_estudiante WHERE grupo_estudiante.id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT ge.id, ge.id_usuario_estudiante, ge.id_grupo, ge.id_anio_escolar, 
                        u.id as id_usuario, u.identificacion as iden_estudiante, u.nombres, u.apellidos, 
                        gd.nombre_grado, 
                        g.id_grado as id_grado, g.nombre_grupo,
                        us.identificacion,us.nombres, us.apellidos 
                        FROM grupo_estudiante ge
                        JOIN usuario u ON ge.id_usuario_estudiante = u.id 
                        JOIN grupo g ON ge.id_grupo = g.id 
                        JOIN grado gd ON g.id_grado = gd.id 
                        JOIN asignacion_docente ad ON g.id = ad.id_grupo  
                        JOIN usuario us ON ad.id_usuario_docente = us.id
                        $filtro 
                        GROUP BY u.identificacion $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = GrupoEstudiante::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $grupo_estudiante = new GrupoEstudiante($resultado[$i], null);
            $lista[$i] = $grupo_estudiante;
        }
        return $lista;
    }
}
