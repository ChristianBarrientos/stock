<?php
class art_gunico {
	
	private $id_gunico;
    private $id_lote_local;
    private $rg_detalle;
    private $cantidad;

    public function __construct($id_gunico, $id_lote_local,$rg_detalle,$cantidad)
    {
        $this->id_gunico = $id_gunico;
        $this->id_lote_local = $id_lote_local;
        $this->rg_detalle = $rg_detalle;
        $this->cantidad = $cantidad;
    }

    public static function alta($id_gunico,$id_lote_local,$rg_detalle,$cantidad){
        global $baseDatos;
        //$id_gunico = art_gunico::ultimo_id();
        
        $sql = "INSERT INTO `art_gunico`(`id_gunico`, `id_lote_local`, `rg_detalle`, `cantidad`) VALUES ($id_gunico,$id_lote_local,'$rg_detalle',$cantidad)";
        $res = $baseDatos->query($sql);
        if ($res) {
            return true;
        }else{    
            return false;
        }
    }

    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_gunico'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_gunico){
        global $baseDatos;

        $res = $baseDatos->query("SELECT * FROM `art_gunico` WHERE id_gunico = $id_gunico"); 
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $lote_local = array();
            $rg_detalle_array  = array();
            $cantidad = array();
            foreach ($filas as $key => $value) {
                
                $lote_local []= art_lote_local::generar_lote_local_id_($value['id_lote_local']);
                $rg_detalle_array[] = $value['rg_detalle'];
                $cantidad [] = $value['cantidad'];
                
            }
            $gunico = new art_gunico($value['id_gunico'],$lote_local,$rg_detalle_array,$cantidad);
            return $gunico;
        }
        else{
            return false;
        }
    }


    public function getId()
    {
        return $this->id_gunico;
    }
    
    public function setId($id_gunico)
    {
        $this->id_gunico = $id_gunico;
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

    public function getRg_detalle()
    {
        return $this->rg_detalle;
    }
    
    public function setRg_detalle($rg_detalle)
    {
        $this->rg_detalle = $rg_detalle;
        return $this;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
        return $this;
    }

   

    

}

?>