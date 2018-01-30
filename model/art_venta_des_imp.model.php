<?php
class art_venta_des_imp {
	
	private $id_des_imp;
    private $valor;
   
    private $signo;

    public function __construct($id_des_imp, $valor,$signo)
    {
        $this->id_des_imp = $id_des_imp;
        $this->valor = $valor;
        $this->signo = $signo;
    }

    public static function alta($valor,$signo){
        global $baseDatos;

        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_des_imp = art_venta_des_imp::ultimo_id();
        
        $sql = "INSERT INTO `art_venta_des_imp`(`id_des_imp`, `valor`, `signo`) VALUES (0,$valor,'$signo')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_des_imp;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_venta_des_imp'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_des_imp){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_venta_des_imp` WHERE id_des_imp = $id_des_imp");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            
            $cliente = new art_venta_des_imp($res_fil['id_des_imp'],$res_fil['valor'],$res_fil['signo']);
            return $cliente;
        }
        else{
            
            return false;
        }
    }

    public static function obtener($id_des_imp){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_venta_des_imp` WHERE id_des_imp = $id_des_imp");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            
            $cliente = new art_venta_des_imp($res_fil['id_des_imp'],$res_fil['valor'],$res_fil['signo']);
            return $cliente;
        }
        else{
            
            return false;
        }
    }


    public function getId()
    {
        return $this->id_des_imp;
    }
    
    public function setId($id_des_imp)
    {
        $this->id_des_imp = $id_des_imp;
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

    public function getSigno()
    {
        return $this->signo;
    }
    
    public function setSigno($signo)
    {
        $this->signo = $signo;
        return $this;
    }

   

    

}

?>