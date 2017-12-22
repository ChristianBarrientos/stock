<?php
class art_venta_medio_promo_fechas {
	
	private $id_medio_promo_fecha;
    private $fecha_hora_inicio;
    private $fecha_hora_fin;
    

    public function __construct($id_medio_promo_fecha, $fecha_hora_inicio, $fecha_hora_fin)
    {
        $this->id_medio_promo_fecha = $id_medio_promo_fecha;
        $this->fecha_hora_inicio = $fecha_hora_inicio;
        $this->fecha_hora_fin = $fecha_hora_fin;
       
    
       
    }

    public static function alta($fecha_hora_inicio,$fecha_hora_fin){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio_promo_fecha = art_venta_medio_promo_fechas::ultimo_id();
        
        $sql = "INSERT INTO `art_venta_medio_promo_fechas`(`id_medio_promo_fecha`, `fecha_hora_inicio`, `fecha_hora_fin`) VALUES (0,'$fecha_hora_inicio','$fecha_hora_fin')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_medio_promo_fecha;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_medio_promo_fechas'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_medio_promo_fecha){
        global $baseDatos;
        echo $id_medio_promo_fecha;
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio_promo_fechas` WHERE id_medio_promo_fecha = $id_medio_promo_fecha");  
        $res_fil = $res->fetch_assoc();
        
      
        if (count($res_fil)) {
            
            $fechas = new art_venta_medio_promo_fechas($res_fil['id_medio_promo_fecha'],$res_fil['fecha_hora_inicio'],$res_fil['fecha_hora_fin']);
            
            return $fechas;
        }
        else{
            
            return false;
        }
    }

    public static function update_fecha_desde($id_medio_promo_fecha,$fecha_desde,$fecha_hasta){
        //obtener empleados por local
        global $baseDatos;
        

        $res = $baseDatos->query(" UPDATE `art_venta_medio_promo_fechas` SET `fecha_hora_fin`='$fecha_hasta',`fecha_hora_inicio`='$fecha_desde' WHERE id_medio_promo_fecha = $id_medio_promo_fecha");  
         
        return $res;
    }



    public function getId()
    {
        return $this->id_medio_promo_fecha;
    }
    
    public function setId($id_medio_promo_fecha)
    {
        $this->id_medio_promo_fecha = $id_medio_promo_fecha;
        return $this;
    }

    public function getFecha_hora_inicio()
    {
        return $this->fecha_hora_inicio;
    }
    
    public function setFecha_hora_inicio($fecha_hora_inicio)
    {
        $this->fecha_hora_inicio = $fecha_hora_inicio;
        return $this;
    }

    public function getFecha_hora_fin()
    {
        return $this->fecha_hora_fin;
    }
    
    public function setFecha_hora_fin($fecha_hora_fin)
    {
        $this->fecha_hora_fin = $fecha_hora_fin;
        return $this;
    }

   

}

?>