<?php
class art_venta_medio_descripcion {
	
	private $id_medio_descripcion;
    private $nombre;
    private $descripcion;
    

    public function __construct($id_medio_descripcion, $nombre, $descripcion)
    {
        $this->id_medio_descripcion = $id_medio_descripcion;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public static function alta_art_venta_medio_descripcion($nombre,$descripcion = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio_descripcion = art_venta_medio_descripcion::ultimo_id_medio_descripcion();
        
        $sql = "INSERT INTO `art_venta_medio_descripcion`(`id_medio_descripcion`, `nombre`, `descripcion`) VALUES (0,'$nombre','$descripcion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_medio_descripcion;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_medio_descripcion(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_medio_descripcion'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_venta_medio_descripcion($id_medio_descripcion){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio_descripcion` WHERE id_medio_descripcion = $id_medio_descripcion");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
          
            $medio_descripcion = new art_venta_medio_descripcion($res_fil['id_medio_descripcion'],$res_fil['nombre'],$res_fil['descripcion']);
            
            return $medio_descripcion;
        }
        else{
            
            return false;
        }
    }

    public static function obtener_medios(){
        global $baseDatos;
       
        $res = $baseDatos->query("SELECT * FROM art_venta_medio_descripcion");  
       
        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $medio_descripcion = array();
             
            foreach ($filas as $key => $value) {
                
                $medio_descripcion[]= new art_venta_medio_descripcion($value['id_medio_descripcion'],$value['nombre'],$value['descripcion']);
            }
            
            return $medio_descripcion;
        }
        else{
            return false;
        }
        
    }

    public static function update_nombre($id_medio,$nombre){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio` SET `nombre`='$nombre' WHERE id_medio = $id_medio");  
         
        return $res;
    }




    public function getId_medio_descripcion()
    {
        return $this->id_medio_descripcion;
    }
    
    public function setId_medio_descripcion($id_medio_descripcion)
    {
        $this->id_medio_descripcion = $id_medio_descripcion;
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