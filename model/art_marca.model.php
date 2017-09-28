<?php
class art_marca {
	
	private $id_marca;
    private $nombre;
    private $descripcion;
    private $id_lote;

    public function __construct($id_marca, $nombre, $descripcion,$id_lote)
    {
        $this->id_marca = $id_marca;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id_lote = $id_lote;
    
       
    }

        public static function obtener_marcas(){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM art_marca");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $art_marcas = array();
             
            foreach ($filas as $key => $value) {
                
                $art_marcas[]= new art_marca($value['id_marca'],$value['nombre'],$value['descripcion'],$value['id_lote']);
            }
            //$zona = mp_zona::obtener_zona__explicita($id_zona);
            
            return $art_marcas;
        }
        else{
            return false;
        }
        
    }

    public static function alta_art_marca($nombre,$descripcion = 'null',$id_lote = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_marca_ = art_marca::ultimo_id_marca();
        
        $sql = "INSERT INTO `art_marca`(`id_marca`, `nombre`, `descripcion`, `id_lote`) 
                VALUES (0,'$nombre','$descripcion',$id_lote)";
        $res = $baseDatos->query($sql);
        if ($res) {
           
            return $id_marca_;
        }else{

            return false;
        }

    }
    public static function ultimo_id_marca(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_marca'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_marca($id_marca){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_marca` WHERE id_marca = $id_marca");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $marca = new art_marca($res_fil['id_marca'],$res_fil['nombre'] );
            return $marca;
        }
        else{
            
            return false;
        }
    }


    public function getId_marca()
    {
        return $this->id_marca;
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

    public function getId_lote()
    {
        return $this->id_lote;
    }
    
    public function setId_lote($id_lote)
    {
        $this->id_lote = $id_lote;
        return $this;
    }
}
?>