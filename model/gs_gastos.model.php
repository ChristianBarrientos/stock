<?php
class gs_gastos {
	
	private $id_gasto;
    private $nombre;
    private $id_gs_des;
    private $id_ggs;
    private $habilitado;

    public function __construct($id_gasto, $nombre,$id_gs_des,$id_ggs,$habilitado)
    {
        $this->id_gasto = $id_gasto;
        $this->nombre = $nombre;
        $this->id_gs_des = $id_gs_des;
        $this->id_ggs = $id_ggs;
        $this->habilitado = $habilitado;
    
       
    }

    public static function alta($nombre,$id_gs_des,$id_ggs,$habilitado = true){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gasto = gs_gastos::ultimo_id_gastos();
        
        $sql = "INSERT INTO `gs_gastos`(`id_gasto`, `nombre`, `id_gs_des`, `id_ggs`, `habilitado`) VALUES (0,'$nombre',$id_gs_des,$id_ggs,$habilitado)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gasto;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_gastos(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='gs_gastos'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_gasto($id_gasto){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_gastos` WHERE id_gasto = $id_gasto");  

        $res_fil = $res->fetch_assoc();
       
        if (count($res_fil) != 0) {
            $id_gs_des = gs_descripcion::generar_gsdes($res_fil['id_gs_des']);
            $id_ggs = gs_grupo::generar_ggs($res_fil['id_ggs']);
           
            $gasto = new gs_gastos($res_fil['id_gasto'],$res_fil['nombre'],$id_gs_des,$id_ggs,$res_fil['habilitado']);

            
            return $gasto;  
        }
        else{
            
            return false;
        }
    }

    public static function obtener_por($id_ggs){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_gastos` WHERE id_ggs = $id_ggs");  

        $res_fil = $res->fetch_assoc();
       
        if (count($res_fil) != 0) {
            $id_gs_des = gs_descripcion::generar_gsdes($res_fil['id_gs_des']);
            $id_ggs = gs_grupo::generar_ggs($res_fil['id_ggs']);
           
            $gasto = new gs_gastos($res_fil['id_gasto'],$res_fil['nombre'],$id_gs_des,$id_ggs,$res_fil['habilitado']);

            
            return $gasto;  
        }
        else{
            
            return false;
        }
    }


    public static function update($id_gasto, $columna, $nuevo_valor){
        //obtener empleados por local
        global $baseDatos;
        if ($columna == 'habilitado') {
            # code...
            $columna = 'habilitado';
        }
        $res = $baseDatos->query(" UPDATE `gs_gastos` SET `$columna`='$nuevo_valor' WHERE id_gasto = $id_gasto");  
         
        return $res;
    }

    public static function baja($id_gasto){
        //Esto debe ser con PDO, escapando caracteres con expresiones regulares
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("DELETE FROM `gs_gastos` WHERE id_gasto = $id_gasto");  

       return $res;
    }

    


    public function getId_gasto()
    {
        return $this->id_gasto;
    }
    
    public function setId_carga($Id_gasto)
    {
        $this->id_gasto = $id_gasto;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function setFecha_hora($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getId_gs_des()
    {
        return $this->id_gs_des;
    }
    
    public function setId_gs_des($id_gs_des)
    {
        $this->id_gs_des = $id_gs_des;
        return $this;
    }

    public function getId_ggs()
    {
        return $this->id_ggs;
    }
    
    public function setId_ggs($id_ggs)
    {
        $this->id_ggs = $id_ggs;
        return $this;
    }

    public function getHabilitado()
    {
        return $this->habilitado;
    }
    
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;
        return $this;
    }

   

    

}

?>