<?php
class us_medio_pago {
	
	private $id_us_medio_pago;
    private $id_usuario;
   
    private $id_medio_pago;

    public function __construct($id_us_medio_pago, $id_usuario,$id_medio_pago)
    {
        $this->id_us_medio_pago = $id_us_medio_pago;
        $this->id_usuario = $id_usuario;
     
        $this->id_medio_pago = $id_medio_pago;
    
       
    }

    public static function alta($id_usuario,$id_medio_pago){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_us_medio_pago = us_medio_pago::ultimo_id();
        
        $sql = "INSERT INTO `us_medio_pago`(`id_us_medio_pago`, `id_usuario`, `id_medio_pago`) VALUES (0,$id_usuario,$id_medio_pago)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_us_medio_pago;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_medio_pago'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_us_medio_pago){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_medio_pago` WHERE id_us_medio_pago = $id_us_medio_pago");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil)) {
            
            
            $cliente = new us_medio_pago($res_fil['id_us_medio_pago'],$res_fil['id_usuario'],$res_fil['id_medio_pago']);
            return $cliente;
        }
        else{
            
            return false;
        }
    }

    public static function obtener($id_usuario){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_medio_pago` WHERE id_usuario = $id_usuario");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas)) {
            # code...
            $medios_pago = array();
        
            foreach ($filas as $clave => $valor) {
                $medios_pago[] = art_venta_medio_pago::generar($valor['id_medio_pago']);
                
            }
            
            return $medios_pago;
        }
        else{

            return false;
        }
    }


    public function getId()
    {
        return $this->id_us_medio_pago;
    }
    
    public function setId($id_us_medio_pago)
    {
        $this->id_us_medio_pago = $id_us_medio_pago;
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

    public function getId_medio_pago()
    {
        return $this->id_medio_pago;
    }
    
    public function setId_medio_pago($id_medio_pago)
    {
        $this->id_medio_pago = $id_medio_pago;
        return $this;
    }

   

    

}

?>