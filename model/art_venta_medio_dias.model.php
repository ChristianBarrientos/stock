<?php
class art_venta_medio_dias {
	
	private $id_dias_medio;
    private $dias;
    

    public function __construct($id_dias_medio, $dias)
    {
        $this->id_dias_medio = $id_dias_medio;
        $this->dias = $dias;
    }

    public static function alta_art_venta_medio_dias($dias){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_dias_medio = art_venta_medio_dias::ultimo_id_dias_medio();
        
        $sql = "INSERT INTO `art_venta_medio_dias`(`id_dias_medio`, `dias`) VALUES (0,'$dias')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_dias_medio;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_dias_medio(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_medio_dias'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_medio_dias($id_dias_medio){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio_dias` WHERE id_dias_medio = $id_dias_medio");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            $dias = new art_venta_medio_dias($res_fil['id_dias_medio'],$res_fil['dias']);
            return $dias;
        }
        else{
            
            return false;
        }
    }

    public static function update_dias($id_dias_medio,$dias){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio_dias` SET `dias`='$dias' WHERE id_dias_medio = $id_dias_medio");  
         
        return $res;
    }



    public function getId_dias_medio()
    {
        return $this->id_dias_medio;
    }
    
    public function setId_dias_medio($id_dias_medio)
    {
        $this->id_dias_medio = $id_dias_medio;
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