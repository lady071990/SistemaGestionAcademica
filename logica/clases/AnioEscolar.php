<?php

class AnioEscolar
{
    protected $id;
    protected $inicio;
    protected $fin;
    protected $id_institucion;
    protected $estado;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, inicio, fin, id_institucion, estado FROM anio_escolar WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
            $this->id = $campo['id'];
            $this->inicio = $campo['inicio'];
            $this->fin = $campo['fin'];
            $this->id_institucion = $campo['id_institucion'];
            $this->estado = $campo['estado'];
        }
    }
    
    public function getNombreInstitucionTexto() {
        $institucion = $this->getNombreInstitucion();
        return method_exists($institucion, 'getNombre') ? $institucion->getNombre() : 'Institución no disponible';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function getFin()
    {
        return $this->fin;
    }

    public function getIdInstitucion()
    {
        return $this->id_institucion;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getNombreInstitucion()
    {
        return new InstitucionEducativa('id', $this->id_institucion);
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setInicio($inicio): void
    {
        $this->inicio = $inicio;
    }

    public function setFin($fin): void
    {
        $this->fin = $fin;
    }

    public function setIdInstitucion($id_institucion): void
    {
        $this->id_institucion = $id_institucion;
    }

    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }

    public function __toString()
    {
        return Generalidades::convertDate($this->inicio, false)  . ' - ' . Generalidades::convertDate($this->fin, false);
    }

    public function guardar()
    {
        if ($this->estado == 1) {
            $cadenaSQL1 = "UPDATE anio_escolar SET estado='2'";
            ConectorBD::ejecutarQuery($cadenaSQL1);
        }
        $cadenaSQL = "INSERT INTO anio_escolar (inicio, fin, id_institucion, estado) values ('$this->inicio', '$this->fin', '$this->id_institucion', '$this->estado')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        if ($this->estado == 1) {
            $cadenaSQL1 = "UPDATE anio_escolar SET estado='2'";
            ConectorBD::ejecutarQuery($cadenaSQL1);
        }
        $cadenaSQL = "UPDATE anio_escolar SET inicio='{$this->inicio}', fin='{$this->fin}', id_institucion='{$this->id_institucion}', estado='{$this->estado}' WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM anio_escolar WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, inicio, fin, id_institucion, estado FROM anio_escolar $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = AnioEscolar::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $anioEscolar = new AnioEscolar($resultado[$i], null);
            $lista[$i] = $anioEscolar;
        }
        return $lista;
    }
}
