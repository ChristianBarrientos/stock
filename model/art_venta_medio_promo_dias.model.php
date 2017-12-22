<?php
class art_venta_medio_promo_dias {
	
	private $id_medio_promo_dias;
    private $dias;
    

    public function __construct($id_medio_promo_dias, $dias)
    {
        $this->id_medio_promo_dias = $id_medio_promo_dias;
        $this->dias = $dias;
    }

    public static function alta($dias){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio_promo_dias = art_venta_medio_promo_dias::ultimo_id();
        
        $sql = "INSERT INTO `art_venta_medio_promo_dias`(`id_medio_promo_dias`, `dias`) VALUES (0,'$dias')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_medio_promo_dias;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_medio_promo_dias'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_medio_promo_dias){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio_promo_dias` WHERE id_medio_promo_dias = $id_medio_promo_dias");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            $dias = new art_venta_medio_promo_dias($res_fil['id_medio_promo_dias'],$res_fil['dias']);
            return $dias;
        }
        else{
            
            return false;
        }
    }

    public static function update_dias($id_medio_promo_dias,$dias){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio_promo_dias` SET `dias`='$dias' WHERE id_medio_promo_dias = $id_medio_promo_dias");  
         
        return $res;
    }



    public function getId()
    {
        return $this->id_medio_promo_dias;
    }
    
    public function setId($id_medio_promo_dias)
    {
        $this->id_medio_promo_dias = $id_medio_promo_dias;
        return $this;
    }

    public function getDias()
    {
        return $this->dias;
    }
    
    public function setDias($dias)
    {
        $this->dias = $dias;
        return $this;
    }

    

   

}

?>