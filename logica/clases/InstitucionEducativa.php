<?php

class InstitucionEducativa
{
    protected $id;
    protected $nombre;
    protected $direccion;
    protected $telefono;
    protected $email;
    protected $nombreDirectora;
    protected $paginaWeb;
    protected $tipo;
    protected $programas;
    protected $especialidadesMedicas;


    public function __construct($campo, $valor)
    {
        if (!is_array($campo)) {
            $cadenaSQL = "SELECT id, nombre, direccion, telefono, email, nombre_directora, pagina_web, tipo, programas, especialidades_medicas FROM institucion_educativa WHERE $campo=$valor";
            $resultado = ConectorBD::ejecutarQuery($cadenaSQL);

            if ($resultado && count($resultado) > 0) {
                $campo = $resultado[0];
            } else {
                $campo = null;
            }
        }

        if (is_array($campo)) {
            $this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->direccion = $campo['direccion'];
            $this->telefono = $campo['telefono'];
            $this->email = $campo['email'];
            $this->nombreDirectora = $campo['nombre_directora'];
            $this->paginaWeb = $campo['pagina_web'];
            $this->tipo = $campo['tipo'] ?? 'Universidad';
            $this->programas = $campo['programas'];
            $this->especialidadesMedicas = $campo['especialidades_medicas'] ?? ''; 
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

    public function getNombreDirectora()
    {
        return $this->nombreDirectora;
    }

    public function getPaginaWeb()
    {
        return $this->paginaWeb;
    }
    public function getTipo() 
    { 
        return $this->tipo; 
    }
    
    public function setProgramas($programas): void {
    if (is_array($programas)) {
        $this->programas = json_encode($programas);
    } elseif (is_string($programas) && json_decode($programas) !== null) {
        // Ya está en formato JSON
        $this->programas = $programas;
    } else {
        // Cadena simple
        $this->programas = json_encode([['tipo' => $programas]]);
    }
    }

    public function getProgramas() {
        if (empty($this->programas)) {
            return [];
        }

        $programas = json_decode($this->programas, true);

        // Si es un array de arrays (formato correcto)
        if (is_array($programas) && isset($programas[0]['tipo'])) {
            return $programas;
        }

        // Si es un array simple o string
        return [['tipo' => is_array($programas) ? implode(', ', $programas) : $programas]];
    }
    
    public function getEspecialidadesMedicas() 
    { 
        return $this->especialidadesMedicas; 
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

    public function setNombreDirectora($nombreDirectora): void
    {
        $this->nombreDirectora = $nombreDirectora;
    }

    public function setPaginaWeb($paginaWeb): void
    {
        $this->paginaWeb = $paginaWeb;
    }
    
    public function setTipo($tipo): void 
    { 
        $this->tipo = $tipo; 
    }
    
    public function setEspecialidadesMedicas($especialidadesMedicas): void 
    { 
    $this->especialidadesMedicas = $especialidadesMedicas; // Quitar la conversión a 1/0
    }

    public function __toString()
    {
        return $this->nombre;
    }
    
    public function guardar()
    {
        $cadenaSQL = "INSERT INTO institucion_educativa (nombre, direccion, telefono, email, nombre_directora, pagina_web, tipo, programas, especialidades_medicas) VALUES ('$this->nombre','$this->direccion','$this->telefono','$this->email', '$this->nombreDirectora', '$this->paginaWeb', '$this->tipo', '$this->programas','$this->especialidadesMedicas')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE institucion_educativa SET nombre='{$this->nombre}', direccion='{$this->direccion}', telefono='{$this->telefono}', email='{$this->email}', nombre_directora='{$this->nombreDirectora}', pagina_web='{$this->paginaWeb}', tipo='{$this->tipo}', programas='{$this->programas}', especialidades_medicas='{$this->especialidadesMedicas}' WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }
    
    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM institucion_educativa WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }
    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, nombre, tipo, direccion, email, telefono, nombre_directora, pagina_web, programas, especialidades_medicas FROM institucion_educativa $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro = '', $orden = '', $usuario = null) {
        // Aplicar filtro adicional para usuarios universidad
        if ($usuario && $usuario->esUniversidad() && $usuario->getInstitucion_educativa_id()) {
            $filtroUniversidad = "id = '{$usuario->getInstitucion_educativa_id()}'";
            $filtro = $filtro ? "($filtro) AND ($filtroUniversidad)" : $filtroUniversidad;
        }

        $where = $filtro ? " WHERE $filtro" : '';
        $order = $orden ? " ORDER BY $orden" : '';
        
        $cadenaSQL = "SELECT * FROM institucion_educativa $where $order";
        $resultados = ConectorBD::ejecutarQuery($cadenaSQL);
        
        $instituciones = [];
        foreach ($resultados as $fila) {
            $instituciones[] = new InstitucionEducativa($fila, null);
        }
        
        return $instituciones;
    }
}