<?php
class us_sl_liquidacion {
	
	private $id_ussl_liquidacion;
    private $id_gs_gsueldo;
    private $fecha_desde;
    private $fecha_hasta;

    public function __construct($id_ussl_liquidacion, $id_gs_gsueldo,$fecha_desde,$fecha_hasta)
    {
        $this->id_ussl_liquidacion = $id_ussl_liquidacion;
        $this->id_gs_gsueldo = $id_gs_gsueldo;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
 
    }

    public static function alta($id_gs_gsueldo,$fecha_desde,$fecha_hasta){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_ussl_liquidacion = us_sl_liquidacion::ultimo_id();
        
        $sql = "INSERT INTO `us_sl_liquidacion`(`id_ussl_liquidacion`, `id_gs_gsueldo`, `fecha_hora`) VALUES (0,$id_gs_gsueldo,'$fecha_hora')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_ussl_liquidacion;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_sl_liquidacion'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_ussl_liquidacion){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_sl_liquidacion` WHERE id_ussl_liquidacion = $id_ussl_liquidacion");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            $gs_gsueldo = sl_gsgsueldo::generar($res_fil['id_gs_gsueldo']);
            $us_sl_liquidacion = new us_sl_liquidacion($res_fil['id_ussl_liquidacion'],$gs_gsueldo,$res_fil['fecha_hora']);
            return $us_sl_liquidacion;
        }
        else{
            
            return false;
        }
       


    }

    public static function obtener(){
//CAMBIAR!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `us_sl_liquidacion`");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $us_sl_liquidacion = array();

            foreach ($filas as $clave => $valor) {
                $us_sl_liquidacion [] = new us_sl_liquidacion($valor['id_ussl_liquidacion'],$valor['id_gs_gsueldo'],$valor['fecha_hora']);
            }

            return $us_sl_liquidacion;
        }
        else{
            return false;
        }
    }


    public function getId()
    {
        return $this->id_ussl_liquidacion;
    }
    
    public function setId($id_ussl_liquidacion)
    {
        $this->id_ussl_liquidacion = $id_ussl_liquidacion;
        return $this;
    }

    public function getId_gs_gsueldo()
    {
        return $this->id_gs_gsueldo;
    }
    
    public function setId_gs_gsueldo($id_gs_gsueldo)
    {
        $this->id_gs_gsueldo = $id_gs_gsueldo;
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