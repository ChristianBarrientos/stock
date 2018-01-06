
<?php
class gs_grupo {
	
	private $id_ggs;
    private $id_gasto_unico;
   

    public function __construct($id_ggs, $id_gasto_unico)
    {
        $this->id_ggs = $id_ggs;
        $this->id_gasto_unico = $id_gasto_unico;
    
    
       
    }

    public static function alta($id_gasto_unico){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_ggs = gs_grupo::ultimo_id_ggs();
        
        $sql = "INSERT INTO `gs_grupo`(`id_ggs`, `id_gasto_unico`) VALUES (0,'$id_gasto_unico')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_ggs;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_ggs(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='gs_grupo'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_ggs($id_ggs){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_grupo` WHERE id_ggs = $id_ggs");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $gastos_unicos = array();

            foreach ($filas as $clave => $valor) {

                $id_gasto_unico = gs_gasto_unico::generar($valor['id_gasto_unico']);
                
                
                $gastos_unicos[] = $id_gasto_unico;

            }
            $ggs = new gs_grupo($id_ggs,$gastos_unicos);
            return $ggs;
        }
        else{
           
            return false;
        }
        
    }

     public static function agrega($id_ggs,$id_gasto_unico){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        
        
        $sql = "INSERT INTO `gs_grupo`(`id_ggs`, `id_gasto_unico`) VALUES ($id_ggs,$id_gasto_unico)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return true;
        }else{
             
            return false;
        }

    }

    public static function obtener_ggs_idgsunico($id_gasto_unico){
        //obtener empleados por local
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `gs_grupo` WHERE id_gasto_unico = $id_gasto_unico");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
 
            return $res_fil['id_ggs'];
        }
        else{
            return false;
        }
    }

    public static function update($id_ggs, $columna, $nuevo_valor){
        //obtener empleados por local
        global $baseDatos;

        $res = $baseDatos->query(" UPDATE `gs_grupo` SET `$columna`='$nuevo_valor' WHERE id_ggs = $id_ggs");  
         
        return $res;
    }

    public static function baja($id_ggs){
        //Esto debe ser con PDO, escapando caracteres con expresiones regulares
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("DELETE FROM `gs_grupo` WHERE id_ggs = $id_ggs");  

       return $res;
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

    public function getId_gasto_unico()
    {
        return $this->id_gasto_unico;
    }
    
    public function setId_gasto_unico($id_gasto_unico)
    {
        $this->id_gasto_unico = $id_gasto_unico;
        return $this;
    }
   

    

}

?>