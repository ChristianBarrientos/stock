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