<?php
class art_venta {
	
	private $id_venta;
    private $fecha_hora; 
    private $id_usuario;
    private $id_promo;
    private $id_medio_pago;
    private $cuotas;
    private $total;
    private $id_cambio;

    public function __construct($id_venta, $fecha_hora,$id_usuario,$id_promo,$id_medio_pago,$total,$cuotas,$id_cambio)
    {
        $this->id_venta = $id_venta;
        $this->fecha_hora = $fecha_hora;
        $this->id_usuario = $id_usuario;
        $this->id_promo = $id_promo;
        $this->id_medio_pago = $id_medio_pago;
        $this->total = $total;
        $this->cuotas = $cuotas;
        $this->id_cambio = $id_cambio;
       
    }
//$fecha_venta,$id_usuario,$medio,$total,$cuotas2
    public static function alta($fecha_hora,$id_usuario,$id_medio_pago,$total,$cuotas,$id_cambio = 'null',$id_promo = 'null'){
        global $baseDatos;

        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_venta = art_venta::ultimo_id();
        $sql = "INSERT INTO `art_venta`(`id_venta`, `fecha_hora`, `id_usuario`, `id_promo`, `id_medio_pago`, `total`, `cuotas`, `id_cambio`) VALUES (0,'$fecha_hora',$id_usuario,$id_promo,$id_medio_pago,$total,'$cuotas',$id_cambio)";

        $res = $baseDatos->query($sql);
        if ($res) {
            printf("Errormessage: %s\n", $baseDatos->error);
            return $id_venta;
        }else{
                printf("Errormessage: %s\n", $baseDatos->error); 
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_venta){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta` WHERE id_venta = $id_venta");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $id_usuario = usuario::generar_usuario($res_fil['id_usuario']);

            if ($res_fil['id_medio_pago'] != null) {
                # code...
                $id_medio_pago = art_venta_medio_pago::generar($res_fil['id_medio_pago']);
            }
            else{
                $id_medio_pago = $res_fil['id_medio_pago'];
            }

            //if ($res_fil['id_promo'] != null) {
                # code...
                //$id_medio_pago = art_venta_medio_pago::generar($res_fil['id_medio_pago']);
            //}
            //else{
                $id_promo = $res_fil['id_promo'];
            //}
            

            if ($res_fil['id_cambio'] != null) {
                # code...
                $art_unico_cambio = art_unico::generar_unico($res_fil['id_cambio']);
                $venta = new art_venta($res_fil['id_venta'],$res_fil['fecha_hora'],$id_usuario,$id_promo,$id_medio_pago,$res_fil['total'],$res_fil['cuotas'],$art_unico_cambio);

            }else{
//$id_venta, $fecha_hora,$id_usuario,$id_promo,$id_medio_pago,$total,$cuotas,$id_cambio
                $venta = new art_venta($res_fil['id_venta'],$res_fil['fecha_hora'],$id_usuario,$id_promo,$id_medio_pago,$res_fil['total'],$res_fil['cuotas'],$res_fil['id_cambio']);
            }
            

            
            return $venta;
        }
        else{
            
            return false;
        }
    }

    public static function update_id_cambio($id_venta,$id_cambio){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_venta` SET `id_cambio`='$id_cambio' WHERE id_venta = $id_venta");  
         
        return $res;
       


    }


    public function getMedio_pago()
    {
        return $this->id_medio_pago;
    }
    
    public function setMedio_pago($id_medio_pago)
    {
        $this->id_medio_pago = $id_medio_pago;
        return $this;
    }

    public function getId_promo()
    {
        return $this->id_promo;
    }
    
    public function setId_promo($id_promo)
    {
        $this->id_promo = $id_promo;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }
    
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }


    public function getId_venta()
    {
        return $this->id_venta;
    }
    
    public function setId_venta($id_venta)
    {
        $this->id_venta = $id_venta;
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

    public function getId_local()
    {
        return $this->id_local;
    }
    
    public function setId_local($id_local)
    {
        $this->id_local = $id_local;
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

    public function getCuotas()
    {
        return $this->cuotas;
    }
    
    public function setCuotas($cuotas)
    {
        $this->cuotas = $cuotas;
        return $this;
    }

    public function getId_cambio()
    {
        return $this->id_cambio;
    }
    
    public function setId_cambio($id_cambio)
    {
        $this->id_cambio = $id_cambio;
        return $this;
    }


}
?>