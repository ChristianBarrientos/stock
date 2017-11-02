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

    public static function alta_art_conjunto($nombre,$descripcion, $descuento){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio = art_conjunto::ultimo_id_conjunto();
        
        $sql = "INSERT INTO `art_conjunto`(`id_art_conjunto`, `nombre`, `descripcion`, `id_tipo`) VALUES (0,$nombre,$descripcion,$id_tipo)";
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

    public static function generar_conjunto($id_art_conjunto){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_conjunto` WHERE id_art_conjunto = $id_art_conjunto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $articulo = articulo::generar_articulo($res_fil['nombre']);   
            $marca = art_marca::generar_marca($res_fil['descripcion']);  
            $tipo = art_tipo::generar_tipo($res_fil['id_tipo']);  
            //$lote = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            $conjunto = new art_conjunto($res_fil['id_art_conjunto'],$articulo,$marca,$tipo);
            
            return $conjunto;
        }
        else{
            
            return false;
        }
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