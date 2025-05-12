<?php

class Menu
{
    protected $id;
    protected $nombre;
    protected $ruta;
    protected $tipo;
    protected $es_hijo;
    protected $posicion;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre, ruta, tipo, es_hijo, posicion FROM menu WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
            $this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->ruta = $campo['ruta'];
            $this->tipo = $campo['tipo'];
            $this->es_hijo = $campo['es_hijo'];
            $this->posicion = $campo['posicion'];
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

    public function getRuta()
    {
        return $this->ruta;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getEsHijo()
    {
        return $this->es_hijo;
    }

    public function getPosicion()
    {
        return $this->posicion;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setRuta($ruta): void
    {
        $this->ruta = $ruta;
    }

    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    public function setEsHijo($es_hijo): void
    {
        $this->es_hijo = $es_hijo;
    }

    public function setPosicion($posicion): void
    {
        $this->posicion = $posicion;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO menu (nombre, ruta, tipo, es_hijo, posicion) values ('$this->nombre','$this->ruta', '$this->tipo', '$this->es_hijo', '$this->posicion')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE menu SET nombre='{$this->nombre}', ruta='{$this->ruta}', tipo='{$this->tipo}', es_hijo='{$this->es_hijo}', posicion='{$this->posicion}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM menu WHERE id='{$this->id}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, nombre, ruta, tipo, es_hijo, posicion FROM menu $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Menu::getLista($filtro, $orden);
        $lista = array();
        foreach ($resultado as $key) {
            $menu = new Menu($key, null);
            array_push($lista, $menu);
        }
        return $lista;
    }
}
