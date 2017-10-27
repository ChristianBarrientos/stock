 <?php
class art_codigo_barra {
	
	private $id_cb;
    private $cb;


    public function __construct($id_cb, $cb)
    {
        $this->id_cb = $id_cb;
        $this->cb = $cb;
      
    
       
    }

    public static function alta_art_cb($cb){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_cb = art_codigo_barra::ultimo_id_cb();
        
        $sql = "INSERT INTO `art_codigo_barra`(`id_cb`, `cb`) VALUES (0,$cb)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_cb;
        }else{
             
            return 'null';
        }

    }
    public static function ultimo_id_cb(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_codigo_barra'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_cb($id_cb){
        global $baseDatos;
        if ($id_cb != null) {
            $res = $baseDatos->query("SELECT * FROM `art_codigo_barra` WHERE id_cb = $id_cb");  
            $res_fil = $res->fetch_assoc();
            if (count($res_fil) != 0) {
                $cb = new art_codigo_barra($res_fil['id_cb'],$res_fil['cb']);
            return $cb;
            }
        }
        
        else{
            
            return false;
        }
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

    public function getcb()
    {
        return $this->cb;
    }
    
    public function setcb($cb)
    {
        $this->cb = $cb;
        return $this;
    }

   

    

}

?>