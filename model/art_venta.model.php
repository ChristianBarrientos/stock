<?php
class art_venta {
	
	private $id_venta;
    private $fecha_hora; 
    private $id_usuario;
    private $medio;
    private $total;

    public function __construct($id_venta, $fecha_hora,$id_usuario,$medio,$total)
    {
        $this->id_venta = $id_venta;
        $this->fecha_hora = $fecha_hora;
        $this->id_usuario = $id_usuario;
        $this->medio = $medio;
        $this->total = $total;
       
    }

    public static function alta_art_venta($fecha_hora,$id_usuario,$id_medio,$total,$cuotas = null,$id_cambio = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_venta = art_venta::ultimo_id_venta();
        echo "%%";
        echo $fecha_hora;
        echo "&&";
        echo $id_usuario;
        echo "&&";
        echo $id_medio;
        echo "&&";
        echo $total;
        echo "&&";
        echo $cuotas;
        echo "%%";
        $sql = "INSERT INTO `art_venta`(`id_venta`, `fecha_hora`, `id_usuarios`, `id_medio`, `total`,`cuotas`, `id_cambio`) VALUES (0,'$fecha_hora',$id_usuario,$id_medio,'$total','$cuotas',$id_cambio)";

        $res = $baseDatos->query($sql);
        if ($res) {
            echo "aca";
            return $id_venta;
        }else{
            echo "ni";
            print_r($baseDatos->error);
            return false;
        }

    }
    public static function ultimo_id_venta(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_venta($id_venta){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_venta` WHERE id_venta = $id_venta");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $id_usuario = usuario::generar_usuario($res_fil['id_usuarios']);

            $venta = new art_venta($res_fil['id_venta'],$res_fil['fecha_hora'],$id_usuario,$res_fil['medio'],$res_fil['total']);

            
            return $venta;
        }
        else{
            
            return false;
        }
    }


    public function getMedio()
    {
        return $this->medio;
    }
    
    public function setMedio($medio)
    {
        $this->medio = $medio;
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


}
?>