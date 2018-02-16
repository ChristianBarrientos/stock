<?php
class art_unico {
	
	private $id_unico;
    private $id_gunico;
    private $id_venta;
    

    public function __construct($id_unico, $id_gunico, $id_venta)
    {
        $this->id_unico = $id_unico;
        $this->id_gunico = $id_gunico;
        $this->id_venta = $id_venta;
       
    }

    public static function alta_art_unico($id_gunico,$id_venta){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_unico = art_unico::ultimo_id_unico();
        
        $sql = "INSERT INTO `art_unico`(`id_unico`, `id_gunico`, `id_venta`) VALUES (0,$id_gunico,$id_venta)";

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
            $id_gunico =  art_gunico::generar($res_fil['id_gunico']);
            $id_venta = art_venta::generar($res_fil['id_venta']);
            $unico = new art_unico($res_fil['id_unico'],$id_gunico,$id_venta);
            
            return $unico;
        }
        else{
            
            return false;
        }
    }

    public static function obtener_por($id_venta){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_unico` WHERE id_venta = $id_venta");  
        $res_fil = $res->fetch_assoc();

         
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $id_gunico =  art_gunico::generar($res_fil['id_gunico']);
            $id_venta = art_venta::generar($res_fil['id_venta']);
            $unico = new art_unico($res_fil['id_unico'],$id_gunico,$id_venta);
            
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

    public function getId_gunico()
    {
        return $this->id_gunico;
    }
    
    public function setId_gunico($id_gunico)
    {
        $this->id_gunico = $id_gunico;
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
