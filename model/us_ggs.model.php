<?php
class us_ggs {
	
	private $id_us_ggs;
    private $id_gasto;
   

    public function __construct($id_us_ggs, $id_gasto)
    {
        $this->id_us_ggs = $id_us_ggs;
        $this->id_gasto = $id_gasto;
    
    }

    public static function alta($id_gasto){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_us_ggs = us_ggs::ultimo_id_us_ggs();
        
        $sql = "INSERT INTO `us_ggs`(`id_us_ggs`, `id_gasto`) VALUES (0,$id_gasto)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_us_ggs;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_us_ggs(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_ggs'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_us_ggs($id_us_ggs){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_ggs` WHERE id_us_ggs = $id_us_ggs");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $id_gasto = gs_gastos::generar_gasto($res_fil['id_gasto']);
            
            $us_ggs = new us_ggs($res_fil['id_us_ggs'],$id_gasto);
            return $us_ggs;
        }
        else{
            
            return false;
        }
    }

    public static function obtener($id_us_ggs){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_ggs` WHERE id_us_ggs = $id_us_ggs");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $usuario_gastos = array();
            foreach ($filas as $clave => $valor) {
                
                $id_gasto = gs_gastos::generar_gasto($valor['id_gasto']);
                 
                $us_ggs = new us_ggs($valor['id_us_ggs'],$id_gasto);
                $usuario_gastos[] = $us_ggs;

            }
            return $usuario_gastos;
        }
        else{
           
            return false;
        }

        
    }

    public static function agrega($id_us_ggs,$id_gasto){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        

        $sql = "INSERT INTO `us_ggs`(`id_us_ggs`, `id_gasto`) VALUES ($id_us_ggs,$id_gasto)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return true;
        }else{
             
            return false;
        }

    }

    public static function obtener_usggs_idgs($id_gasto){
        //obtener empleados por local
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `us_ggs` WHERE id_gasto = $id_gasto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
 
            return $res_fil['id_us_ggs'];
        }
        else{
            return false;
        }
    }

    public static function baja($id_gasto){
        //Esto debe ser con PDO, escapando caracteres con expresiones regulares
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("DELETE FROM `us_ggs` WHERE id_gasto = $id_gasto");  

       return $res;
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

    public function getId_gasto()
    {
        return $this->id_gasto ;
    }
    
    public function setId_gasto($id_gasto)
    {
        $this->id_gasto = $id_gasto;
        return $this;
    }

   

    

}

?>