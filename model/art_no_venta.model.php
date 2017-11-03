<?php
class art_no_venta {
	
	private $id_no_venta;
    private $fecha_hora; 
    private $id_usuario;
    private $id_lote_local;
    

    public function __construct($id_no_venta, $fecha_hora,$id_usuario,$id_lote_local)
    {
        $this->id_no_venta = $id_no_venta;
        $this->fecha_hora = $fecha_hora;
        $this->id_usuario = $id_usuario;
        $this->id_lote_local = $id_lote_local;
         
       
    }

    public static function alta_art_no_venta($fecha_hora,$id_usuario,$id_lote_local){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_no_venta = art_no_venta::ultimo_id_no_venta();
        $sql = "INSERT INTO `art_no_venta`(`id_no_venta`, `fecha_hora`, `id_usuarios`, `id_lote_local`) VALUES (0,'$fecha_hora',$id_usuario,$id_lote_local)";

        $res = $baseDatos->query($sql);
        if ($res) {
            
            return $id_no_venta;
        }else{
            
            return false;
        }

    }
    public static function ultimo_id_no_venta(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_no_venta'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_no_venta($id_no_venta){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_no_venta` WHERE id_venta = $id_venta");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $id_usuario = usuario::generar_usuario($res_fil['id_usuarios']);
            $id_lote_local = art_lote_local::generar_lote_local_id_($res_fil['id_lote_local']);
            $no_venta = new art_no_venta($res_fil['id_no_venta'],$res_fil['fecha_hora'],$id_usuario,$id_lote_local);

            
            return $no_venta;
        }
        else{
            
            return false;
        }
    }


    public function getId_no_venta()
    {
        return $this->id_no_venta;
    }
    
    public function setId_no_venta($id_no_venta)
    {
        $this->id_no_venta = $id_no_venta;
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

    public function getId_lote_local()
    {
        return $this->id_lote_local;
    }
    
    public function setId_lote_local($id_lote_local)
    {
        $this->id_lote_local = $id_lote_local;
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