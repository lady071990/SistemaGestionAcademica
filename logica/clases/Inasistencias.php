<?php

class Inasistencias
{
    protected $id;
    protected $cantidad;
    protected $justificacion;
    protected $fecha_creacion;
    protected $fecha_modificacion;
    protected $id_asignatura;
    protected $registrado_a_estudiante;
    protected $creado_por_docente;

    public function __construct($campo, $valor, $all)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT i.id, SUM(i.cantidad) as cantidad, i.justificacion, i.fecha_creacion, i.fecha_modificacion, i.id_asignatura, i.registrado_a_estudiante, i.creado_por_docente, 
                            a.nombre_asignatura, 
                            u.identificacion, u.nombres, u.apellidos, 
                            us.identificacion, us.nombres, us.apellidos 
                            FROM inasistencias i 
                            JOIN asignatura a ON i.id_asignatura = a.id 
                            JOIN usuario u ON i.registrado_a_estudiante = u.id 
                            JOIN usuario us ON i.creado_por_docente = us.id 
                            WHERE $campo = $valor 
                            GROUP BY i.id_asignatura, i.registrado_a_estudiante 
                            ORDER BY i.id_asignatura, i.fecha_creacion";

                $cadenaSQLAll = "SELECT i.id, i.cantidad, i.justificacion, i.fecha_creacion, i.fecha_modificacion, i.id_asignatura, i.registrado_a_estudiante, i.creado_por_docente, 
                    a.nombre_asignatura, 
                    u.identificacion, u.nombres, u.apellidos, 
                    us.identificacion, us.nombres, us.apellidos 
                    FROM inasistencias i 
                    JOIN asignatura a ON i.id_asignatura = a.id 
                    JOIN usuario u ON i.registrado_a_estudiante = u.id 
                    JOIN usuario us ON i.creado_por_docente = us.id WHERE $campo = $valor";

                $cadenaSQLSum = "SELECT i.id, SUM(i.cantidad) as cantidad, i.justificacion, i.fecha_creacion, i.fecha_modificacion, i.id_asignatura, i.registrado_a_estudiante, i.creado_por_docente FROM inasistencias i WHERE $campo = $valor";

                if ($all == 'total') {
                    $campo = ConectorBD::ejecutarQuery($cadenaSQLAll)[0];
                } elseif ($all == 'suma') {
                    $campo = ConectorBD::ejecutarQuery($cadenaSQLSum)[0];
                } else {
                    $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                }
            }
            $this->id = $campo['id'];
            $this->cantidad = $campo['cantidad'];
            $this->justificacion = $campo['justificacion'];
            $this->fecha_creacion = $campo['fecha_creacion'];
            $this->fecha_modificacion = $campo['fecha_modificacion'];
            $this->id_asignatura = $campo['id_asignatura'];
            $this->registrado_a_estudiante = $campo['registrado_a_estudiante'];
            $this->creado_por_docente = $campo['creado_por_docente'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function getJustificacion()
    {
        return $this->justificacion;
    }

    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    public function getFechaModificacion()
    {
        return $this->fecha_modificacion;
    }

    public function getIdAsignatura()
    {
        return $this->id_asignatura;
    }

    public function getRegistradoAEstudiante()
    {
        return $this->registrado_a_estudiante;
    }

    public function getCreadoPorDocente()
    {
        return $this->creado_por_docente;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function setJustificacion($justificacion): void
    {
        $this->justificacion = $justificacion;
    }

    public function setFechaCreacion($fecha_creacion): void
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function setFechaModificacion($fecha_modificacion): void
    {
        $this->fecha_modificacion = $fecha_modificacion;
    }

    public function setIdAsignatura($id_asignatura): void
    {
        $this->id_asignatura = $id_asignatura;
    }

    public function setRegistradoAEstudiante($registrado_a_estudiante): void
    {
        $this->registrado_a_estudiante = $registrado_a_estudiante;
    }

    public function setCreadoPorDocente($creado_por_docente): void
    {
        $this->creado_por_docente = $creado_por_docente;
    }

    public function __toString()
    {
        return $this->cantidad;
    }

    public function getNombreAsignatura()
    {
        return new Asignatura('id', $this->id_asignatura);
    }

    public function getNombreEstudiante()
    {
        return new Usuario('id', $this->registrado_a_estudiante);
    }

    public function getNombreDocente()
    {
        return new Usuario('id', $this->creado_por_docente);
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO inasistencias (cantidad, justificacion, id_asignatura, registrado_a_estudiante, creado_por_docente) values ('$this->cantidad','$this->justificacion', '$this->id_asignatura', '$this->registrado_a_estudiante', '$this->creado_por_docente')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE inasistencias SET cantidad='{$this->cantidad}', justificacion='{$this->justificacion}', id_asignatura='{$this->id_asignatura}', registrado_a_estudiante='{$this->registrado_a_estudiante}', creado_por_docente='{$this->creado_por_docente}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM inasistencias WHERE id='{$this->id}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden, $all)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";

        $cadenaSQLAll = "SELECT i.id, i.cantidad, i.justificacion, i.fecha_creacion, i.fecha_modificacion, i.id_asignatura, i.registrado_a_estudiante, i.creado_por_docente, 
                    a.nombre_asignatura, 
                    u.identificacion, u.nombres, u.apellidos, 
                    us.identificacion, us.nombres, us.apellidos 
                    FROM inasistencias i 
                    JOIN asignatura a ON i.id_asignatura = a.id 
                    JOIN usuario u ON i.registrado_a_estudiante = u.id 
                    JOIN usuario us ON i.creado_por_docente = us.id $filtro $orden";

        $cadenaSQL = "SELECT i.id, SUM(i.cantidad) as cantidad, i.justificacion, i.fecha_creacion, i.fecha_modificacion, i.id_asignatura, i.registrado_a_estudiante, i.creado_por_docente, 
                    a.nombre_asignatura, 
                    u.identificacion, u.nombres, u.apellidos, 
                    us.identificacion, us.nombres, us.apellidos 
                    FROM inasistencias i 
                    JOIN asignatura a ON i.id_asignatura = a.id 
                    JOIN usuario u ON i.registrado_a_estudiante = u.id 
                    JOIN usuario us ON i.creado_por_docente = us.id 
                    $filtro 
                    GROUP BY i.id_asignatura, i.registrado_a_estudiante
                    $orden";

        $cadenaSQLSum = "SELECT i.id, SUM(i.cantidad) as cantidad, i.justificacion, i.fecha_creacion, i.fecha_modificacion, i.id_asignatura, i.registrado_a_estudiante, i.creado_por_docente FROM inasistencias i $filtro $orden";

        if ($all == 'total') {
            return ConectorBD::ejecutarQuery($cadenaSQLAll);
        } elseif ($all == 'suma') {
            return ConectorBD::ejecutarQuery($cadenaSQLSum);
        } else {
            return ConectorBD::ejecutarQuery($cadenaSQL);
        }
    }

    public static function getListaEnObjetos($filtro, $orden, $all)
    {
        $resultado = Inasistencias::getLista($filtro, $orden, $all);
        $lista = array();
        foreach ($resultado as $key) {
            $inasistencia = new Inasistencias($key, null, $all);
            array_push($lista, $inasistencia);
        }
        return $lista;
    }
}
