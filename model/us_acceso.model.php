<?php
class us_acceso {
	
	private $id_acceso;
    private $id_local;
    private $id_usuario;
    private $fecha_hora_inicio;
    private $fecha_hora_fin;



    public function __construct($id_acceso, $id_local,$id_usuario,$fecha_hora_inicio, $fecha_hora_fin)
    {
        $this->id_acceso = $id_acceso;
        $this->id_local = $id_local;
        $this->id_usuario = $id_usuario;
        $this->fecha_hora_inicio = $fecha_hora_inicio;
        $this->fecha_hora_fin = $fecha_hora_fin;
    
       
    }

    public static function insert_us_acceso($id_local,$id_usuario,$fecha_hora_inicio, $fecha_hora_fin = 'null'){
        global $baseDatos;
        $id_acceso = us_acceso::ultimo_id_us_acceso();
        $sql = "INSERT INTO `us_acceso` (`id_acceso`, `id_local`, `id_usuario`, `fecha_hora_inicio`, `fecha_hora_fin`) 
                VALUES (0,$id_local,$id_usuario,'$fecha_hora_inicio','$fecha_hora_fin')";
        $res = $baseDatos->query($sql);
        if ($res) {
            
        }else{
            
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
  

    public static function generar_acceso($id_acceso){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_acceso` WHERE id_acceso = $id_acceso");  

        $filas = $res->fetch_assoc();
        
        if (count($filas) != 0) {
            $id_local = art_local::generar_local_2($filas['id_local']);
            $id_usuario = usuario::generar_usuario($filas['id_usuario']);
               
            $us_acceso = new us_acceso($filas['id_acceso'],$id_local,$id_usuario,$filas['fecha_hora_inicio'],$filas['fecha_hora_fin']);
           
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

    public function getId_acceso()
    {
        return $this->id_acceso;
    }
    
    public function setId_acceso($id_acceso)
    {
        $this->id_acceso = $id_acceso;
        return $this;
    }

    public function getId_local()
    {
        return $this->id_local;
    }
    
    public function setId_local($id_local)
    {
        $this->id_local = $id_local;
        return $this;
    }

    public function getUsuario()
    {
        return $this->id_usuario;
    }
    
    public function setUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        return $this;
    }

    public function getFechaHora_Inicio()
    {
        return $this->fecha_hora_inicio;
    }
    
    public function setFechaHora_Inicio($fecha_hora_inicio)
    {
        $this->fecha_hora_inicio = $fecha_hora_inicio;
        return $this;
    }

    public function getFechaHora_Fin()
    {
        return $this->fecha_hora_fin;
    }
    
    public function setFechaHora_Fin($fecha_hora_fin)
    {
        $this->fecha_hora_fin = $fecha_hora_fin;
        return $this;
    }

   

    

}

?>