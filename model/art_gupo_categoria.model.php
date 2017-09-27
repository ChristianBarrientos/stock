<?php
class art_grupo_categoria {
	
	private $id_gc;
    private $id_categoria;
    
    

    public function __construct($id_gc, $id_categoria)
    {
        $this->id_gc = $id_gc;
        $this->id_categoria = $id_categoria;
    }

    public static function obtener_categoriast(){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM art_grupo_categoria");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $art_cat = array();
             
            foreach ($filas as $key => $value) {
                
                $datos_cat = art_categoria::obtener_categoria($value['id_categoria']);
                $art_cat[]= $datos_cat;
            }
            //$zona = mp_zona::obtener_zona__explicita($id_zona);
            
            return $art_cat;
        }
        else{
            return false;
        }
        
    }

    public static function alta_art_gc($id_categoria, $id_grupo_categoria = 0){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gc = art_grupo_categoria::ultimo_id_cb();
        
        $sql = "INSERT INTO `art_grupo_categoria`(`id_gc`, `id_categoria`) VALUES ($id_grupo_categoria,$id_categoria)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gc;
        }else{

            return false;
        }

    }
    public static function ultimo_id_cb(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_grupo_categoria'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
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

    public function getId_categoria()
    {
        return $this->id_categoria;
    }
    
    public function setId_categoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
        return $this;
    }

}

?>