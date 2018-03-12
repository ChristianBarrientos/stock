<?php
class us_prvd_foto {

	private $id_foto;
    private $path_foto;
    
 
	
    public function __construct($id_foto,$path_foto)
    {
        $this->id_foto = $id_foto;
        $this->path_foto = $path_foto;
        
        
         
        
    }

    public static function alta_foto($path_foto){
        global $baseDatos;
        $id_foto = us_prvd_foto::ultimo_id_us_prvd_foto();
        $sql = "INSERT INTO `us_prvd_foto`(`id_foto`, `path_foto`) VALUES (0,'$path_foto')";
        $res = $baseDatos->query($sql);
        return $id_foto;
    }


    

    public static function ultimo_id_us_prvd_foto(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_prvd_foto'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }


    public function getId()
    {
        return $this->id_foto;
    }
    
    public function setId($id_foto)
    {
        $this->id_foto = $id_foto;
        return $this;
    }
    public function getpath_foto()
    {
        return $this->path_foto;
    }
    
    public function setpath_foto($path_foto)
    {
        $this->path_foto = $path_foto;
        return $this;
    }



}

?>