<?php
class art_lote {
	
	private $id_lote;
    private $id_proveedor;
    private $cantidad;
    private $id_local;
    private $id_cb;
    private $id_gc;
    private $id_carga;

    public function __construct($id_lote, $id_proveedor, $cantidad,$id_local,$id_cb,$id_gc,$id_carga)
    {
        $this->id_lote = $id_lote;
        $this->id_proveedor = $id_proveedor;
        $this->cantidad = $cantidad;
        $this->id_local = $id_local;
        $this->id_cb = $id_cb;
        $this->id_gc = $id_gc;
        $this->id_carga = $id_carga;
    }

    public static function generar_lote(){
        
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

    public function getId_local()
    {
        return $this->id_local;
    }
    
    public function setId_local($id_local)
    {
        $this->id_local = $id_local;
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