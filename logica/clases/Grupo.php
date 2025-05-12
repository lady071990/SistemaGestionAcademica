<?php

class Grupo
{
    protected $id;
    protected $nombreGrupo;
    protected $id_grado;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre_grupo, id_grado FROM grupo WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }

            $this->id = $campo['id'];
            $this->nombreGrupo = $campo['nombre_grupo'];
            $this->id_grado = $campo['id_grado'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreGrupo() 
    {
        return $this->nombreGrupo;
    }
   
    public function getIdGrado() 
    {
        return $this->id_grado;
    }

    public function getNombreGrado()
    {
        return new Grado('id', $this->id_grado);
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombreGrupo($nombreGrupo): void 
    {
        $this->nombreGrupo = $nombreGrupo;
    }
    
    public function setIdGrado($id_grado): void 
    {
        $this->id_grado = $id_grado;
    }
    
    public function __toString()
    {
        return $this->nombreGrupo;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO grupo (nombre_grupo, id_grado) values ('$this->nombreGrupo', '$this->id_grado')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE grupo SET nombre_grupo='{$this->nombreGrupo}', id_grado='{$this->id_grado}' WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM grupo WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, nombre_grupo, id_grado FROM grupo $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Grupo::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $grupo = new Grupo($resultado[$i], null);
            $lista[$i] = $grupo;
        }
        return $lista;
    }
}
