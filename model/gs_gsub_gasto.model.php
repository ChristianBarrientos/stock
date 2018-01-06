<?php
class gs_gsub_gasto {
	
	private $id_gsub_gasto;
    private $id_sub_gasto;

    public function __construct($id_gsub_gasto, $id_sub_gasto)
    {
        $this->id_gsub_gasto = $id_gsub_gasto;
        $this->id_sub_gasto = $id_sub_gasto;
    
       
    }

    public static function alta($id_sub_gasto){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gsub_gasto = gs_gsub_gasto::ultimo_id();
        
        $sql = "INSERT INTO `gs_gsub_gasto`(`id_gsub_gasto`, `id_sub_gasto`) VALUES (0,$id_sub_gasto)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gsub_gasto;
        }else{
             
            return false;
        }

    }

    public static function agregar($id_gsub_gasto,$id_sub_gasto){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        
        
        $sql = "INSERT INTO `gs_gsub_gasto`(`id_gsub_gasto`, `id_sub_gasto`) VALUES ($id_gsub_gasto,$id_sub_gasto)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gsub_gasto;
        }else{
             
            return false;
        }

    }

    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='gs_gsub_gasto'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }


          
    public static function generar($id_gsub_gasto){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_gsub_gasto` WHERE id_gsub_gasto = $id_gsub_gasto");  
         
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $subgastos = array();
             
            foreach ($filas as $key => $value) {
                
                $subgastos[] = gs_subgasto::generar($value['id_sub_gasto']);
            
                

            }
            //$zona = mp_zona::obtener_zona__explicita($id_zona);
            $gsub_gasto = new gs_gsub_gasto($id_gsub_gasto,$subgastos);
            return $gsub_gasto;
        }
        else{
            return false;
        }
       
    }

    public static function obtener_gsubgasto_idsubgasto($id_sub_gasto){
        //obtener empleados por local
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `gs_gsubgasto` WHERE id_sub_gasto = $id_sub_gasto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
 
            return $res_fil['id_gsub_gasto'];
        }
        else{
            
            return false;
        }
       


    }


    public static function baja($id_gsub_gasto){
        //Esto debe ser con PDO, escapando caracteres con expresiones regulares
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("DELETE FROM `gs_gsub_gasto` WHERE id_gsub_gasto = $id_gsub_gasto");  

       return $res;
    }


    public function getId_gsub_gasto()
    {
        return $this->id_gsub_gasto;
    }
    
    public function setId_gsub_gasto($id_gsub_gasto)
    {
        $this->id_gsub_gasto = $id_gsub_gasto;
        return $this;
    }

    public function getId_sub_gasto()
    {
        return $this->id_sub_gasto;
    }
    
    public function setId_sub_gasto($id_sub_gasto)
    {
        $this->id_sub_gasto = $id_sub_gasto;
        return $this;
    }

   

    

}

?>