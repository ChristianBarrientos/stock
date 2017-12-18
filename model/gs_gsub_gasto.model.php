<?php
class gs_gsub_gasto {
	
	private $id_gsub_gasto;
    private $id_sub_gasto;

    public function __construct($id_gsub_gasto, $id_sub_gasto,$id_usuario)
    {
        $this->id_gsub_gasto = $id_gsub_gasto;
        $this->id_sub_gasto = $id_sub_gasto;
    
       
    }

    public static function alta_gsub_gasto($id_sub_gasto){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gsub_gasto = gs_gsub_gasto::ultimo_id_gsub_gasto();
        
        $sql = "INSERT INTO `gs_gsub_gasto`(`id_gsub_gasto`, `id_sub_gasto`) VALUES (0,$id_sub_gasto)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gsub_gasto;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_gsub_gasto(){
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
         
        $res_fil = $res->fetch_assoc();
         
        if (count($res_fil) != 0) {
            
            $id_sub_gasto = gs_subgasto::generar($res_fil['id_sub_gasto']);
            
            $gs_gsub_gasto = new gs_gsub_gasto($res_fil['id_gsub_gasto'],$id_sub_gasto);
            return $gs_gsub_gasto;
        }
        else{
           
            return false;
        }
       


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