<?php
class gs_subgasto {
	private $id_sub_gasto;
    private $nombre;
    private $valor;
    private $descripcion;
    private $fecha_hora;
    private $condicion;

    public function __construct($id_sub_gasto, $nombre,$valor,$descripcion,$fecha_hora,$condicion)
    {
        $this->id_sub_gasto = $id_sub_gasto;
        $this->nombre = $nombre;
        $this->valor = $valor;
        $this->descripcion = $descripcion;
        $this->fecha_hora = $fecha_hora;
        $this->nombre = $nombre;
        $this->condicion = $condicion;
    
       
    }

    public static function alta_gs_subgasto( $nombre,$valor,$descripcion,$fecha_hora,$condicion){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_sub_gasto = gs_subgasto::ultimo_id_sub_gasto();
        
        $sql = "INSERT INTO `gs_subgasto`(`id_sub_gasto`, `nombre`, `valor`,`descripcion`,`fecha_hora`,`condicion`) VALUES (0,'$nombre',$valor,'$descripcion','$fecha_hora','$condicion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_sub_gasto;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_sub_gasto(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='gs_subgasto'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_sub_gasto){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_subgasto` WHERE id_sub_gasto = $id_sub_gasto");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            $subgasto = new gs_subgasto($res_fil['id_sub_gasto'],$res_fil['nombre'],$res_fil['valor'],$res_fil['descripcion'],$res_fil['fecha_hora'],$res_fil['condicion']);
            return $subgasto;
        }
        else{
            
            return false;
        }
       


    }


    public function getId_sub_gasto()
    {
        return $this->id_sub_gasto;
    }
    
    public function setId_sub_gasto($id_sub_gasto)
    {
        $this->id_sub_gasto = $id_sub_gasto;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }
    
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
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

    public function getCondicion()
    {
        return $this->condicion;
    }
    
    public function setCondicion($condicion)
    {
        $this->condicion = $condicion;
        return $this;
    }

   

    

}

?>