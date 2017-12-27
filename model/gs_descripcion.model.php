<?php
class gs_descripcion {
	
	private $id_gs_des;
    private $nombre;
   
    private $descripcion;

    public function __construct($id_gs_des, $nombre,$descripcion)
    {
        $this->id_gs_des = $id_gs_des;
        $this->nombre = $nombre;
     
        $this->descripcion = $descripcion;
    
       
    }

    public static function alta($nombre,$descripcion){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gs_des = gs_descripcion::ultimo_id_gs_des();
        
        $sql = "INSERT INTO `gs_descripcion`(`id_gs_des`, `nombre`, `descripcion`) VALUES (0,'$nombre','$descripcion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gs_des;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_gs_des(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='gs_descripcion'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_gsdes($id_gs_des){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_descripcion` WHERE id_gs_des = $id_gs_des");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            
            $gs_descripcion = new gs_descripcion($res_fil['id_gs_des'],$res_fil['nombre'],$res_fil['descripcion']);
            return $gs_descripcion;
        }
        else{
            
            return false;
        }
       


    }

    public static function obtener(){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_descripcion`");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $gs_des = array();

            foreach ($filas as $clave => $valor) {
                $gs_des [] = new gs_descripcion($valor['id_gs_des'],$valor['nombre'],$valor['descripcion']);
            }

            return $gs_des;
        }
        else{
            return false;
        }
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

    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }
   

    

}

?>