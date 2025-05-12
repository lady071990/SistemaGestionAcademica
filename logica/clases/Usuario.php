<?php

class Usuario
{
    protected $id;
    protected $identificacion;
    protected $nombres;
    protected $apellidos;
    protected $telefono;
    protected $email;
    protected $direccion;
    protected $clave;
    protected $rol_id;
    protected $institucion_educativa_id;
    protected $estado;
    protected $hoja_vida;
    protected $documentos;
    protected $foto;

    public function __construct($campo, $valor) {
    if ($campo != null) {
        if (!is_array($campo)) {
            $cadenaSQL = "SELECT id, identificacion, nombres, apellidos, telefono, email, direccion, clave, rol_id, institucion_educativa_id, estado, hoja_vida, documentos, foto FROM usuario WHERE $campo='$valor'";
            $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
        }

            $this->id = $campo['id'];
            $this->identificacion = $campo['identificacion'];
            $this->nombres = $campo['nombres'];
            $this->apellidos = $campo['apellidos'];
            $this->telefono = $campo['telefono'];
            $this->email = $campo['email'];
            $this->direccion = $campo['direccion'];
            $this->clave = $campo['clave'];
            $this->rol_id = $campo['rol_id'];
            $this->institucion_educativa_id = $campo['institucion_educativa_id'];
            $this->estado = $campo['estado'];
            $this->hoja_vida  = isset($campo['hoja_vida']) ? $campo['hoja_vida'] : null;
            $this->documentos = isset($campo['documentos']) ? $campo['documentos'] : null;
            $this->foto = isset($campo['foto']) ? $campo['foto'] : 'foto1.jpeg'; // Valor por defecto
        }
    }

    public function getTelefono() { return $this->telefono; }
    public function getEmail() { return $this->email; }
    public function getDireccion() { return $this->direccion; }
    public function getEstado() { return $this->estado; }
    public function getHojaVida() { return $this->hoja_vida; }
    public function getDocumentos() { return $this->documentos; }
    public function getFoto() { return $this->foto; }

    public function setTelefono($telefono): void { $this->telefono = $telefono; }
    public function setEmail($email): void { $this->email = $email; }
    public function setDireccion($direccion): void { $this->direccion = $direccion; }
    public function setEstado($estado): void { $this->estado = $estado; }
    public function setHojaVida($hoja_vida): void { $this->hoja_vida = $hoja_vida; }
    public function setDocumentos($documentos): void { $this->documentos = $documentos; }
    public function setFoto($foto): void { $this->foto = $foto; }

    public function getIdentificacion() { return $this->identificacion; }
    public function getId() { return $this->id; }
    public function getNombres() { return $this->nombres; }
    public function getApellidos() { return $this->apellidos; }
    public function getRolId() { return $this->rol_id; }
    public function getInstitucion_educativa_id() {return $this->institucion_educativa_id;}
    public function getClave() { return $this->clave; }

    public function setIdentificacion($identificacion): void { $this->identificacion = $identificacion; }
    public function setId($id): void { $this->id = $id; }
    public function setNombres($nombres): void { $this->nombres = $nombres; }
    public function setApellidos($apellidos): void { $this->apellidos = $apellidos; }
    public function setRolId($rol_id): void { $this->rol_id = $rol_id; }
    public function setInstitucion_educativa_id($institucion_educativa_id): void {$this->institucion_educativa_id = $institucion_educativa_id;}
    public function setClave($clave): void { $this->clave = $clave; }

    public function getRolNombre() {
        return new Rol('id', $this->rol_id);
    }

    public function __toString() {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function guardar()
    {
        $clave = md5($this->identificacion);
        $cadenaSQL = "INSERT INTO usuario 
        (identificacion, nombres, apellidos, telefono, email, direccion, clave, rol_id, institucion_educativa_id, estado, hoja_vida, documentos, foto) 
        VALUES 
        ('$this->identificacion', '$this->nombres', '$this->apellidos', '$this->telefono', '$this->email', '$this->direccion', '$clave', '$this->rol_id', '$this->institucion_educativa_id', '$this->estado', '$this->hoja_vida', '$this->documentos', '$this->foto')";
        
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $clave = md5($this->clave);
        $cadenaSQL = $this->clave ?
            "UPDATE usuario SET identificacion='{$this->identificacion}', nombres='{$this->nombres}', apellidos='{$this->apellidos}', telefono='{$this->telefono}', email='{$this->email}', direccion='{$this->direccion}', clave='{$clave}', rol_id='{$this->rol_id}', institucion_educativa_id='{$this->institucion_educativa_id}', estado='{$this->estado}', hoja_vida='{$this->hoja_vida}', documentos='{$this->documentos}', foto='{$this->foto}' WHERE id='{$ID}'"
            :
            "UPDATE usuario SET identificacion='{$this->identificacion}', nombres='{$this->nombres}', apellidos='{$this->apellidos}', telefono='{$this->telefono}', email='{$this->email}', direccion='{$this->direccion}', rol_id='{$this->rol_id}', institucion_educativa_id='{$this->institucion_educativa_id}', estado='{$this->estado}', hoja_vida='{$this->hoja_vida}', documentos='{$this->documentos}', foto='{$this->foto}' WHERE id='{$ID}'";

        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM usuario WHERE id='{$this->id}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, identificacion, nombres, apellidos, telefono, email, direccion, clave, rol_id, institucion_educativa_id, estado FROM usuario $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
    
    // Se Agrega método para obtener el nombre de la institución
    public function getInstitucionEducativaNombre() {
        if ($this->institucion_educativa_id) {
            $query = "SELECT nombre FROM institucion_educativa WHERE id='{$this->institucion_educativa_id}'";
            $result = ConectorBD::ejecutarQuery($query);
            if ($result && count($result) > 0) {
                return $result[0]['nombre'];
            }
        }
        return 'No asignada';
    }
    
    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Usuario::getLista($filtro, $orden);
        $lista = array();
        foreach ($resultado as $key) {
            $usuario = new Usuario($key, null);
            array_push($lista, $usuario);
        }
        return $lista;
    }

    public static function validar($usuario, $clave)
    {
        $resultado = Usuario::getListaEnObjetos("identificacion='$usuario' and clave=md5('$clave')", null);
        $usuario = null;
        if (count($resultado) > 0) $usuario = $resultado[0];
        return $usuario;
    }
}
