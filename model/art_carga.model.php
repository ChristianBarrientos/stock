<?php
class art_carga {
	
	private $id_carga;
    private $fecha_hora;
    private $id_local;
    private $id_usuario;

    public function __construct($id_carga, $fecha_hora, $id_local,$id_usuario)
    {
        $this->id_carga = $id_carga;
        $this->fecha_hora = $fecha_hora;
        $this->id_local = $id_local;
        $this->id_usuario = $id_usuario;
    
       
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

    

}

?>