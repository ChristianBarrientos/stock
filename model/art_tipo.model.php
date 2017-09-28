<?php
class art_tipo {
	
	private $id;
    private $id_marca;
    private $nombre;
    private $descripcion;
 
    //$id_marca,
    public function __construct($id, $nombre, $descripcion)
    {
        $this->id = $id;
        //$this->id_marca = $id_marca;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
         
       
    }

        public static function obtener_tipos(){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM art_tipo");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $art_tipos = array();
             
            foreach ($filas as $key => $value) {
                
                $art_tipos[]= new art_tipo($value['id_tipo'],$value['id_marca'],$value['nombre'],$value['descripcion']);
            }
            //$zona = mp_zona::obtener_zona__explicita($id_zona);
            
            return $art_tipos;
        }
        else{
            return false;
        }
        
    }

    public static function alta_art_tipo($nombre,$descripcion = 'null', $id_marca = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_tipo_ = art_tipo::ultimo_id_tipo();
        
        $sql = "INSERT INTO `art_tipo`(`id_tipo`, `nombre`, `descripcion`, `id_marca`) VALUES (0,'$nombre','$descripcion',$id_marca)";
        $res = $baseDatos->query($sql);
        if ($res) {
            echo $id_tipo_;
            return $id_tipo_;
        }else{

            return false;
        }

    }
    public static function ultimo_id_tipo(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_tipo'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_tipo($id_tipo){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_tipo` WHERE id_tipo = $id_tipo");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $tipo = new art_tipo($res_fil['id_tipo'],$res_fil['nombre'],'null' );
            return $tipo;
        }
        else{
            
            return false;
        }
    }


    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId_marca()
    {
        return $this->id;
    }
    
    public function setId_marca($id_marca)
    {
        $this->id_marca = $id_marca;
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