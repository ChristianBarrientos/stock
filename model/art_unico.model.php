<?php
class art_unico {
	
	private $id_unico;
    private $id_lote;
    private $id_venta;
    

    public function __construct($id_unico, $id_lote, $id_venta)
    {
        $this->id_unico = $id_unico;
        $this->id_lote = $id_lote;
        $this->id_venta = $id_venta;
       
    }

    public function getId_unico()
    {
        return $this->id_unico;
    }
    
    public function setId_unico($id_unico)
    {
        $this->id_unico = $id_unico;
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

    public function getId_venta()
    {
        return $this->id_venta;
    }
    
    public function setId_venta($id_venta)
    {
        $this->id_venta = $id_venta;
        return $this;
    }


}

?>
