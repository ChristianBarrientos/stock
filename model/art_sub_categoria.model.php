<?php
class art_sub_categoria {
	
	private $id_sub_categoria;
    private $nombre;
    private $valor;
    private $descripcion;

    public function __construct($id_sub_categoria, $nombre, $valor,$descripcion)
    {
        $this->id_sub_categoria = $id_sub_categoria;
        $this->nombre = $nombre;
        $this->valor = $valor;
        $this->descripcion = $descripcion;
    
       
    }

    public static function obtener_sub_categoria($id_sub_categoria){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_sub_categoria WHERE id_sub_categoria = $id_sub_categoria");  

        $res_fil = $res->fetch_assoc();

        if (count($res_fil) != 0) {
            
            
            $sub_cat = new art_sub_categoria($res_fil['id_sub_categoria'],$res_fil['nombre'],$res_fil['valor'],$res_fil['descripcion']);
            
            return $sub_cat;
        }
        else{
            return false;
        }
    }


    public function getId_sub_categoria()
    {
        return $this->id_sub_categoria;
    }
    
    public function setId_sub_categoria($id_sub_categoria)
    {
        $this->id_sub_categoria = $id_sub_categoria;
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