 <?php
class art_conjunto {
	
	private $id_conjunto;
    private $id_articulo;
    private $id_marca;
    private $id_tipo;

    public function __construct($id_conjunto, $id_articulo, $id_marca,$id_tipo)
    {
        $this->id_conjunto = $id_conjunto;
        $this->id_articulo = $id_articulo;
        $this->id_marca = $id_marca;
        $this->id_tipo = $id_tipo;
    
       
    }

    public static function alta_art_conjunto($id_articulo,$id_marca, $id_tipo){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_conjunto = art_conjunto::ultimo_id_conjunto();
        
        $sql = "INSERT INTO `art_conjunto`(`id_art_conjunto`, `id_articulo`, `id_marca`, `id_tipo`) VALUES (0,$id_articulo,$id_marca,$id_tipo)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_conjunto;
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
            $articulo = articulo::generar_articulo($res_fil['id_articulo']);   
            $marca = art_marca::generar_marca($res_fil['id_marca']);  
            $tipo = art_tipo::generar_tipo($res_fil['id_tipo']);  
            //$lote = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            $conjunto = new art_conjunto($res_fil['id_art_conjunto'],$articulo,$marca,$tipo);
            return $conjunto;
        }
        else{
            
            return false;
        }
    }



    public function getId_conjunto()
    {
        return $this->id_conjunto;
    }
    
    public function setId_conjunto($id_conjunto)
    {
        $this->id_conjunto = $id_conjunto;
        return $this;
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

    public function getId_marca()
    {
        return $this->id_marca;
    }
    
    public function setId_marca($id_marca)
    {
        $this->id_marca = $id_marca;
        return $this;
    }

    public function getId_tipo()
    {
        return $this->id_tipo;
    }
    
    public function setId_tipo($id_tipo)
    {
        $this->id_tipo = $id_tipo;
        return $this;
    }

    

}

?>