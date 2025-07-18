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
    Protected $programa_academico;
    protected $tipo_vinculacion;
    protected $experiencia_laboral;
    protected $certificacion_postgrado;
    protected $fecha_certificacion;
    protected $perfil_profesional;

    public function __construct($campo, $valor) {
    if ($campo != null) {
        if (!is_array($campo)) {
            $cadenaSQL = "SELECT id, identificacion, nombres, apellidos, telefono, email, direccion, clave, rol_id, institucion_educativa_id, estado, hoja_vida, documentos, foto, programa_academico, tipo_vinculacion, experiencia_laboral, certificacion_postgrado, fecha_certificacion, perfil_profesional FROM usuario WHERE $campo='$valor'";
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
            $this->programa_academico = $campo['programa_academico'];
            $this->tipo_vinculacion = $campo['tipo_vinculacion'] ?? null;
            $this->experiencia_laboral = $campo['experiencia_laboral'] ?? null;
            $this->certificacion_postgrado = $campo['certificacion_postgrado'] ?? 0;
            $this->fecha_certificacion = $campo['fecha_certificacion'] ?? null;
            $this->perfil_profesional = $campo['perfil_profesional'] ?? null;
        }
    }

    public function getTelefono() { return $this->telefono; }
    public function getEmail() { return $this->email; }
    public function getDireccion() { return $this->direccion; }
    public function getEstado() { return $this->estado; }
    public function getHojaVida() { return $this->hoja_vida; }
    public function getDocumentos() { return $this->documentos; }
    public function getFoto() { return $this->foto; }
    public function getPrograma_academico() { return $this->programa_academico; }

 
    public function setTelefono($telefono): void { $this->telefono = $telefono; }
    public function setEmail($email): void { $this->email = $email; }
    public function setDireccion($direccion): void { $this->direccion = $direccion; }
    public function setEstado($estado): void { $this->estado = $estado; }
    public function setHojaVida($hoja_vida): void { $this->hoja_vida = $hoja_vida; }
    public function setDocumentos($documentos): void { $this->documentos = $documentos; }
    public function setFoto($foto): void { $this->foto = $foto; }
    public function setPrograma_academico($programa_academico): void { $this->programa_academico = $programa_academico; }

 
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
    
    public function setTipoVinculacion($valor) { $this->tipo_vinculacion = $valor; }
    public function setExperienciaLaboral($valor) { $this->experiencia_laboral = $valor; }
    public function setCertificacionPostgrado($valor) { $this->certificacion_postgrado = $valor; }
    public function setFechaCertificacion($valor) { $this->fecha_certificacion = $valor; }
    public function setPerfilProfesional($valor) { $this->perfil_profesional = $valor; }

    public function getTipoVinculacion() { return $this->tipo_vinculacion; }
    public function getExperienciaLaboral() { return $this->experiencia_laboral; }
    public function getCertificacionPostgrado() { return $this->certificacion_postgrado; }
    public function getFechaCertificacion() { return $this->fecha_certificacion; }
    public function getPerfilProfesional() { return $this->perfil_profesional; }

    public function getRolNombre() {
        return new Rol('id', $this->rol_id);
    }

    public function __toString() {
        return $this->nombres . ' ' . $this->apellidos;
    }
    
    public function esUniversidad() {
        return $this->rol_id == 7; // Rol 7 = Universidad
    }
    
    public function guardar()
    {
        $clave = md5($this->identificacion);
        $cadenaSQL = "INSERT INTO usuario 
        (identificacion, nombres, apellidos, telefono, email, direccion, clave, rol_id, institucion_educativa_id, estado, hoja_vida, documentos, foto, programa_academico, tipo_vinculacion, experiencia_laboral, certificacion_postgrado, fecha_certificacion, perfil_profesional) 
        VALUES 
        ('$this->identificacion', '$this->nombres', '$this->apellidos', '$this->telefono', '$this->email', '$this->direccion', '$clave', '$this->rol_id', '$this->institucion_educativa_id', '$this->estado', '$this->hoja_vida', '$this->documentos', '$this->foto', '$this->programa_academico', '$this->tipo_vinculacion', '$this->experiencia_laboral', '$this->certificacion_postgrado', '$this->fecha_certificacion', '$this->perfil_profesional')";
        
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $clave = md5($this->clave);
        $cadenaSQL = $this->clave ?
            "UPDATE usuario SET identificacion='{$this->identificacion}', nombres='{$this->nombres}', apellidos='{$this->apellidos}', telefono='{$this->telefono}', email='{$this->email}', direccion='{$this->direccion}', clave='{$clave}', rol_id='{$this->rol_id}', institucion_educativa_id='{$this->institucion_educativa_id}', estado='{$this->estado}', hoja_vida='{$this->hoja_vida}', documentos='{$this->documentos}', foto='{$this->foto}', programa_academico='{$this->programa_academico}', tipo_vinculacion='{$this->tipo_vinculacion}', experiencia_laboral='{$this->experiencia_laboral}',
            certificacion_postgrado='{$this->certificacion_postgrado}', fecha_certificacion='{$this->fecha_certificacion}', perfil_profesional='{$this->perfil_profesional}' WHERE id='{$ID}'"
            :
            "UPDATE usuario SET identificacion='{$this->identificacion}', nombres='{$this->nombres}', apellidos='{$this->apellidos}', telefono='{$this->telefono}', email='{$this->email}', direccion='{$this->direccion}', rol_id='{$this->rol_id}', institucion_educativa_id='{$this->institucion_educativa_id}', estado='{$this->estado}', hoja_vida='{$this->hoja_vida}', documentos='{$this->documentos}', foto='{$this->foto}', programa_academico='{$this->programa_academico}',                 tipo_vinculacion='{$this->tipo_vinculacion}',
            experiencia_laboral='{$this->experiencia_laboral}', certificacion_postgrado='{$this->certificacion_postgrado}', fecha_certificacion='{$this->fecha_certificacion}', perfil_profesional='{$this->perfil_profesional}' WHERE id='{$ID}'";

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
        $cadenaSQL = "SELECT id, identificacion, nombres, apellidos, telefono, email, direccion, clave, rol_id, institucion_educativa_id, estado, hoja_vida, documentos, foto, programa_academico, tipo_vinculacion, experiencia_laboral, certificacion_postgrado, fecha_certificacion, perfil_profesional FROM usuario $filtro $orden";
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
    
    public function getPerfilResumido() {
        return substr(strip_tags($this->getPerfilProfesional()), 0, 100) . '...';
    }

    public function certificacionValida() {
        if (!$this->getCertificacionPostgrado() || !$this->getFechaCertificacion()) return false;
        $fecha = new DateTime($this->getFechaCertificacion());
        $hoy = new DateTime();
        $diff = $hoy->diff($fecha);
        return $diff->y == 0 && $diff->m <= 6;
    }
    
}
