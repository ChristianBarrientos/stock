<?php
class art_fotos {
	
	private $id_art_fotos;
    private $id_foto;
    private $path_foto;

    public function __construct($id_art_fotos, $id_foto,$path_foto)
    {
        $this->id_art_fotos = $id_art_fotos;
        $this->id_foto = $id_foto;
        $this->path_foto = $path_foto;
    
       
    }

    public static function alta_art_foto($path_foto){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_carga = art_carga::ultimo_id_carga();
        
        $sql = "INSERT INTO `us_prvd_foto`(`id_foto`, `path_foto`) 
                VALUES (0,$path_foto)";
        $res = $baseDatos->query($sql);
        if ($res) {
              
            return $id_carga;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_carga(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_prvd_foto'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_fotos($id_art_fotos){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_fotos` WHERE id_art_fotos = $id_art_fotos");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            $fotos = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
                $path_foto = art_fotos::obtener_path_foto($valor['id_foto']);
                $fotos[] = new art_fotos($valor['id_art_fotos'],
                                         $valor['id_foto'],
                                         $path_foto);
               
            }
            return $fotos;
        }
        else{
           
            return false;
        }
    }

    public static function obtener_path_foto($id_foto){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_prvd_foto WHERE id_foto = $id_foto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            return $res_fil['path_foto'];
        }
        else{
            return false;
        }

    }


    public function getId_art_fotos()
    {
        return $this->id_art_fotos;
    }
    
    public function setId_art_fotos($id_art_fotos)
    {
        $this->id_art_fotos = $id_art_fotos;
        return $this;
    }

    public function getId_foto()
    {
        return $this->id_foto;
    }
    
    public function setId_foto($id_foto)
    {
        $this->id_foto = $id_foto;
        return $this;
    }

    public function getPath_foto()
    {
        return $this->path_foto;
    }
    
    public function setPath_foto($path_foto)
    {
        $this->path_foto = $path_foto;
        return $this;
    }

   

    

}

?>