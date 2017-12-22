
<?php
class us_art_gcat {
	
	private $id_us_gcat;
    private $id_us_art_cat;
    private $id_usuario;
   

    public function __construct($id_us_gcat, $id_us_art_cat,$id_usuario)
    {
        $this->id_us_gcat = $id_us_gcat;
        $this->id_us_art_cat = $id_us_art_cat;
        $this->id_usuario = $id_usuario;
    
    
       
    }

    public static function alta($id_us_art_cat,$id_usuario){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_us_gcat = us_art_gcat::ultimo_id();
        
        $sql = "INSERT INTO `us_art_gcat`(`id_us_gcat`, `id_us_art_cat`, `id_usuario`) VALUES (0,$id_us_art_cat,$id_usuario)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_us_gcat;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_art_gcat'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_usuario){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_art_gcat` WHERE id_usuario = $id_usuario");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $art_gcat = array();

            foreach ($filas as $clave => $valor) {

                $id_us_art_cat = us_art_cat::generar($valor['id_us_art_cat']);
                
               
                $art_gcat[] = $id_us_art_cat;

            }
          
            $us_art_gcat = new us_art_gcat($valor['us_art_gcat'],$art_gcat,$valor['id_usuario']);
            return $us_art_gcat;
        }
        else{
           
            return false;
        }
        
    }

     public static function agrega($id_ggs,$id_us_art_cat){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        
        
        $sql = "INSERT INTO `gs_grupo`(`id_ggs`, `id_us_art_cat`) VALUES ($id_ggs,$id_us_art_cat)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return true;
        }else{
             
            return false;
        }

    }

    public function getId()
    {
        return $this->id_us_gcat;
    }
    
    public function setId($id_us_gcat)
    {
        $this->id_us_gcat = $id_us_gcat;
        return $this;
    }

    public function getId_us_art_cat()
    {
        return $this->id_us_art_cat;
    }
    
    public function setId_us_art_cat($id_us_art_cat)
    {
        $this->id_us_art_cat = $id_us_art_cat;
        return $this; 
    }
   

    

}

?>