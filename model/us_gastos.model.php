<?php
class us_gastos {
	
	private $id_us_gasto;
    private $id_usuario;
    private $id_us_ggs;

    public function __construct($id_us_gasto, $id_usuario,$id_us_ggs)
    {
        $this->id_us_gasto = $id_us_gasto;
        $this->id_usuario = $id_usuario;
     
        $this->id_us_ggs = $id_us_ggs;
    
       
    }

    public static function alta($id_usuario,$id_us_ggs){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_us_gastos = us_gastos::ultimo_id_us_gasto();
        
        $sql = "INSERT INTO `us_gastos`(`id_us_gasto`, `id_usuario`, `id_us_ggs`) VALUES (0,$id_usuario,$id_us_ggs)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_us_gastos;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_us_gasto(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_gastos'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_us_gasto($id_us_gasto){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_gastos` WHERE id_us_gasto = $id_us_gasto");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $us_ggs = us_ggs::generar_us_ggs($res_fil['id_us_ggs']);
            
            $us_gastos = new us_gastos($res_fil['id_us_gasto'],$res_fil['id_usuario'],$us_ggs);
            return $us_gastos;
        }
        else{
            
            return false;
        }
    }

    public static function obtener($id_usuario){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_gastos` WHERE id_usuario = $id_usuario");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            $us_ggs = us_ggs::obtener($res_fil['id_us_ggs']);
           
            $us_gastos = new us_gastos($res_fil['id_us_gasto'],$res_fil['id_usuario'],$us_ggs);

            
            return $us_gastos;
        }
        else{
            
            return false;
        }
    }
   
    public function getId_us_gastos()
    {
        return $this->id_us_gastos;
    }
    
    public function setId_us_gastos($id_us_gastos)
    {
        $this->id_us_gastos = $id_us_gastos;
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

    public function getId_us_ggs()
    {
        return $this->id_us_ggs;
    }
    
    public function setId_us_ggs($id_us_ggs)
    {
        $this->id_us_ggs = $id_us_ggs;
        return $this;
    }
    

}

?>