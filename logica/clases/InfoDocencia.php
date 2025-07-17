<?php

class InfoDocencia
{
    protected $id;
    protected $nombre;
    protected $direccion;
    protected $telefono;
    protected $email;
    protected $nombreCoordinador;
    protected $paginaWeb;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nombre, direccion, telefono, email, nombre_coordinador, pagina_web FROM info_docencia WHERE $campo=$valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
            $this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->direccion = $campo['direccion'];
            $this->telefono = $campo['telefono'];
            $this->email = $campo['email'];
            $this->nombreCoordinador = $campo['nombre_coordinador'];
            $this->paginaWeb = $campo['pagina_web'];
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

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNombreCoordinador() 
    {
        return $this->nombreCoordinador;
    }

    
    public function getPaginaWeb()
    {
        return $this->paginaWeb;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setNombreCoordinador($nombreCoordinador): void 
    {
        $this->nombreCoordinador = $nombreCoordinador;
    }

    
    public function setPaginaWeb($paginaWeb): void
    {
        $this->paginaWeb = $paginaWeb;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO info_docencia (nombre, direccion, telefono, email, nombre_coordinador, pagina_web) VALUES ('$this->nombre','$this->direccion','$this->telefono','$this->email', '$this->nombreCoordinador', '$this->paginaWeb'))";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE info_docencia SET nombre='{$this->nombre}', direccion='{$this->direccion}', telefono='{$this->telefono}', email='{$this->email}', nombre_coordinador='{$this->nombreCoordinador}', pagina_web='{$this->paginaWeb}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, nombre, direccion, telefono, email, nombre_coordinador, pagina_web FROM info_docencia $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = InfoDocencia::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $institucion = new InfoDocencia($resultado[$i], null);
            $lista[$i] = $institucion;
        }
        return $lista;
    }
}