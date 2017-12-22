<?php
class art_venta_medio_pago {
	
	private $id_medio_pago;
    private $nombre;
    private $id_medio_tipo;
    private $id_des_imp;
    private $id_medio_fecha;
    private $id_medio_dias;
    private $id_gart_aplica;

    public function __construct($id_medio_pago, $nombre, $id_medio_tipo,$id_des_imp,$id_medio_fecha,$id_medio_dias,$id_gart_aplica = null)
    {
        $this->id_medio_pago = $id_medio_pago;
        $this->nombre = $nombre;
        $this->id_medio_tipo = $id_medio_tipo;
        $this->id_des_imp = $id_des_imp;
        $this->id_medio_fecha = $id_medio_fecha;
        $this->id_medio_dias = $id_medio_dias;
        $this->id_gart_aplica = $id_gart_aplica;
   
    
       
    }

    public static function alta($nombre,$id_medio_tipo,$id_des_imp,$id_medio_dias,$id_medio_fecha='null',$id_gart_aplica = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio_pago = art_venta_medio_pago::ultimo_id();
        
        $sql = "INSERT INTO `art_venta_medio_pago`(`id_medio_pago`, `nombre`, `id_medio_tipo`, `id_des_imp`, `id_medio_dias`, `id_medio_fecha`, `id_gart_aplica`) VALUES (0,'$nombre',$id_medio_tipo,$id_des_imp,$id_medio_dias,$id_medio_fecha,$id_gart_aplica)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_medio_pago;
        }else{
            
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_medio_pago'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_medio_pago){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_venta_medio_pago` WHERE id_medio_pago = $id_medio_pago");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {

            $medio_tipo = art_venta_medio_tipo::generar($res_fil['id_medio_tipo']);
            $imp = art_venta_des_imp::generar($res_fil['id_des_imp']);

            if ($res_fil['id_medio_fecha'] != null) {
                # code...
                $medio_fechas = art_venta_medio_promo_fechas::generar($res_fil['id_medio_fecha']);
            }else{
                $medio_fechas = $res_fil['id_medio_fecha'];
            }
            
            if ($res_fil['id_medio_dias'] != null) {
                # code...
                $medio_dias = art_venta_medio_promo_dias::generar($res_fil['id_medio_dias']);
            }else{
                $medio_dias = $res_fil['id_medio_dias'];
            }
            
          
            $venta_medio = new art_venta_medio_pago($res_fil['id_medio_pago'],$res_fil['nombre'],$medio_tipo,$imp,$medio_fechas,$medio_dias,$res_fil['id_gart_aplica']);
            
            return $venta_medio;
        }
        else{
            
            return false;
        }
    }

    public static function obtener($id_usuario){
        global $baseDatos;
       
        $res = $baseDatos->query("SELECT * FROM us_medio_pago WHERE id_usuario = $id_usuario");  
       
        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $medios = array();
             
            foreach ($filas as $key => $value) {
                
                $medios[]= art_venta_medio_pago::generar($value['id_medio_pago']);
            }
            
            return $medios;
        }
        else{
            return false;
        }
        
    }

    public static function update_nombre($id_medio,$nombre){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio` SET `nombre`='$nombre' WHERE id_medio = $id_medio");  
         
        return $res;
    }

    public static function update_descripcion($id_medio,$descripcion){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio` SET `descripcion`='$descripcion' WHERE id_medio = $id_medio");  
         
        return $res;
    }

    public static function update_descuento($id_medio,$descuento){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta_medio` SET `descuento`='$descuento' WHERE id_medio = $id_medio");  
         
        return $res;
    }

    



    public function getId()
    {
        return $this->id_medio_pago;
    }
    
    public function setId($id_medio_pago)
    {
        $this->id_medio_pago = $id_medio_pago;
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

    public function getId_medio_tipo()
    {
        return $this->id_medio_tipo;
    }
    
    public function setId_medio_tipo($id_medio_tipo)
    {
        $this->id_medio_tipo = $id_medio_tipo;
        return $this;
    }

    public function getDesImp()
    {
        return $this->id_des_imp;
    }
    
    public function setIddesimp($id_des_imp)
    {
        $this->id_des_imp = $id_des_imp;
        return $this;
    }
    public function getId_medio_fechas()
    {
        return $this->id_medio_fecha;
    }
    
    public function setId_medio_fechas($id_medio_fecha)
    {
        $this->id_medio_fecha = $id_medio_fecha;
        return $this;
    }
    public function getId_medio_dias()
    {
        return $this->id_medio_dias;
    }
    
    public function setId_medio_dias($id_medio_dias)
    {
        $this->id_medio_dias = $id_medio_dias;
        return $this;
    }

    public function getId_art_aplica()
    {
        return $this->id_art_aplica;
    }
    
    public function setId_art_aplica($id_art_aplica)
    {
        $this->id_art_aplica = $id_art_aplica;
        return $this;
    }
    

}

?>