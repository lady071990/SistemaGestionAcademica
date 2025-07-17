<?php

class ObservacionBoletin
{
    protected $id;
    protected $id_usuario_estudiante;
    protected $id_anio_escolar;
    protected $observacion;
    protected $fecha_registro;

    public function __construct($campo = null, $valor = null)
{
    if ($campo != null && $valor != null) {
        $cadenaSQL = "SELECT id, id_usuario_estudiante, id_anio_escolar, observacion, fecha_registro FROM observaciones_boletin WHERE $campo='$valor'";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL);

        if ($resultado && count($resultado) > 0) {
            $campo = $resultado[0]; // ahora sí existe
            $this->id = $campo['id'];
            $this->id_usuario_estudiante = $campo['id_usuario_estudiante'];
            $this->id_anio_escolar = $campo['id_anio_escolar'];
            $this->observacion = $campo['observacion'];
            $this->fecha_registro = $campo['fecha_registro'];
        }
    }
}

    public function getId() {
        return $this->id;
    }

    public function getId_usuario_estudiante() {
        return $this->id_usuario_estudiante;
    }

    public function getId_anio_escolar() {
        return $this->id_anio_escolar;
    }

    public function getObservacion() {
    return $this->observacion;
    }

    public function getFecha_registro() {
        return $this->fecha_registro;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setId_usuario_estudiante($id_usuario_estudiante): void {
        $this->id_usuario_estudiante = $id_usuario_estudiante;
    }

    public function setId_anio_escolar($id_anio_escolar): void {
        $this->id_anio_escolar = $id_anio_escolar;
    }

    public function setObservacion($observacion): void {
        $this->observacion = $observacion;
    }

    public function setFecha_registro($fecha_registro): void {
        $this->fecha_registro = $fecha_registro;
    }

    public function __toString() { return $this->id_usuario_estudiante; }

    public function guardar()
    {
        $cadenaSQL = "INSERT INTO observaciones_boletin (id_usuario_estudiante, id_anio_escolar, observacion, fecha_registro)
                      VALUES ('$this->id_usuario_estudiante', '$this->id_anio_escolar', '$this->observacion', '$this->fecha_registro')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE observacion_boletin 
                      SET observacion = '$this->observacion' 
                      id_usuario_estudiante='{$this->id_usuario_estudiante}',  
                      id_anio_escolar='{$this->id_anio_escolar}'
                      fecha_registro='{$this->fecha_registro}'    
                      WHERE id={$ID}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM observacion_boletin WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function BuscarObservacion($id_usuario_estudiante, $id_anio_escolar) {
    $sql = "SELECT * FROM observaciones_boletin 
            WHERE id_usuario_estudiante = '$id_usuario_estudiante' 
              AND id_anio_escolar = '$id_anio_escolar' 
            LIMIT 1";
    $resultado = ConectorBD::ejecutarQuery($sql);

    if ($resultado && count($resultado) > 0) {
        $fila = $resultado[0];
        $obj = new ObservacionBoletin(); // crea vacío
        $obj->id = $fila['id'];
        $obj->id_usuario_estudiante = $fila['id_usuario_estudiante'];
        $obj->id_anio_escolar = $fila['id_anio_escolar'];
        $obj->observacion = $fila['observacion'];
        $obj->fecha_registro = $fila['fecha_registro'];
        return $obj;
    } else {
        return null;
    }
}

}
