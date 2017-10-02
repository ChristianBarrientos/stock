<?php
class art_lote_local {
	
	private $id_lote_local;
    private $id_lote;
    private $id_local;
    private $cantidad_parcial;
    private $id_carga;
  

    public function __construct($id_lote_local, $id_lote, $id_local,$cantidad_parcial,$id_carga)
    {
        $this->$id_lote_local = $id_lote_local;
        $this->id_lote = $id_lote;
        $this->id_local = $id_local;
        $this->cantidad_parcial = $cantidad_parcial;
        $this->id_carga = $id_carga;
        
    }

    public static function alta_art_lote_local($id_lote,$id_local,$cantidad_parcial,$id_carga){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $$id_lote_local = art_lote_local::ultimo_id_lote_local();
        
        $sql = "INSERT INTO `art_lote_local`(`id_lote_local`, `id_lote`, `id_local`, `cantidad_parcial`, `id_carga`) VALUES (0,$id_lote,$id_local,$cantidad_parcial,$id_carga)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $$id_lote_local;
        }else{
            
            return false;
        }

    }
    public static function ultimo_id_lote_local(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_lote_local'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_lote_local($id_lote){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_lote_local` WHERE id_lote = $id_lote");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            //$id_categoria, $nombre, $valor,$descripcion
            $id_lote = art_lote::generar_lote($res_fil['id_lote_local']);
            $id_local = art_local::generar_local_2($res_fil['id_local']);
            $id_carga = art_carga::generar_carga($res_fil['id_carga']);

            $lote_local = new art_categoria($res_fil['id_lote_local'],$id_lote,$id_local,$res_fil['cantidad_parcial'],$id_carga);
            return $lote_local;
        }
        else{
            
            return false;
        }
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

    public function getId_lote()
    {
        return $this->id_lote;
    }
    
    public function setId_lote($id_lote)
    {
        $this->id_lote = $id_lote;
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
    public function getCantidad_parcial()
    {
        return $this->cantidad_parcial;
    }
    
    public function setCantidad_parcial($cantidad_parcial)
    {
        $this->cantidad_parcial = $cantidad_parcial;
        return $this;
    }

    public function getId_carga()
    {
        return $this->id_carga;
    }
    
    public function setId_carga($id_carga)
    {
        $this->id_carga = $id_carga;
        return $this;
    }

}