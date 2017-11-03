<?php
class art_venta_medio {
	
	private $id_medio;
    private $nombre;
    private $descripcion;
    private $descuento;
    private $id_fechas_medio;
    private $id_dias_medio;
    private $id_usuario;

    public function __construct($id_medio, $nombre, $descripcion,$descuento,$id_fechas_medio,$id_dias_medio,$id_usuario)
    {
        $this->id_medio = $id_medio;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->descuento = $descuento;
        $this->id_fechas_medio = $id_fechas_medio;
        $this->id_dias_medio = $id_dias_medio;
        $this->id_usuario = $id_usuario;
    
       
    }

    public static function alta_art_venta_medio($nombre,$descripcion,$descuento,$id_fechas_medio,$id_dias_medio,$id_usuario){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio = art_venta_medio::ultimo_id_conjunto();
        
        $sql = "INSERT INTO `art_venta_medio`(`id_medio`, `nombre`, `descripcion`, `descuento`, `id_fechas_medio`, `id_dias_medio`, `id_usuario`) VALUES (0,'$nombre','$descripcion',$descuento,$id_fechas_medio,$id_dias_medio,$id_usuario)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_medio;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_conjunto(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_conjunto'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_venta_medio($id_medio){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio` WHERE id_medio = $id_medio");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $medio_fechas = art_venta_medio_fechas::generar_medio_fechas($res_fil['id_fechas_medio']);
            $medio_dias = art_venta_medio_dias::generar_medio_dias($res_fil['id_dias_medio']);
          
            $venta_medio = new art_venta_medio($res_fil['id_medio'],$res_fil['nombre'],$res_fil['descripcion'],$res_fil['descuento'],$medio_fechas,$medio_dias,$res_fil['id_usuario']);
            
            return $venta_medio;
        }
        else{
            
            return false;
        }
    }

    public static function obtener_medios($id_usuario){
        global $baseDatos;
       
        $res = $baseDatos->query("SELECT * FROM art_venta_medio WHERE id_usuario = $id_usuario");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $medios = array();
             
            foreach ($filas as $key => $value) {
                $id_fechas_medio = art_venta_medio_fechas::generar_medio_fechas($value['id_fechas_medio']);
                $id_dias_medio = art_venta_medio_dias::generar_medio_dias($value['id_dias_medio']);
                $medios[]= new art_venta_medio($value['id_medio'],$value['nombre'],$value['descripcion'],$value['descuento'],$id_fechas_medio,$id_dias_medio,$value['id_usuario']);
            }
            
            return $medios;
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

    public static function update_descripcion($id_medio,$descripcion){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio` SET `descripcion`='$descripcion' WHERE id_medio = $id_medio");  
         
        return $res;
    }

    public static function update_descuento($id_medio,$descuento){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio` SET `descuento`='$descuento' WHERE id_medio = $id_medio");  
         
        return $res;
    }

    



    public function getId_medio()
    {
        return $this->id_medio;
    }
    
    public function setId_medio($id_medio)
    {
        $this->id_medio = $id_medio;
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

    public function getDescuento()
    {
        return $this->descuento;
    }
    
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
        return $this;
    }
    public function getId_fechas_medio()
    {
        return $this->id_fechas_medio;
    }
    
    public function setId_fechas_medio($id_fechas_medio)
    {
        $this->id_fechas_medio = $id_fechas_medio;
        return $this;
    }
    public function getId_dias_medio()
    {
        return $this->id_dias_medio;
    }
    
    public function setId_dias_medio($id_dias_medio)
    {
        $this->id_dias_medio = $id_dias_medio;
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
    

}

?>