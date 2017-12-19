<?php
class art_us_codigos  {
	
	private $id_codigo;
    private $id_usuario;
   
    private $numero;

    public function __construct($id_codigo, $id_usuario,$numero)
    {
        $this->id_codigo = $id_codigo;
        $this->id_usuario = $id_usuario;
     
        $this->numero = $numero;
    
       
    }

    public static function alta($id_usuario,$numero){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_codigo = art_us_codigos::ultimo_id();
        
        $sql = "INSERT INTO `art_us_codigos`(`id_codigo`, `id_usuario`, `numero`) VALUES (0,$id_usuario,$numero)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_codigo;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_us_codigos'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_codigo){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_us_codigos` WHERE id_codigo = $id_codigo");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            
            $cliente = new art_us_codigos($res_fil['id_codigo'],$res_fil['id_usuario'],$res_fil['numero']);
            return $cliente;
        }
        else{
            
            return false;
        }
    }

    public static function obtener($id_usuario){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_us_codigos` WHERE id_usuario = $id_usuario");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            
            $codigo = new art_us_codigos($res_fil['id_codigo'],$res_fil['id_usuario'],$res_fil['numero']);
            return $codigo;
        }
        else{
            
            return false;
        }
    }

    public static function update($id_codigo,$fila,$nuevo_valor){
        //obtener empleados por local
        global $baseDatos;
        switch ($fila) {
            case 'numero':
                # code...
                $res = $baseDatos->query(" UPDATE `art_us_codigos` SET `numero`=$nuevo_valor WHERE id_codigo = $id_codigo");  
                break;
            default:
                # code...
                break;
        }
        
         
        return $res;
    }


    public function getId()
    {
        return $this->id_codigo;
    }
    
    public function setId($id_codigo)
    {
        $this->id_codigo = $id_codigo;
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

    public function getNumero()
    {
        return $this->numero;
    }
    
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

   

    

}

?>