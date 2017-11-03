<?php
class us_datos {
	
	private $id_datos;
    private $nombre;
    private $apellido;
    private $fecha_nac;
    private $dni;
    private $foto;
    private $genero;
    private $id_fecha_ab;
    


    public function __construct($id_datos, $nombre, $apellido,$fecha_nac,$dni,$foto,$genero,$id_fecha_ab)
    {
        $this->id_datos = $id_datos;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->fecha_nac = $fecha_nac;
        $this->dni = $dni;
        $this->foto = $foto;
        $this->genero = $genero;
        $this->id_fecha_ab = $id_fecha_ab;
        
        
    }

    public static function alta_datos($fecha_alta,$nombre,$apellido,$fecha_nac,$dni,$id_foto,$genero){
        global $baseDatos;
        $id_fecha_a = us_datos::alta_fecha_ab($fecha_alta);
        $id_datos = us_datos::ultimo_id_us_datos();
        
        $sql = "INSERT INTO `us_datos`(`id_datos`, `nombre`, `apellido`, `fecha_nac`, `dni`, `id_foto`, `genero`, `id_fecha_ab`) 
                VALUES (0,'$nombre','$apellido','$fecha_nac',$dni,$id_foto,'$genero','$id_fecha_a');";
        $res = $baseDatos->query($sql);
        if ($res) {
            
            return $id_datos;
        }else{

            return false;
        }
        
    }
    public static function generar_us_datos($id_datos){
          global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_datos WHERE id_datos = $id_datos");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            $us_datos = new us_datos($res_fil['id_datos'],$res_fil['nombre'],$res_fil['apellido'],$res_fil['fecha_nac'],$res_fil['dni'],$res_fil['id_foto'],$res_fil['genero'],$res_fil['id_fecha_ab']);
            return $us_datos;
        }
        else{
            return false;
        }
    }

    public static function alta_fecha_ab($alta, $baja = null){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_prvd_fecha_ab'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        $sql_fecha_ab_inser = "INSERT INTO `us_prvd_fecha_ab`(`id_fecha_ab`, `alta`, `baja`) 
                                VALUES (0,'$alta','$baja');";
        $res_inser = $baseDatos->query($sql_fecha_ab_inser);

        return $res_fil['LastId'];
    }

    public static function ultimo_id_us_datos(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_datos'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }
    

    public static function up_nombre($id_datos, $nombre){
        global $baseDatos;

        $sql = "UPDATE `us_datos` SET  `nombre`= '$nombre' WHERE id_datos = $id_datos";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    public static function up_apellido($id_datos, $apellido){
        global $baseDatos;

        $sql = "UPDATE `us_datos` SET  `apellido`= '$apellido' WHERE id_datos = $id_datos";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    public static function up_genero($id_datos, $genero){
        global $baseDatos;

        $sql = "UPDATE `us_datos` SET  `genero`= '$genero' WHERE id_datos = $id_datos";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    public static function up_dni($id_datos, $dni){
        global $baseDatos;

        $sql = "UPDATE `us_datos` SET  `dni`= $dni WHERE id_datos = $id_datos";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    public static function up_fecha_nac($id_datos, $fecha_nac){
        global $baseDatos;

        $sql = "UPDATE `us_datos` SET  `fecha_nac`= $fecha_nac WHERE id_datos = $id_datos";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    
  

	
    public function getId_datos()
    {
        return $this->id_datos;
    }
    
    public function setId_datos($id_datos)
    {
        $this->id_datos = $id_datos;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getApellido()
    {
        return $this->apellido;
    }
    
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
        return $this;
    }

    public function getFecha_nac()
    {
        return $this->fecha_nac;
    }
    
    public function setFecha_nac($fecha_nac)
    {
        $this->fecha_nac = $fecha_nac;
        return $this;
    }

    public function getDni()
    {
        return $this->dni;
    }
    
    public function setDni($dni)
    {
        $this->dni = $dni;
        return $this;
    }

    public function getFoto()
    {
        return $this->foto;
    }
    
    public function setFoto($foto)
    {
        $this->foto = $foto;
        return $this;
    }

    public function getGenero()
    {
        return $this->genero;
    }
    
    public function setGenero($genero)
    {
        $this->genero = $genero;
        return $this;
    }

    public function getFecha_alta()
    {
        return $this->fecha_alta;
    }
    
    public function setFecha_alta($fecha_alta)
    {
        $this->fecha_alta = $fecha_alta;
        return $this;
    }

    public function getFecha_baja()
    {
        return $this->fecha_baja;
    }
    
    public function setFecha_baja($fecha_baja)
    {
        $this->fecha_baja = $fecha_baja;
        return $this;
    }

}
?>