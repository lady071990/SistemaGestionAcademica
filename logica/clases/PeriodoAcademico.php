<?php

class PeriodoAcademico
{
    protected $id;
    protected $nombre;
    protected $inicioPeriodo;
    protected $finalizacionPeriodo;
    protected $id_anio_escolar;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre, inicio_periodo, finalizacion_periodo, id_anio_escolar FROM periodo_academico WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }

            $this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->inicioPeriodo = $campo['inicio_periodo'];
            $this->finalizacionPeriodo = $campo['finalizacion_periodo'];
            $this->id_anio_escolar = $campo['id_anio_escolar'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getInicioPeriodo()
    {
        return $this->inicioPeriodo;
    }

    public function getFinalizacionPeriodo()
    {
        return $this->finalizacionPeriodo;
    }

    public function getIdAnioEscolar()
    {
        return $this->id_anio_escolar;
    }

    public function getAnioEscolar()
    {
        return new AnioEscolar('id', $this->id_anio_escolar);
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setInicioPeriodo($inicioPeriodo): void
    {
        $this->inicioPeriodo = $inicioPeriodo;
    }

    public function setFinalizacionPeriodo($finalizacionPeriodo): void
    {
        $this->finalizacionPeriodo = $finalizacionPeriodo;
    }

    public function setIdAnioEscolar($id_anio_escolar): void
    {
        $this->id_anio_escolar = $id_anio_escolar;
    }

    public function __toString()
    {
        //return Generalidades::convertDate($this->inicioPeriodo, false) . ' - ' . Generalidades::convertDate($this->finalizacionPeriodo, false);
        return $this->nombre;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO periodo_academico (nombre, inicio_periodo, finalizacion_periodo, id_anio_escolar) values ('$this->nombre', '$this->inicioPeriodo', '$this->finalizacionPeriodo', '$this->id_anio_escolar')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE periodo_academico SET nombre='{$this->nombre}', inicio_periodo='{$this->inicioPeriodo}', finalizacion_periodo='{$this->finalizacionPeriodo}' , id_anio_escolar='{$this->id_anio_escolar}' WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM periodo_academico WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, nombre, inicio_periodo, finalizacion_periodo, id_anio_escolar FROM periodo_academico $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = PeriodoAcademico::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $periodo = new PeriodoAcademico($resultado[$i], null);
            $lista[$i] = $periodo;
        }
        return $lista;
    }
}
