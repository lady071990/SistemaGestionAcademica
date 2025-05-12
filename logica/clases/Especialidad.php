<?php

class Especialidad {
    protected $id;
    protected $nombre;
    protected $orden;
    protected $duracionDias;
    
    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre, orden, duracionDias FROM usuario WHERE $campo='$valor'";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
    
            $this->id = $campo['id'] ?? null;
            $this->nombre = $campo['nombre'];
            $this->orden = $campo['orden'];
            $this->duracionDias = $campo['duracion_dias'] ?? 30;
        }
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getOrden() { return $this->orden; }
    public function getDuracionDias() { return $this->duracionDias; }
    
    public function setId($id): void { $this->id = $id; }
    public function setNombre($nombre): void { $this->nombre = $nombre; }
    public function setOrden($orden): void { $this->orden = $orden; }
    public function setDuracionDias($duracionDias): void { $this->duracionDias = $duracionDias; }

    public function __toString()
    {
        return $this->nombre;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO especialidades (nombre, orden, duracionDias) values ('$this->nombre','$this->orden', '$this->duracionDias')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE especialidades SET nombre='{$this->nombre}', orden='{$this->orden}', duracionDias='{$this->duracionDias}' WHERE id='{$ID}'";
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
        $cadenaSQL = "SELECT id, nombre, orden, duracionDias FROM especialidades $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Especialidad::getLista($filtro, $orden);
        $lista = array();
        foreach ($resultado as $key) {
            $rol = new Especialidad($key, null);
            array_push($lista, $rol);
        }
        return $lista;
    }
}
