<?php
class us_sueldos {
	
	private $id_sueldo;
    private $id_usuario;
    private $id_gmv;
    private $basico;
    private $aguinaldo;

    public function __construct($id_sueldo, $id_usuario,$id_gmv,$basico,$aguinaldo)
    {
        $this->id_sueldo = $id_sueldo;
        $this->id_usuario = $id_usuario;
        $this->id_gmv = $id_gmv;
        $this->basico = $basico;
        $this->aguinaldo = $aguinaldo;
    
       
    }

    public static function alta($id_usuario,$id_gmv,$basico,$aguinaldo){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_sueldo = us_sueldos::ultimo_id();
        
        $sql = "INSERT INTO `us_sueldos`(`id_sueldo`, `id_usuario`, `id_gmv`, `basico`, `aguinaldo`) VALUES (0,$id_usuario,$id_gmv,$basico,$aguinaldo)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_sueldo;
        }else{
             
            return false;
        }

    }

    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_sueldos'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function agregar($id_sueldo,$id_usuario){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        
        
        $sql = "INSERT INTO `us_sueldos`(`id_sueldo`, `id_usuario`) VALUES ($id_sueldo,$id_usuario)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_sueldo;
        }else{
             
            return false;
        }

    }

          
    public static function generar($id_sueldo){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_sueldos` WHERE id_sueldo = $id_sueldo");  
         
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            $id_gmv = us_gmv::generar($res_fil['id_gmv']);
            
            $us_sueldo = new us_sueldos($res_fil['id_sueldo'],$res_fil['id_usuario'],$id_gmv,$res_fil['basico'],$res_fil['aguinaldo']);
            return $us_sueldo;
        }
        else{
            
            return false;
        }
       
    }

    public static function obtener(){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_sueldos`");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $us_sueldos = array();

            foreach ($filas as $clave => $valor) {
                $id_gmv = us_gmv::generar($valor['id_gmv']);
                $id_usuario = usuario::generar_usuario($valor['id_usuario']);
                $us_sueldos [] = new us_sueldos($valor['id_sueldo'],$id_usuario,$id_gmv,$valor['basico'],$valor['aguinaldo']);
            }

            return $us_sueldos;
        }
        else{
            return false;
        }
       
    }

    public static function update($id_sueldo, $columna, $nuevo_valor){
        //obtener empleados por local
        global $baseDatos;

        $res = $baseDatos->query(" UPDATE `us_sueldos` SET `$columna`='$nuevo_valor' WHERE id_sueldo = $id_sueldo");  
         
        return $res;
    }


    public function getId()
    {
        return $this->id_sueldo;
    }
    
    public function setId($id_sueldo)
    {
        $this->id_sueldo = $id_sueldo;
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

    public function getId_gmv()
    {
        return $this->id_gmv;
    }
    
    public function setId_gmv($id_gmv)
    {
        $this->id_gmv = $id_gmv;
        return $this;
    }

    public function getBasico()
    {
        return $this->basico;
    }
    
    public function setBasico($basico)
    {
        $this->basico = $basico;
        return $this;
    }

    public function getAguinaldo()
    {
        return $this->aguinaldo;
    }
    
    public function setAguinaldo($aguinaldo)
    {
        $this->aguinaldo = $aguinaldo;
        return $this;
    }

   

    

}

?>