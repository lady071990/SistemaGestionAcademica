<?php

class Permiso
{
    protected $id;
    protected $id_rol;
    protected $id_menu;
    protected $estado;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, id_rol, id_menu, estado FROM permisos WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
            $this->id = $campo['id'];
            $this->id_rol = $campo['id_rol'];
            $this->id_menu = $campo['id_menu'];
            $this->estado = $campo['estado'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdRol()
    {
        return $this->id_rol;
    }

    public function getIdMenu()
    {
        return $this->id_menu;
    }
    
    public function getEstado()
    {
        return $this->estado;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setIdRol($id_rol): void
    {
        $this->id_rol = $id_rol;
    }

    public function setIdMenu($id_menu): void
    {
        $this->id_menu = $id_menu;
    }
    
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }

    public function __toString()
    {
        return $this->id_rol;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO permisos (id_rol, id_menu, estado) values ('$this->nombre','$this->valor')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE permisos SET id_rol='{$this->id_rol}', id_menu='{$this->id_menu}', estado ='{$this->estado}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM permisos WHERE id='{$this->id}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, id_rol, id_menu, estado FROM permisos $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Permiso::getLista($filtro, $orden);
        $lista = array();
        foreach ($resultado as $key) {
            $permisos = new Permiso($key, null);
            array_push($lista, $permisos);
        }
        return $lista;
    }
}
