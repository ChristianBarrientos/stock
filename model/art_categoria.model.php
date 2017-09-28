<?php
class art_categoria {
	
	private $id_categoria;
    private $nombre;
    private $valor;
    private $descripcion;
    

    public function __construct($id_categoria, $nombre, $valor,$descripcion)
    {
        $this->id_categoria = $id_categoria;
        $this->nombre = $nombre;
        $this->valor = $valor;
        $this->descripcion = $descripcion;
       
    
       
    }

    public static function obtener_categoria($id_categoria){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_categoria WHERE id_categoria = $id_categoria");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $cat = new art_categoria($res_fil['id_categoria'],$res_fil['nombre'],$res_fil['valor'],$res_fil['descripcion'] );
            return $cat;
        }
        else{
            return false;
        }
    }

    public static function alta_art_categoria($nombre,$valor,$descripcion = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_categoria = art_categoria::ultimo_id_categoria();
        
        $sql = "INSERT INTO `art_categoria`(`id_categoria`, `nombre`, `valor`, `descripcion`) VALUES (0,'$nombre','$valor','$descripcion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_categoria;
        }else{

            return false;
        }

    }
    public static function ultimo_id_categoria(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_categoria'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_categoria($id_categoria){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_categoria` WHERE id_categoria = $id_categoria");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $categoria = new art_categoria($res_fil['id_categoria'],$res_fil['nombre'],$res_fil['valor'],$res_fil['$descripcion']);
            return $categoria;
        }
        else{
            
            return false;
        }
    }

    public function getId_categoria()
    {
        return $this->id_categoria;
    }
    
    public function setId_categoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
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

    public function getValor()
    {
        return $this->valor;
    }
    
    public function setValor($valor)
    {
        $this->valor = $valor;
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