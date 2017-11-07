<?php
class art_venta_cambio {
	
	private $id_cambio;
    private $fecha_hora; 
    private $id_usuario;
    private $id_lote_local;
    private $saldo_favor;

    public function __construct($id_cambio, $fecha_hora,$id_usuario,$id_lote_local,$saldo_favor)
    {
        $this->id_cambio = $id_cambio;
        $this->fecha_hora = $fecha_hora;
        $this->id_usuario = $id_usuario;
        $this->id_lote_local = $id_lote_local;
        $this->saldo_favor = $saldo_favor;
       
    }

    public static function alta_art_venta_cambio($fecha_hora,$id_usuario,$id_lote_local,$saldo_favor){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_cambio = art_venta_cambio::ultimo_id_venta_cambio();
        $sql = "INSERT INTO `art_venta_cambio`(`id_cambio`, `fecha_hora`, `id_usuario`, `id_lote_local`, `saldo_favor`) VALUES (0,'$fecha_hora',$id_usuario,$id_lote_local,$saldo_favor)";

        $res = $baseDatos->query($sql);
        if ($res) {
            
            return $id_cambio;
        }else{
            
            return false;
        }

    }
    public static function ultimo_id_venta_cambio(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_cambio'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_venta_cambio($id_cambio){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta_cambio` WHERE id_cambio = $id_cambio");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $id_usuario = usuario::generar_usuario($res_fil['id_usuarios']);
            $id_lote_local =  art_lote_local::generar_lote_local($res_fil['id_lote_local']);

            $cambio = new art_venta_cambio($res_fil['id_cambio'],$res_fil['fecha_hora'],$id_usuario,$id_lote_local,$res_fil['saldo_favor']);

            
            return $cambio;
        }
        else{
            
            return false;
        }
    }


    public function getId_lote_local()
    {
        return $this->id_lote_local;
    }
    
    public function setId_lote_local($id_lote_local)
    {
        $this->id_lote_local = $id_lote_local;
        return $this;
    }

    public function getSaldo_Favor()
    {
        return $this->saldo_favor;
    }
    
    public function setSaldo_Favor($saldo_favor)
    {
        $this->saldo_favor = $saldo_favor;
        return $this;
    }


    public function getId_venta()
    {
        return $this->id_venta;
    }
    
    public function setId_venta($id_venta)
    {
        $this->id_venta = $id_venta;
        return $this;
    }

    public function getFecha_hora()
    {
        return $this->fecha_hora;
    }
    
    public function setFecha_hora($fecha_hora)
    {
        $this->fecha_hora = $fecha_hora;
        return $this;
    }

    public function getId_local()
    {
        return $this->id_local;
    }
    
    public function setId_local($id_local)
    {
        $this->id_local = $id_local;
        return $this;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }
    
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        return $this;
    }


}
?>