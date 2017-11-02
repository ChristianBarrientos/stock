<?php
class art_venta_medio_fechas {
	
	private $id_fechas_medio;
    private $fecha_hora_inicio;
    private $fecha_hora_fin;
    

    public function __construct($id_fechas_medio, $fecha_hora_inicio, $fecha_hora_fin)
    {
        $this->id_fechas_medio = $id_fechas_medio;
        $this->fecha_hora_inicio = $fecha_hora_inicio;
        $this->fecha_hora_fin = $fecha_hora_fin;
       
    
       
    }

    public static function alta_art_venta_medio_fechas($fecha_hora_inicio,$fecha_hora_fin){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_fechas_medio = art_venta_medio_fechas::ultimo_id_fechas_medio();
        
        $sql = "INSERT INTO `art_venta_medio_fechas`(`id_fechas_medio`, `fecha_hora_inicio`, `fecha_hora_fin`) VALUES (0,'$fecha_hora_inicio','$fecha_hora_fin')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_fechas_medio;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_fechas_medio(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_medio_fechas'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_medio_fechas($id_fechas_medio){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio_fechas` WHERE id_fechas_medio = $id_fechas_medio");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            $fechas = new art_venta_medio_fechas($res_fil['id_fechas_medio'],$res_fil['fecha_hora_inicio'],$res_fil['fecha_hora_fin']);
            
            return $fechas;
        }
        else{
            
            return false;
        }
    }

    public static function update_fecha_desde($id_fechas_medio,$fecha_desde,$fecha_hasta){
        //obtener empleados por local
        global $baseDatos;
        

        $res = $baseDatos->query(" UPDATE `art_venta_medio_fechas` SET `fecha_hora_fin`='$fecha_hasta',`fecha_hora_inicio`='$fecha_desde' WHERE id_fechas_medio = $id_fechas_medio");  
         
        return $res;
    }



    public function getId_fechas_medio()
    {
        return $this->id_fechas_medio;
    }
    
    public function setId_fechas_medio($id_fechas_medio)
    {
        $this->id_fechas_medio = $id_fechas_medio;
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