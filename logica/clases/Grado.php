<?php

class Grado
{
    protected $id;
    protected $nombreGrado;
    protected $id_institucion;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre_grado, id_institucion FROM grado WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }

            $this->id = $campo['id'];
            $this->nombreGrado = $campo['nombre_grado'];
            $this->id_institucion = $campo['id_institucion'];
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

    public function getNombreGrado()
    {
        return $this->nombreGrado;
    }
    
    public function getIdInstitucion()
    {
        return $this->id_institucion;
    }

    public function getNombreInstitucion()
    {
        return new InstitucionEducativa('id', $this->id_institucion);
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombreGrado($nombreGrado): void
    {
        $this->nombreGrado = $nombreGrado;
    }
    
    public function setIdInstitucion($id_institucion): void
    {
        $this->id_institucion = $id_institucion;
    }

    public function __toString()
    {
        return $this->nombreGrado;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO grado (nombre_grado, id_institucion) values ('$this->nombreGrado', '$this->id_institucion')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE grado SET nombre_grado='{$this->nombreGrado}', id_institucion='{$this->id_institucion}' WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM grado WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro = null, $orden = null) {
        $where = $filtro ? " WHERE $filtro" : "";
        $orderBy = $orden ? " ORDER BY $orden" : "";
        $cadenaSQL = "SELECT id, nombre FROM institucion_educativa$where$orderBy";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro = null, $orden = null) {
    $where = ($filtro != null && $filtro != '') ? " WHERE $filtro" : "";
    $orderBy = ($orden != null && $orden != '') ? " ORDER BY $orden" : "";
    
    $sql = "SELECT g.id, g.nombre_grado, g.id_institucion, 
           i.nombre as nombre_institucion 
           FROM grado g
           LEFT JOIN institucion_educativa i ON g.id_institucion = i.id
           $where $orderBy";
    
    $resultados = ConectorBD::ejecutarQuery($sql);
    $lista = [];
    
    if ($resultados) {
        foreach ($resultados as $item) {
            $lista[] = new Grado($item, null);
        }
    }
    
    return $lista;
}
}
