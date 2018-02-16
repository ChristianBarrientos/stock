<?php
class us_gmv {
	
	private $id_gmv;
    private $id_gs_mv;
   
   

    public function __construct($id_gmv, $id_gs_mv)
    {
        $this->id_gmv = $id_gmv;
        $this->id_gs_mv = $id_gs_mv;
       
    }

    public static function alta($id_gs_mv){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gmv = us_gmv::ultimo_id();
        
        $sql = "INSERT INTO `us_gmv`(`id_gmv`, `id_gs_mv`) VALUES (0,$id_gs_mv)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gmv;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_gmv'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }


    public static function obtener($id_gmv){
        //obtener empleados por local
        global $baseDatos;

        $res = $baseDatos->query("SELECT * FROM `us_gmv` WHERE id_gmv = $id_gmv");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0){
            
            $id_gs_mv = gs_gasto_unico::generar($valor['id_gs_mv']);
            $us_gmv = new us_gmv($res_fil['id_gmv'],$id_gs_mv);
            return $us_gmv;

        }
        else{
           
            return false;
        }
        
    }

    public static function obtener_por($id_gs_mv){
        //obtener empleados por local
        global $baseDatos;

        $res = $baseDatos->query("SELECT * FROM `us_gmv` WHERE id_gs_mv = $id_gs_mv");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0){
            
            $id_gs_mv = gs_gasto_unico::generar($res_fil['id_gs_mv']);
            $us_gmv = new us_gmv($res_fil['id_gmv'],$id_gs_mv);
            return $us_gmv;

        }
        else{
           
            return false;
        }
        
    }

    public static function generar($id_gmv){
        //obtener empleados por local
        global $baseDatos;
          
    
        $res = $baseDatos->query("SELECT * FROM `us_gmv` WHERE id_gmv = $id_gmv");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $gs_mv = array();

            foreach ($filas as $clave => $valor) {

                $id_gs_mv = gs_gasto_unico::generar($valor['id_gs_mv']);
                
               
                $gs_mv[] = $id_gs_mv;

            }
          
            $us_gmv = new us_gmv($valor['id_gmv'],$gs_mv);
            return $us_gmv;
        }
        else{
           
            return false;
        }
        
    }

     public static function agrega($id_ggs,$id_gs_mv){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        
        
        $sql = "INSERT INTO `us_gmv`(`id_gmv`, `id_gs_mv`) VALUES ($id_ggs,$id_gs_mv)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return true;
        }else{
             
            return false;
        }

    }

    public function getId()
    {
        return $this->id_gmv;
    }
    
    public function setId($id_gmv)
    {
        $this->id_gmv = $id_gmv;
        return $this;
    }

    public function getId_gs_mv()
    {
        return $this->id_gs_mv;
    }
    
    public function setId_gs_mv($id_gs_mv)
    {
        $this->id_gs_mv = $id_gs_mv;
        return $this; 
    }
   

    

}

?>