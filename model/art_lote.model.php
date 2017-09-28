<?php
class art_lote {
	
	private $id_lote;
    private $id_proveedor;
    private $cantidad;
    private $id_art_conjunto;
    private $id_cb;
    private $id_gc;
 

    public function __construct($id_lote, $id_proveedor, $cantidad,$id_art_conjunto,$id_cb,$id_gc)
    {
        $this->id_lote = $id_lote;
        $this->id_proveedor = $id_proveedor;
        $this->cantidad = $cantidad;
        $this->id_art_conjunto = $id_art_conjunto;
        $this->id_cb = $id_cb;
        $this->id_gc = $id_gc;
      
    }

    public static function alta_art_lote($id_art_conjunto, $cantidad_total, $id_cb, $id_gc, $id_proveedor = 'null', $descripion = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_lote = art_lote::ultimo_id_lote();
        
        $sql = "INSERT INTO `art_lote`(`id_lote`, `id_art_conjunto`, `id_provedor`, `cantidad_total`, `id_cb`, `id_gc`, `descripcion`) VALUES (0,$id_art_conjunto,$id_proveedor,$cantidad_total,$id_cb,$id_gc,'$descripcion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_lote;
        }else{

            return false;
        }

    }
    public static function ultimo_id_lote(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_lote'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_lote($id_lote){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_lote` WHERE id_lote = $id_lote");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $id_art_conjunto = art_lote::obtener_art_conjunto($res_fil['id_art_conjunto']);   
            //$lote = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            return $lote;
        }
        else{
            
            return false;
        }
    }

    public static function obtener_art_conjunto($id_art_conjunto){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `id_art_conjunto` WHERE id_art_conjunto = $id_art_conjunto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $conjunto = art_conjunto::generar_conjunto($res_fil['id_art_conjunto']);   
            $prvd = proveedor::generar_prvd($res_fil['id_provedor']);
            $cb = art_codigo_barra::generar_cb($res_fil['id_cb']);
            $gc = art_grupo_categoria::generar_gc($res_fil['id_gc']);
            //$id_lote, $id_proveedor, $cantidad,$id_art_conjunto,$id_cb,$id_gc
            $lote = new art_local($res_fil['id_local'],$prvd,$res_fil['cantidad_total'],$conjunto,$cb,$gc);
            return $lote;
        }
        else{
            
            return false;
        }
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

    public function getId_proveedor()
    {
        return $this->id_proveedor;
    }
    
    public function setId_proveedor($id_proveedor)
    {
        $this->id_proveedor = $id_proveedor;
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

    public function getId_art_conjunto()
    {
        return $this->id_art_conjunto;
    }
    
    public function setId_art_conjunto($id_art_conjunto)
    {
        $this->id_art_conjunto = $id_art_conjunto;
        return $this;
    }

    public function getId_cb()
    {
        return $this->id_cb;
    }
    
    public function setId_cb($id_cb)
    {
        $this->id_cb = $id_cb;
        return $this;
    }

    public function getId_gc()
    {
        return $this->id_gc;
    }
    
    public function setId_gc($id_gc)
    {
        $this->id_gc = $id_gc;
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
?>