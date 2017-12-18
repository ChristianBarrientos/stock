<?php
class us_prvd_contacto {

	private $id_contacto;
    private $direccion;
    private $correo;
    private $nro_caracteristica;
    private $nro_telefono;
    


    public function __construct($id_contacto, $direccion, $correo,$nro_caracteristica = null,$nro_telefono = null)
    {
        $this->id_contacto = $id_contacto;
        $this->direccion = $direccion;
        $this->correo = $correo;
        $this->nro_caracteristica = $nro_caracteristica;
        $this->nro_telefono = $nro_telefono;
       
        
        
    }

    public static function generar_us_contacto($id_contacto){
          global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_prvd_contacto WHERE id_contacto = $id_contacto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            $us_prvd_contacto = new us_prvd_contacto($res_fil['id_contacto'],$res_fil['direccion'],$res_fil['correo']);
            return $us_prvd_contacto;
        }
        else{
            return false;
        }
    }

    public static function alta_contacto($direccion,$correo,$telefono){
        global $baseDatos;
        
        $id_contacto_tel = us_prvd_contacto::alta_contacto_tel($telefono);
        
        $id_contacto = us_prvd_contacto::ultimo_id_us_contacto();

        $sql = "INSERT INTO `us_prvd_contacto`(`id_contacto`, `id_contacto_tel`, `direccion`, `correo`) 
                VALUES (0,$id_contacto_tel,'$direccion','$correo')";
        $res = $baseDatos->query($sql);
        if ($res) {
            return $id_contacto;
        }else{

            return false;
        }
        
    }

     public static function alta_contacto_tel($telefono, $caracteristica = 0000){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_prvd_contacto_tel'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        $sql_fecha_ab_inser = "INSERT INTO `us_prvd_contacto_tel`(`id_contacto_tel`, `nro_caracteristica`, `nro_telefono`)                   VALUES (0,$caracteristica,$telefono);";
        $res_inser = $baseDatos->query($sql_fecha_ab_inser);
        return $res_fil['LastId'];
    }

    public static function ultimo_id_us_contacto(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_prvd_contacto'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function up_direccion($id_contacto, $direccion){
        global $baseDatos;
        

        $sql = "UPDATE `us_prvd_contacto` SET `direccion`= '$direccion' WHERE id_contacto = $id_contacto";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    public static function up_correo($id_contacto, $correo){
        global $baseDatos;
        

        $sql = "UPDATE `us_prvd_contacto` SET `correo`= '$correo' WHERE id_contacto = $id_contacto";
        $res = $baseDatos->query($sql);
        return $res;   
    }


    public static function update($id_contacto,$fila,$valor_nuevo){
        //obtener empleados por local
        global $baseDatos;
        //`id_fecha_ab`=[value-4],`id_foto`=[value-5]
        switch ($fila) {
            case 'direccion':
                # code...
                $res = $baseDatos->query(" UPDATE `us_prvd_contacto` SET `direccion`='$valor_nuevo' WHERE id_contacto = $id_contacto");  
                break;
            case 'correo':
                # code...
                $res = $baseDatos->query(" UPDATE `us_prvd_contacto` SET `correo`='$valor_nuevo' WHERE id_contacto = $id_contacto");  
                break;

            case 'telefono':
                # code...
                $res = $baseDatos->query(" UPDATE `us_prvd_contacto_tel` SET `nro_telefono`='$valor_nuevo' WHERE id_contacto_tel = $id_contacto");  
                break;

            default:
                # code...
                break;
        }
        
         
        return $res;
    }

    public function getId_contacto()
    {
        return $this->id_contacto;
    }
    
    public function setId_contacto($id_contacto)
    {
        $this->id_contacto = $id_contacto;
        return $this;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }
    
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
        return $this;
    }

    public function getCorreo()
    {
        return $this->correo;
    }
    
    public function setCorreo($correo)
    {
        $this->correo = $correo;
        return $this;
    }

    public function getNro_caracteristica()
    {
        return $this->nro_caracteristica;
    }
    
    public function setNro_caracteristica($nro_caracteristica)
    {
        $this->nro_caracteristica = $nro_caracteristica;
        return $this;
    }

    public function getNro_telefono()
    {
        return $this->nro_telefono;
    }
    
    public function setNro_telefono($nro_telefono)
    {
        $this->nro_telefono = $nro_telefono;
        return $this;
    }

   

}

?>