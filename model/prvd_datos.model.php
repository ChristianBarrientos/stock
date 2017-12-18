<?php
class prvd_datos {
	
	private $id_datos_prvd;
    private $nombre;
    private $cuit;
    private $fecha_alta;
    private $fecha_baja;
    private $id_foto;
    

    public function __construct($id_datos_prvd, $nombre, $cuit,$id_fecha_alta,$id_fecha_baja,$id_foto )
    {
        $this->id_datos_prvd = $id_datos_prvd;
        $this->nombre = $nombre;
        $this->cuit = $cuit;
        $this->id_fecha_alta = $id_fecha_alta;
        $this->id_fecha_baja = $id_fecha_baja;
        $this->id_foto = $id_foto;

    }

    public static function alta_datos($fecha_alta, $nombre, $cuit, $id_foto){
        global $baseDatos;
        $id_fecha_a = prvd_datos::alta_fecha_ab($fecha_alta);
        $id_datos = prvd_datos::ultimo_id_us_datos();
         
        $sql = "INSERT INTO `prvd_datos`(`id_datos_prvd`, `nombre`,  `cuit`, `id_foto`,  `id_fecha_ab`) 
                VALUES (0,'$nombre',$cuit,$id_foto,$id_fecha_a);";
        $res = $baseDatos->query($sql);
        if ($res) {
            
            return $id_datos;
        }else{

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
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='prvd_datos'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }


    public static function update($id_datos_prvd,$fila,$valor_nuevo){
        //obtener empleados por local
        global $baseDatos;
        //`id_fecha_ab`=[value-4],`id_foto`=[value-5]
        switch ($fila) {
            case 'nombre':
                # code...
                $res = $baseDatos->query(" UPDATE `prvd_datos` SET `nombre`='$valor_nuevo' WHERE id_datos_prvd = $id_datos_prvd");  
                break;
            case 'cuit':
                # code...
                $res = $baseDatos->query(" UPDATE `prvd_datos` SET `cuit`='$valor_nuevo' WHERE id_datos_prvd = $id_datos_prvd");  
                break;

            default:
                # code...
                break;
        }
        
         
        return $res;
    }


    public function getId_datos_prvd()
    {
        return $this->id_datos_prvd;
    }
    
    public function setId_datos_prvd($id_datos_prvd)
    {
        $this->id_datos_prvd = $id_datos_prvd;
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

    public function getCuit()
    {
        return $this->cuit;
    }
    
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;
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

    public function getId_foto()
    {
        return $this->id_foto;
    }
    
    public function setId_foto($id_foto)
    {
        $this->id_foto = $id_foto;
        return $this;
    }
}
?>