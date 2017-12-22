<?php
class art_venta_medio_tipo {
	
	private $id_medio_tipo;
    private $nombre;
   
    private $descripcion;

    public function __construct($id_medio_tipo, $nombre,$descripcion)
    {
        $this->id_medio_tipo = $id_medio_tipo;
        $this->nombre = $nombre;
     
        $this->descripcion = $descripcion;
    
       
    }

    public static function alta($nombre,$descripcion = null){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio_tipo = art_venta_medio_tipo::ultimo_id();
        
        $sql = "INSERT INTO `art_venta_medio_tipo`(`id_medio_tipo`, `nombre`, `descripcion`) VALUES (0,'$nombre','$descripcion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_medio_tipo;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_medio_tipo'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_medio_tipo){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio_tipo` WHERE id_medio_tipo = $id_medio_tipo");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            
            $cliente = new art_venta_medio_tipo($res_fil['id_medio_tipo'],$res_fil['nombre'],$res_fil['descripcion']);
            return $cliente;
        }
        else{
            
            return false;
        }
    }

    public static function obtener(){
        //obtener empleados por local
 
        
        global $baseDatos;
       
        $res = $baseDatos->query("SELECT * FROM art_venta_medio_tipo ");  
       
        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $medios = array();
             
            foreach ($filas as $key => $value) {
                
                $medios[]= new art_venta_medio_tipo($value['id_medio_tipo'],$value['nombre'],$value['descripcion']);
            }
            
            return $medios;
        }
        else{
            return false;
        }
    }


    public function getId()
    {
        return $this->id_medio_tipo;
    }
    
    public function setId($id_medio_tipo)
    {
        $this->id_medio_tipo = $id_medio_tipo;
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