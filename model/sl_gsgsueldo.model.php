<?php
class sl_gsgsueldo {
	
	private $id_gs_gsueldo;
    private $id_gasto_unico;
  
    public function __construct($id_gs_gsueldo, $id_gasto_unico)
    {
        $this->id_gs_gsueldo = $id_gs_gsueldo;
        $this->id_gasto_unico = $id_gasto_unico;
    }

    public static function alta($id_gasto_unico){
        global $baseDatos;
        
        $id_gs_gsueldo = sl_gsgsueldo::ultimo_id();
        
        $sql = "INSERT INTO `sl_gsgsueldo`(`id_gs_gsueldo`, `id_gasto_unico`) VALUES (0,$id_gasto_unico)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gs_gsueldo;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='sl_gsgsueldo'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_gs_gsueldo){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `sl_gsgsueldo` WHERE id_gs_gsueldo = $id_gs_gsueldo");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $gs_unicos = array();

            foreach ($filas as $clave => $valor) {
            

                $gs_unicos [] = gs_gasto_unico::generar($valor['id_gasto_unico']);
            }
            
            $sl_gsgsueldo = new sl_gsgsueldo($valor['id_gs_gsueldo'],$gs_unicos);
            return $sl_gsgsueldo;
        }
        else{
            return false;
        }
    }

    public static function obtener(){

        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `sl_gsgsueldo`");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $sl_gsgsueldo = array();

            foreach ($filas as $clave => $valor) {
                $sl_gsgsueldo [] = new sl_gsgsueldo($valor['id_gs_gsueldo'],$valor['id_gasto_unico'],$valor['fecha_hora']);
            }

            return $sl_gsgsueldo;
        }
        else{
            return false;
        }
    }
    

    public function getId()
    {
        return $this->id_gs_gsueldo;
    }
    
    public function setId($id_gs_gsueldo)
    {
        $this->id_gs_gsueldo = $id_gs_gsueldo;
        return $this;
    }

    public function getId_gs_gsueldo()
    {
        return $this->id_gasto_unico;
    }
    
    public function setId_gs_gsueldo($id_gasto_unico)
    {
        $this->id_gasto_unico = $id_gasto_unico;
        return $this;
    }

    public function getFecha_hora()
    {
        return $this->fecha_hora;
    }
    
    public function setFecha_hora($fecha_hora)
    {
        $this->fecha_hora = $fecha_hora;
        return $this;
    }
   

    

}

?>