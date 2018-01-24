<?php
class art_gunico {
	
	private $id_gunico;
    private $id_lote_local;

    public function __construct($id_gunico, $id_lote_local)
    {
        $this->id_gunico = $id_gunico;
        $this->id_lote_local = $id_lote_local;
    }

    public static function alta($id_lote_local){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_gunico = art_gunico::ultimo_id();
        
        $sql = "INSERT INTO `art_gunico`(`id_gunico`, `id_lote_local`) VALUES (0,$id_lote_local)";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_gunico;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_gunico'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar($id_gunico){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_gunico` WHERE id_gunico = $id_gunico"); 
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            $lote_local = array();
            foreach ($filas as $key => $value) {
                $lote_local []= art_lote_local::generar_lote_local_id_($res_fil['id_lote_local']);
            }

            $gunico = new art_gunico($res_fil['id_gunico'],$lote_local);}
            return $gunico;
        }
        else{
            return false;
        }
    }


    public function getId()
    {
        return $this->id_gunico;
    }
    
    public function setId($id_gunico)
    {
        $this->id_gunico = $id_gunico;
        return $this;
    }

    public function getFecha_hora()
    {
        return $this->id_lote_local;
    }
    
    public function setFecha_hora($id_lote_local)
    {
        $this->id_lote_local = $id_lote_local;
        return $this;
    }

   

    

}

?>