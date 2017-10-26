<?php
class art_unico {
	
	private $id_unico;
    private $id_lote;
    private $id_venta;
    

    public function __construct($id_unico, $id_lote, $id_venta)
    {
        $this->id_unico = $id_unico;
        $this->id_lote = $id_lote;
        $this->id_venta = $id_venta;
       
    }

    public static function alta_art_unico($id_lote,$id_venta){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_unico = art_unico::ultimo_id_unico();
        
        $sql = "INSERT INTO `art_unico`(`id_unico`, `id_lote_local`, `id_venta`) VALUES (0,$id_lote,$id_venta)";

        $res = $baseDatos->query($sql);
       
        if ($res) {
             
            return $id_unico;
        }else{
               
            return false;
        }

    }
    public static function ultimo_id_unico(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_unico'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_unico($id_unico){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_unico` WHERE id_unico = $id_unico");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $id_lote_local =  art_lote_local::generar_lote_local($res_fil['id_lote_local']);
            $id_venta = art_venta::generar_venta($res_fil['id_venta']);
            $unico = new art_unico($res_fil['id_unico'],$id_lote_local,$id_venta);
            return $unico;
        }
        else{
            
            return false;
        }
    }


    public function getId_unico()
    {
        return $this->id_unico;
    }
    
    public function setId_unico($id_unico)
    {
        $this->id_unico = $id_unico;
        return $this;
    }

    public function getId_lote()
    {
        return $this->id_lote;
    }
    
    public function setId_lote($id_lote)
    {
        $this->id_lote = $id_lote;
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


}

?>
