<?php
class articulo {
	
	private $id_articulo;
    private $nombre;
    private $id_marca;

    public function __construct($id_articulo, $nombre, $id_marca = null)
    {
        $this->id_articulo = $id_articulo;
        $this->nombre = $nombre;
        $this->id_marca = $id_marca;
    
       
    }


    public static function alta_art_general($nombre,$des = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_articulo_ = articulo::ultimo_id_articulo();
        
        $sql = "INSERT INTO `art_articulo`(`id_articulo`, `nombre`, `descripcion`) VALUES (0,'$nombre','$des')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_articulo_;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_articulo(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_articulo'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function obtener_articulos(){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM art_articulo");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $art_nombres = array();
             
            foreach ($filas as $key => $value) {
                 
                $art_nombres[]= new articulo($value['id_articulo'],$value['nombre'] );
            }
            //$zona = mp_zona::obtener_zona__explicita($id_zona);
            
            return $art_nombres;
        }
        else{
            return false;
        }
        
    }

    public static function generar_articulo($id_articulo){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_articulo` WHERE id_articulo = $id_articulo");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
             
            $articulo = new articulo($res_fil['id_articulo'],$res_fil['nombre'],$res_fil['descripcion']);
            return $articulo;
        }
        else{
            
            return false;
        }
    }



    public function getId_articulo()
    {
        return $this->id_articulo;
    }
    
    public function setId_articulo($id_articulo)
    {
        $this->id_articulo = $id_articulo;
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

    public function getId_marca()
    {
        return $this->id_marca;
    }
    
    public function setId_marca($id_marca)
    {
        $this->id_marca = $id_marca;
        return $this;
    }
}