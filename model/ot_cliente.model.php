<?php
class ot_cliente {
	
	private $id_cliente;
    private $id_usuario;
   
    private $nombre;

    public function __construct($id_cliente, $id_usuario,$nombre)
    {
        $this->id_cliente = $id_cliente;
        $this->id_usuario = $id_usuario;
     
        $this->nombre = $nombre;
    
       
    }

    public static function alta($id_usuario,$nombre){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_cliente = ot_cliente::ultimo_id();
        
        $sql = "INSERT INTO `ot_cliente`(`id_cliente`, `id_usuario`, `nombre`) VALUES (0,$id_usuario,$nombre)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_cliente;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='ot_cliente'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_cliente){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `ot_cliente` WHERE id_cliente = $id_cliente");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            
            $cliente = new ot_cliente($res_fil['id_cliente'],$res_fil['id_usuario'],$res_fil['nombre']);
            return $cliente;
        }
        else{
            
            return false;
        }
    }

    public static function obtener($id_usuario){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `ot_cliente` WHERE id_usuario = $id_usuario");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            
            $cliente = new ot_cliente($res_fil['id_cliente'],$res_fil['id_usuario'],$res_fil['nombre']);
            return $cliente;
        }
        else{
            
            return false;
        }
    }


    public function getId()
    {
        return $this->id_cliente;
    }
    
    public function setId($id_cliente)
    {
        $this->id_cliente = $id_cliente;
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

    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

   

    

}

?>