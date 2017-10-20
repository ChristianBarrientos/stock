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


    public function getId_us_prvd()
    {
        return $this->id_us_prvd;
    }
    
    public function setId_us_prvd($id_us_prvd)
    {
        $this->id_us_prvd = $id_us_prvd;
        return $this;
    }
    public function getId_usuarios()
    {
        return $this->id_usuarios;
    }
    
    public function setId_usuarios($id_usuarios)
    {
        $this->id_usuarios = $id_usuarios;
        return $this;
    }

    public function getProveedor()
    {
        return $this->proveedor;
    }
    
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
        return $this;
    }

}

?>