<?php
class gs_gasto_unico {
	
	private $id_gasto_unico;
    private $nombre;
    private $valor;
    private $fecha_hora;
    private $id_gsub_gasto;
    private $habilitado;
    private $descripcion;

    public function __construct($id_gasto_unico, $nombre,$valor,$fecha_hora,$id_gsub_gasto,$habilitado,$descripcion)
    {
        $this->id_gasto_unico = $id_gasto_unico;
        $this->nombre = $nombre;
        $this->valor = $valor;
        $this->fecha_hora = $fecha_hora;
        $this->id_gsub_gasto = $id_gsub_gasto;
        $this->descripcion = $descripcion;
        $this->habilitado = $habilitado;
        
    
       
    }

    public static function alta($nombre,$valor,$habilitado,$fecha_hora,$descripcion = 'null',$id_gsub_gasto = null){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gasto_unico = gs_gasto_unico::ultimo_id_gasto_unico();
        
        /*print_r( $nombre);
        echo "\n";
        print_r( $valor);
        echo "\n";
        print_r( $habilitado);
        echo "\n";
        print_r( $fecha_hora);
        echo "\n";
        print_r( $descripcion);
        echo "\n";)*/
        $sql = "INSERT INTO `gs_gasto_unico`(`id_gasto_unico`, `nombre`, `valor`, `fecha_hora`, `id_gsub_gasto`, `habilitado`, `descripcion`) VALUES (0,'$nombre',$valor ,'$fecha_hora', null, $habilitado, '$descripcion')";
        $res = $baseDatos->query($sql);
        //printf("Errormessage: %s\n", $baseDatos->error);
        if ($res) {
             
            return $id_gasto_unico;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_gasto_unico(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='gs_gasto_unico'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_gasto_unico){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `gs_gasto_unico` WHERE id_gasto_unico = $id_gasto_unico");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {

            if ($res_fil['id_gsub_gasto'] != null) {
                # code...
                $id_gsub_gasto = gs_gsub_gasto::generar($res_fil['id_gsub_gasto']);
            }
            else{
                $id_gsub_gasto = null;
            }
            
            $gasto_unico = new gs_gasto_unico($res_fil['id_gasto_unico'],$res_fil['nombre'],$res_fil['valor'],$res_fil['fecha_hora'],$id_gsub_gasto,$res_fil['habilitado'],$res_fil['descripcion']);

         
            return $gasto_unico;    
        }
        else{
            
            return false;
        }
       


    }

    public static function update($id_gasto_unico, $columna, $nuevo_valor){
        //obtener empleados por local
        global $baseDatos;

        $res = $baseDatos->query(" UPDATE `gs_gasto_unico` SET `$columna`='$nuevo_valor' WHERE id_gasto_unico = $id_gasto_unico");  
         
        return $res;
    }


    public function getId_gasto_unico()
    {
        return $this->id_gasto_unico;
    }
    
    public function setId_gasto_unico($id_gasto_unico)
    {
        $this->id_gasto_unico = $id_gasto_unico;
        return $this;
    }

    public function getnombre()
    {
        return $this->nombre;
    }
    
    public function setnombre($nombre)
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

    public function getFecha_hora()
    {
        return $this->fecha_hora;
    }
    
    public function setFecha_hora($fecha_hora)
    {
        $this->fecha_hora = $fecha_hora;
        return $this;
    }

    public function getId_gsub_gasto()
    {
        return $this->id_gsub_gasto;
    }
    
    public function setId_gsub_gasto($id_gsub_gasto)
    {
        $this->id_gsub_gasto = $id_gsub_gasto;
        return $this;
    }

    public function getHabilitado()
    {
        return $this->habilitado;
    }
    
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;
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
   

    

}

?>