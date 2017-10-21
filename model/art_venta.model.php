<?php
class art_venta {
	
	private $id_venta;
    private $fecha_hora;
    private $id_local;
    private $id_usuario;
    private $medio_pago;
    private $cuotas;

    public function __construct($id_venta, $fecha_hora, $id_local,$id_usuario,$medio_pago,$cuotas = 1)
    {
        $this->id_venta = $id_venta;
        $this->fecha_hora = $fecha_hora;
        $this->id_local = $id_local;
        $this->id_usuario = $id_usuario;
        $this->medio_pago = $medio_pago;
        $this->cuotas = $cuotas;
       
    }

    public function getMedio_pago()
    {
        return $this->id_venta;
    }
    
    public function setCuotas($id_venta)
    {
        $this->id_venta = $id_venta;
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