<?php
class art_carga {
	
	private $id_carga;
    private $fecha_hora;
   
    private $id_usuario;

    public function __construct($id_carga, $fecha_hora,$id_usuario)
    {
        $this->id_carga = $id_carga;
        $this->fecha_hora = $fecha_hora;
     
        $this->id_usuario = $id_usuario;
    
       
    }

    public static function alta_art_carga($fecha_hora,$id_usuario){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_carga = art_carga::ultimo_id_carga();
        
        $sql = "INSERT INTO `art_carga`(`id_carga`, `fecha_hora`, `id_usuario`) VALUES (0,'$fecha_hora',$id_usuario)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_carga;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_carga(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_carga'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_carga($id_carga){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_carga` WHERE id_carga = $id_carga");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $id_us = usuario::obtener_tabla_usuario($res_fil['id_usuario']);
            
            $carga = new art_carga($res_fil['id_carga'],$res_fil['fecha_hora'],$id_us);
            return $carga;
        }
        else{
            
            return false;
        }
       


    }


    public function getId_carga()
    {
        return $this->id_carga;
    }
    
    public function setId_carga($id_carga)
    {
        $this->id_carga = $id_carga;
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

   

    

}

?>