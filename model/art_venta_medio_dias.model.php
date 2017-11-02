<?php
class art_venta_medio_dias {
	
	private $id_dias_medio;
    private $dias;
    

    public function __construct($id_dias_medio, $dias, $fecha_hora_fin)
    {
        $this->id_dias_medio = $id_dias_medio;
        $this->dias = $dias;
    }

    public static function alta_art_conjunto($nombre,$descripcion, $descuento){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_medio = art_conjunto::ultimo_id_conjunto();
        
        $sql = "INSERT INTO `art_conjunto`(`id_art_conjunto`, `nombre`, `descripcion`, `id_tipo`) VALUES (0,$nombre,$descripcion,$id_tipo)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_medio;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_conjunto(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_conjunto'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_conjunto($id_art_conjunto){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_conjunto` WHERE id_art_conjunto = $id_art_conjunto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $articulo = articulo::generar_articulo($res_fil['nombre']);   
            $marca = art_marca::generar_marca($res_fil['descripcion']);  
            $tipo = art_tipo::generar_tipo($res_fil['id_tipo']);  
            //$lote = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            $conjunto = new art_conjunto($res_fil['id_art_conjunto'],$articulo,$marca,$tipo);
            
            return $conjunto;
        }
        else{
            
            return false;
        }
    }



    public function getId_dias_medio()
    {
        return $this->id_dias_medio;
    }
    
    public function setId_dias_medio($id_dias_medio)
    {
        $this->id_dias_medio = $id_dias_medio;
        return $this;
    }

    public function getDias()
    {
        return $this->dias;
    }
    
    public function setDias($dias)
    {
        $this->dias = $dias;
        return $this;
    }

    

   

}

?>