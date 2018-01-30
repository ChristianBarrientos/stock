<?php
class art_gmedio_pago {
	
	private $id_gmedio_pago;
    private $id_medio_pago;
    private $rg_detalle;

    public function __construct($id_gmedio_pago, $id_medio_pago,$rg_detalle)
    {
        $this->id_gmedio_pago = $id_gmedio_pago;
        $this->id_medio_pago = $id_medio_pago;
        $this->rg_detalle = $rg_detalle;
    }

    public static function alta($id_gmedio_pago,$id_medio_pago,$rg_detalle){
        global $baseDatos;
        //$id_contacto_tel = $this::alta_contacto($telefono);
        //$id_gmedio_pago = art_gmedio_pago::ultimo_id();
        
        $sql = "INSERT INTO `art_gmedio_pago`(`id_gmedio_pago`, `id_medio_pago`, `rg_detalle`) VALUES ($id_gmedio_pago,$id_medio_pago,'$rg_detalle')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return true;
        }else{
             
            return false;
        }
    }
    
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_gmedio_pago'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_gmedio_pago){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_gmedio_pago` WHERE id_gmedio_pago = $id_gmedio_pago"); 
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $medios_pago = array();
            foreach ($filas as $key => $value) {
                $medios_pago []= art_venta_medio_pago::generar($res_fil['id_medio_pago']);
            }
            $gmedio_pago = new art_gmedio_pago($res_fil['id_gmedio_pago'],$medios_pago,$res_fil['rg_detalle']);
            return $gmedio_pago;
        }
        else{
            return false;
        }
    }


    public function getId()
    {
        return $this->id_gmedio_pago;
    }
    
    public function setId($id_gmedio_pago)
    {
        $this->id_gmedio_pago = $id_gmedio_pago;
        return $this;
    }

    public function getId_medio_pago()
    {
        return $this->id_medio_pago;
    }
    
    public function setId_medio_pago($id_medio_pago)
    {
        $this->id_medio_pago = $id_medio_pago;
        return $this;
    }

    public function getRg_detalle()
    {
        return $this->rg_detalle;
    }
    
    public function setRg_detalle($rg_detalle)
    {
        $this->rg_detalle = $rg_detalle;
        return $this;
    }

   

    

}

?>