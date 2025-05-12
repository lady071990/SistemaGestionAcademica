<?php

class TipoActividad
{
    protected $id;
    protected $nombre_actividad;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre_actividad FROM tipo_actividad WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }

            $this->id = $campo['id'];
            $this->nombre_actividad = $campo['nombre_actividad'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreActividad()
    {
        return $this->nombre_actividad;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombreActividad($nombre_actividad): void
    {
        $this->nombre_actividad = $nombre_actividad;
    }

    public function __toString()
    {
        return $this->nombre_actividad;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO tipo_actividad (nombre_actividad) VALUES ('$this->nombre_actividad')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE tipo_actividad SET nombre_actividad='{$this->nombre_actividad}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM tipo_actividad WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, nombre_actividad FROM tipo_actividad $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = TipoActividad::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $tipo_actividad = new TipoActividad($resultado[$i], null);
            $lista[$i] = $tipo_actividad;
        }
        return $lista;
    }
}
