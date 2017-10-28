<?php
class proveedor {
	
	private $id_proveedor;
    private $id_contacto;
    private $id_datos_prvd;
    private $descripcion;
    

    public function __construct($id_proveedor, $id_contacto, $id_datos_prvd,$descripcion )
    {
        $this->id_proveedor = $id_proveedor;
        $this->id_contacto = $id_contacto;
        $this->id_datos_prvd = $id_datos_prvd;
        $this->descripcion = $descripcion;
    
       
    }

    public static function alta_prvd($id_datos_prvd,$id_contacto,$descripcion){
        global $baseDatos;
       
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_prvd = proveedor::ultimo_id_usuarios();
        $sql = "INSERT INTO `prvd_provedor`(`id_provedor`, `id_contacto`, `id_datos_prvd`,`descripcion`) 
                VALUES (0, $id_contacto, $id_datos_prvd,'$descripcion')";
        $res = $baseDatos->query($sql);
        return $id_prvd;

    }
    public static function ultimo_id_usuarios(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='prvd_provedor'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function obtener_prvd($id_user){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM us_prvd WHERE id_usuarios = $id_user");  
       
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           
            $prvds = array();
             
            foreach ($filas as $key => $value) {
                
                $prvds[]= proveedor::generar_prvd($value['id_provedor']);
            }
            //$zona = mp_zona::obtener_zona__explicita($id_zona);
            
            return $prvds;
        }
        else{
            
            return false;
        }
    }

    public static function generar_prvd($id_prvd){
        global $baseDatos;
        

        $res = $baseDatos->query("SELECT * FROM prvd_provedor WHERE id_provedor = $id_prvd");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $id_contacto = proveedor::obtener_us_prvd_contacto($res_fil['id_contacto']);
            $id_datos_prvd = proveedor::obtener_us_prvd_datos($res_fil['id_datos_prvd']);
            $prvd = new proveedor($res_fil['id_provedor'],$id_contacto,$id_datos_prvd,$res_fil['descripcion']);
            return $prvd;
        }
        else{
            return false;
        }
    }

    public static function obtener_us_prvd_datos($id_datos_prvd = null){
        global $baseDatos;
         if ($id_datos_prvd == null) {
             $id_datos_prvd = $this->getid_datos_prvd();
        } 
       

        $res = $baseDatos->query("SELECT * FROM prvd_datos WHERE id_datos_prvd = $id_datos_prvd");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $prvd_fecha_ab = proveedor::obtener_prvd_datos_fecha_ab($res_fil['id_fecha_ab']);
            $prvd_id_foto = proveedor::obtener_us_datos_foto($res_fil['id_foto']);

            $prvd_contacto = new prvd_datos($res_fil['id_datos_prvd'], $res_fil['nombre'],$res_fil['cuit'],$prvd_fecha_ab['alta'],$prvd_fecha_ab['baja'],$prvd_id_foto);
             
            return $prvd_contacto;
        }
        else{
            return false;
        }

    }

    public static function obtener_us_datos_foto($id_foto){
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

    public static function obtener_prvd_datos_fecha_ab($id_fecha_ab){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_prvd_fecha_ab WHERE id_fecha_ab = $id_fecha_ab");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $fecha_ab = array('alta'  => $res_fil['alta'],'baja' => $res_fil['baja']);
            return $fecha_ab;
        }
        else{
            return false;
        }

    }

    public static function obtener_us_prvd_contacto($id_contacto = null){
        global $baseDatos;
         if ($id_contacto == null) {
             $id_contacto = $this->getId_contacto();
        } 
       

        $res = $baseDatos->query("SELECT * FROM us_prvd_contacto WHERE id_contacto = $id_contacto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $us_prvd_contacto_tel = proveedor::obtener_us_prvd_contacto_tel($res_fil['id_contacto_tel']);
           
            $prvd_contacto = new us_prvd_contacto($res_fil['id_contacto'], $res_fil['direccion'],$res_fil['correo'],$us_prvd_contacto_tel['nro_caracteristica'],$us_prvd_contacto_tel['nro_telefono']);
             
            return $prvd_contacto;
        }
        else{
            return false;
        }
    }

    public static function obtener_us_prvd_contacto_tel($id_contacto_tel){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_prvd_contacto_tel WHERE id_contacto_tel = $id_contacto_tel");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $telefono = array('nro_caracteristica'  => $res_fil['nro_caracteristica'],'nro_telefono' => $res_fil['nro_telefono']);
            return $telefono;
        }
        else{
            return false;
        }
    }

    public function getId_proveedor()
    {
        return $this->id_proveedor;
    }
    
    public function setId_proveedor($id_proveedor)
    {
        $this->id_proveedor = $id_proveedor;
        return $this;
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

    public function getid_datos_prvd()
    {
        return $this->id_datos_prvd;
    }
    
    public function setid_datos_prvd($id_datos_prvd)
    {
        $this->id_datos_prvd = $id_datos_prvd;
        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

}
?>