<?php
class us_prvd {

	private $id_us_prvd;
    private $id_usuarios;
    private $proveedor;
 
	
    public function __construct($id_us_prvd,$id_usuarios, $proveedor)
    {
        $this->id_us_prvd = $id_us_prvd;
        $this->id_usuarios = $id_usuarios;
        $this->proveedor = $proveedor;
        
         
        
    }

    public static function agregar_prvd_a_us($id_prvd,$id_usuarios){
        global $baseDatos;
        
        $sql = "INSERT INTO `us_prvd`(`id_us_prvd`, `id_usuarios`, `id_provedor`) 
                VALUES (0,$id_usuarios,$id_prvd)";
        $res = $baseDatos->query($sql);
        return $res;
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