<?php

class Rol
{
    protected $id;
    protected $nombre;
    protected $valor;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre, valor FROM roles WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
            $this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->valor = $campo['valor'];
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

    public function getValor()
    {
        return $this->valor;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setValor($valor): void
    {
        $this->valor = $valor;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO roles (nombre, valor) values ('$this->nombre','$this->valor')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE roles SET nombre='{$this->nombre}', valor='{$this->valor}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM roles WHERE id='{$this->id}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, nombre, valor FROM roles $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Rol::getLista($filtro, $orden);
        $lista = array();
        foreach ($resultado as $key) {
            $rol = new Rol($key, null);
            array_push($lista, $rol);
        }
        return $lista;
    }
}
