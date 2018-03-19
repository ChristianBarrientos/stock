<?php
class art_venta {
	
	private $id_venta;
    private $fecha_hora; 
    private $id_usuario;
    private $id_promo;
    private $id_gmedio_pago;
    private $cuotas;
    private $total;
    private $id_cambio;
    private $rg_detalle;
//RG DETALLE --> NUMERO DE COMPROBANTE , NUMERO DE ARTICULO , TOTAL , ID LOCAL , ID USUARIO   ----------------------- PEDIDO POR MOTOMATCH
    public function __construct($id_venta, $fecha_hora,$id_usuario,$id_promo,$id_gmedio_pago,$total,$cuotas,$id_cambio,$rg_detalle = null){
        $this->id_venta = $id_venta;
        $this->fecha_hora = $fecha_hora;
        $this->id_usuario = $id_usuario;
        $this->id_promo = $id_promo;
        $this->id_gmedio_pago = $id_gmedio_pago;
        $this->total = $total;
        $this->cuotas = $cuotas;
        $this->id_cambio = $id_cambio;
        $this->rg_detalle = $rg_detalle;
    }

    public static function alta($fecha_hora,$id_usuario,$id_gmedio_pago,$total,$cuotas,$id_cambio = 'null',$id_promo = 'null',$rg_detalle = 'null'){
        global $baseDatos;
        $id_venta = art_venta::ultimo_id();
        $sql = "INSERT INTO `art_venta`(`id_venta`, `fecha_hora`, `id_usuario`, `id_promo`, `id_gmedio_pago`, `total`, `cuotas`, `id_cambio`,`rg_detalle`) VALUES (0,'$fecha_hora',$id_usuario,$id_promo,$id_gmedio_pago,$total,'$cuotas',$id_cambio,'$rg_detalle')";
        $res = $baseDatos->query($sql);
        if ($res) {
            //printf("Errormessage: %s\n", $baseDatos->error);
            return $id_venta;
        }else{
                //printf("Errormessage: %s\n", $baseDatos->error); 
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
            $id_usuario = usuario::generar_usuario($res_fil['id_usuario']);

            if ($res_fil['id_gmedio_pago'] != null) {
                $id_gmedio_pago = art_gmedio_pago::generar($res_fil['id_gmedio_pago']);
            }
            else{
                $id_gmedio_pago = $res_fil['id_gmedio_pago'];
            }
            $id_promo = $res_fil['id_promo'];
            if ($res_fil['id_cambio'] != null) {
                $art_unico_cambio = art_unico::generar_unico($res_fil['id_cambio']);
                $venta = new art_venta($res_fil['id_venta'],$res_fil['fecha_hora'],$id_usuario,$id_promo,$id_gmedio_pago,$res_fil['total'],$res_fil['cuotas'],$art_unico_cambio,$res_fil['rg_detalle']);
            }else{
                $venta = new art_venta($res_fil['id_venta'],$res_fil['fecha_hora'],$id_usuario,$id_promo,$id_gmedio_pago,$res_fil['total'],$res_fil['cuotas'],$res_fil['id_cambio'],$res_fil['rg_detalle']);
            }
            return $venta;
        }
        else{
            return false;
        }
    }

    public static function obtener($id_usuario){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta` WHERE rg_detalle IS NOT NULL");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           
            $ventas = array();
            //Momentanemente bandera
            
            foreach ($filas as $key => $value) {
                $aux = explode(',',$value['rg_detalle']);

                //MOMENTANEAMENTE
                
                if (count($aux) == 5) {
                    $id_user_carga = $aux[4];
                    //if ($id_user_carga == $id_usuario) {

                        $ventas[] = art_venta::generar($value['id_venta']);

                    //}else{
                    //    continue;
                    //}
                }else{
                    continue;
                }
                //FinMomentanemaente
                 
                
                

                 
            }
           
            return $ventas;
        }
        else{
            
            return false;
        }
    }

    public static function facturacion_($lote_local_cantidad){
        global $baseDatos;


        global $baseDatos;
        foreach ($lote_local_cantidad as $key => $value) {

            $id_lote_local = $value['id_lote_local'];
            $cantidad_vendida = $value['cantidad'];
            $lote_local_generado = art_lote_local::generar_lote_local_id_($id_lote_local);
            $cantidad_lote_local = $lote_local_generado->getCantidad_parcial();
            $lote = $lote_local_generado->getId_lote();
            $cantidad_lote = $lote->getCantidad();

            $cantidad_lote_new = intval($cantidad_lote) - intval($cantidad_vendida); 
            $cantidad_lote_local_new = intval($cantidad_lote_local) - intval($cantidad_vendida); 
            //Update en Art_lote
            $update_art_lote = art_lote::update_cantidad_total($lote->getId_lote(),$cantidad_lote_new);
            //Update en art_lote_local
            $update_art_lote_local = art_lote_local::update_cantidad_parcial($id_lote_local,$cantidad_lote_local_new);

        } 
        
        if ($update_art_lote && $update_art_lote_local) {
             
            $data['status'] = 'ok';
            $data['result'] = $lote_local_cantidad;
            
            return $data;
        }
        else{
            $data['status'] = 'err';
            $data['result'] = '';
            return false;
        }
    }

    public static function update_id_cambio($id_venta,$id_cambio){
        global $baseDatos;
        $res = $baseDatos->query(" UPDATE `art_venta` SET `id_cambio`='$id_cambio' WHERE id_venta = $id_venta");  
        return $res;
    }

    public function getId_gmedio_pago()
    {
        return $this->id_gmedio_pago;
    }
    
    public function setId_gmedio_pago($id_gmedio_pago)
    {
        $this->id_gmedio_pago = $id_gmedio_pago;
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