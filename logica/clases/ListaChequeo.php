<?php

class ListaChequeo
{
    protected $id;
    protected $institucion_educativa_id;
    protected $convenio;
    protected $poliza_responsabilidad;
    protected $poliza_riesgo_biologico;
    protected $anexo_tecnico;
    protected $cronograma;
    protected $esquema_vacunacion;
    protected $ssst;
    protected $arl;
    protected $fecha_subida;

    public function __construct($campo, $valor)
    {
        if (!is_array($campo)) {
            $cadenaSQL = "SELECT id, institucion_educativa_id, convenio, poliza_responsabilidad, poliza_riesgo_biologico, anexo_tecnico, cronograma, esquema_vacunacion, ssst, arl, fecha_subida FROM documentos WHERE $campo=$valor";
            $resultado = ConectorBD::ejecutarQuery($cadenaSQL);

            if ($resultado && count($resultado) > 0) {
                $campo = $resultado[0];
            } else {
                $campo = null;
            }
        }

         if (is_array($campo)) {
            $this->id = $campo['id'];
            $this->institucion_educativa_id = $campo['institucion_educativa_id'];
            $this->convenio = $campo['convenio'];
            $this->poliza_responsabilidad = $campo['poliza_responsabilidad'];
            $this->poliza_riesgo_biologico = $campo['poliza_riesgo_biologico'];
            $this->anexo_tecnico = $campo['anexo_tecnico'];
            $this->cronograma = $campo['cronograma'];
            $this->esquema_vacunacion = $campo['esquema_vacunacion'];
            $this->ssst = $campo['ssst'];
            $this->arl = $campo['arl'];
            $this->fecha_subida = $campo['fecha_subida'];
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function getInstitucion_educativa_id() {
        return $this->institucion_educativa_id;
    }

    public function getConvenio() {
        return $this->convenio;
    }

    public function getPoliza_responsabilidad() {
        return $this->poliza_responsabilidad;
    }

    public function getPoliza_riesgo_biologico() {
        return $this->poliza_riesgo_biologico;
    }

    public function getAnexo_tecnico() {
        return $this->anexo_tecnico;
    }

    public function getCronograma() {
        return $this->cronograma;
    }

    public function getEsquema_vacunacion() {
        return $this->esquema_vacunacion;
    }

    public function getSsst() {
        return $this->ssst;
    }

    public function getArl() {
        return $this->arl;
    }

    public function getFecha_subida() {
        return $this->fecha_subida;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setInstitucion_educativa_id($institucion_educativa_id): void {
        $this->institucion_educativa_id = $institucion_educativa_id;
    }

    public function setConvenio($convenio): void {
        $this->convenio = $convenio;
    }

    public function setPoliza_responsabilidad($poliza_responsabilidad): void {
        $this->poliza_responsabilidad = $poliza_responsabilidad;
    }

    public function setPoliza_riesgo_biologico($poliza_riesgo_biologico): void {
        $this->poliza_riesgo_biologico = $poliza_riesgo_biologico;
    }

    public function setAnexo_tecnico($anexo_tecnico): void {
        $this->anexo_tecnico = $anexo_tecnico;
    }

    public function setCronograma($cronograma): void {
        $this->cronograma = $cronograma;
    }

    public function setEsquema_vacunacion($esquema_vacunacion): void {
        $this->esquema_vacunacion = $esquema_vacunacion;
    }

    public function setSsst($ssst): void {
        $this->ssst = $ssst;
    }

    public function setArl($arl): void {
        $this->arl = $arl;
    }

    public function setFecha_subida($fecha_subida): void {
        $this->fecha_subida = $fecha_subida;
    }
        
    public function __toString()
    {
        return $this->id;
    }
    
    public function guardar()
    {
        $cadenaSQL = "INSERT INTO documentos (institucion_educativa_id, convenio, poliza_responsabilidad, poliza_riesgo_biologico, anexo_tecnico, cronograma, esquema_vacunacion, ssst, arl, fecha_subida)"
                . " VALUES ('$this->institucion_educativa_id',
                    '$this->convenio', '$this->poliza_responsabilidad', '$this->poliza_riesgo_biologico',
                    '$this->anexo_tecnico', '$this->cronograma', '$this->esquema_vacunacion', '$this->ssst', '$this->arl',
                    '$this->fecha_subida')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($ID)
    {
        $cadenaSQL = "UPDATE documentos SET
                    institucion_educativa_id = '$this->institucion_educativa_id',
                    convenio = '$this->convenio',
                    poliza_responsabilidad = '$this->poliza_responsabilidad',
                    poliza_riesgo_biologico = '$this->poliza_riesgo_biologico',
                    anexo_tecnico = '$this->anexo_tecnico',
                    cronograma = '$this->cronograma',
                    esquema_vacunacion = '$this->esquema_vacunacion',
                    ssst = '$this->ssst',
                    arl = '$this->arl',
                    fecha_subida = '$this->fecha_subida'
                    WHERE id='{$ID}'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }
    
    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM documentos WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }
    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '') $filtro = '';
        else $filtro = " WHERE $filtro";
        if ($orden == null || $orden == '') $orden = '';
        else $orden = " ORDER BY $orden";
        $cadenaSQL = "SELECT id, institucion_educativa_id, convenio, poliza_responsabilidad, poliza_riesgo_biologico, anexo_tecnico, cronograma, esquema_vacunacion, ssst, arl, fecha_subida FROM documentos $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
    
    public function obtenerPorIdYInstitucion($id, $id_institucion)
    {
        $sql = "SELECT * FROM documentos_chequeo WHERE id = ? AND id_institucion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id, $id_institucion);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
    $resultado = ListaChequeo::getLista($filtro, $orden);
    
    $lista = array();
    if ($resultado && is_array($resultado)) {
        for ($i = 0; $i < count($resultado); $i++) {
            $institucion = new ListaChequeo($resultado[$i], null);
            $lista[$i] = $institucion;
        }
    }
    return $lista;
    }
}