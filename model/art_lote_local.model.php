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

}