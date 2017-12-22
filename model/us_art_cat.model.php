<?php
class us_art_cat {
	
	private $id_us_art_cat;
    private $nombre;
    private $descripcion;
    private $id_gc;

    public function __construct($id_us_art_cat, $nombre,$descripcion,$id_gc)
    {
        $this->id_us_art_cat = $id_us_art_cat;
        $this->nombre = $nombre;
        $this->id_gc = $id_gc;
        $this->descripcion = $descripcion;
    
       
    }

    public static function alta($nombre,$descripcion,$id_gc){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_us_art_cat = us_art_cat::ultimo_id();
        
        $sql = "INSERT INTO `us_art_cat`(`id_us_art_cat`, `nombre`, `descripcion`, `id_gc`) VALUES (0,'$nombre','$descripcion',$id_gc)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_us_art_cat;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_art_cat'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_us_art_cat){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_art_cat` WHERE id_us_art_cat = $id_us_art_cat");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            $id_gc = art_grupo_categoria::generar_gc($res_fil['id_gc']);

            $us_art_cat = new us_art_cat($res_fil['id_us_art_cat'],$res_fil['nombre'],$res_fil['descripcion'],$id_gc);
            return $us_art_cat;
        }
        else{
            
            return false;
        }
       


    }

    public static function obtener(){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_art_cat`");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            return $res_fil;
        }
        else{
            
            return false;
        }
    }

    public function getId()
    {
        return $this->id_us_art_cat;
    }
    
    public function setId($id_us_art_cat)
    {
        $this->id_us_art_cat = $id_us_art_cat;
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

   public function getId_gc()
   {
       return $this->id_gc;
   }
   
   public function setId_gc($id_gc)
   {
       $this->id_gc = $id_gc;
       return $this;
   }

    

}

?>