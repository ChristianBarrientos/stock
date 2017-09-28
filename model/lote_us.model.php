<?php
class lote_us {
	
	private $id_lote_us;
    private $id_usuario;
    private $id_lote;
   

    public function __construct($id_lote_us, $id_usuario, $id_lote)
    {
        $this->id_lote_us = $id_lote_us;
        $this->id_usuario = $id_usuario;
        $this->id_lote = $id_lote;
        
    }

   
    public static function ultimo_id_lote_us(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='lote_us'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function obtener_lotes($user){
        global $baseDatos;
        
        $id_user = $user->getId_user();

        $res = $baseDatos->query("SELECT * FROM art_carga WHERE id_usuario = $id_user");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            $lotes = array();
            $locales_empleados = array();
            $locales_articulos = array();
            
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
                $locales_empleados[] = art_local::generar_local_empleados($valor['id_zona']);
                $locales [] = art_local::generar_local($valor['id_zona'],count($locales_empleados[$clave]));
                $locales_articulos [] = $locales[$clave]->getId_local();

                //$lotes[] = 
                
            }
            //$res_fil['id_zona'];
            
            $_SESSION["lotes"] = proveedor::obtener_prvd($id_user);
             
            return true;
        }
        else{
            return false;
        }
    }


    public function getId_lote()
    {
        return $this->id_lote;
    }
    
    public function setId_lote($id_lote)
    {
        $this->id_lote = $id_lote;
        return $this;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }
    
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        return $this;
    }

    public function getId_lote_usuario()
    {
        return $this->id_lote_us;
    }
    
    public function setId_lote_usuario($id_lote_us)
    {
        $this->id_lote_us = $id_lote_usuario;
        return $this;
    }

    

}
?>