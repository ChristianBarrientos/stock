<?php
class us_acceso {
	
	private $id_acceso;
    private $id_local;
    private $id_usuario;
    private $fecha_hora_inicio;
    private $fecha_hora_fin;
    private $id_zona;


    public function __construct($id_acceso, $id_local,$id_usuario,$fecha_hora_inicio, $fecha_hora_fin,$id_zona)
    {
        $this->id_acceso = $id_acceso;
        $this->id_local = $id_local;
        $this->id_usuario = $id_usuario;
        $this->fecha_hora_inicio = $fecha_hora_inicio;
        $this->fecha_hora_fin = $fecha_hora_fin;
        $this->id_zona = $id_zona;
    
       
    }

    public static function insert_us_acceso($id_local,$id_usuario,$fecha_hora_inicio, $fecha_hora_fin = 'null',$id_zona = null){
        global $baseDatos;
        $id_acceso = us_acceso::ultimo_id_us_acceso();
        $sql = "INSERT INTO `us_acceso` (`id_acceso`, `id_local`, `id_usuario`, `fecha_hora_inicio`, `fecha_hora_fin`, `id_zona`) 
                VALUES (0,$id_local,$id_usuario,'$fecha_hora_inicio','$fecha_hora_fin',$id_zona)";
        $res = $baseDatos->query($sql);
        if ($res) {
            echo "si";
        }else{
            echo $baseDatos->error;
            echo "no";
        }
        return $id_acceso;
         
    }

    public static function ultimo_id_us_acceso(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_acceso'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function update_fecha_hora_fina($id_acceso,$fecha_hora_fin){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `us_acceso` SET `fecha_hora_fin`='$fecha_hora_fin' WHERE id_acceso = $id_acceso");  
         
        return $res;
    }

    public static function update_id_zona($id_acceso,$id_zona){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `us_acceso` SET `id_zona`='$id_zona' WHERE id_acceso = $id_acceso");  
         
        return $res;
    }



  

    public static function generar_acceso($id_acceso){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_acceso` WHERE id_acceso = $id_acceso");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            $us_acceso = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
                $id_local = art_local::generar_local_2($valor['id_local']);
                $id_usuario = usuario::generar_usuario($valor['id_usuario']);
                if ($valor['id_zona'] != null) {
                    $id_zona = mp_zona::obtener_zona__explicita_3($valor['id_usuario']);
                    $us_acceso[] = new us_acceso($valor['id_acceso'],$id_local,$id_usuario,$valor['fecha_hora_inicio'],$valor['fecha_hora_fin'],$id_zona);
                }else{
                    $us_acceso[] = new us_acceso($valor['id_acceso'],$id_local,$id_usuario,$valor['fecha_hora_inicio'],$valor['fecha_hora_fin'],$valor['id_zona']);
                }
    
                
               
            }
            return $us_acceso;
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