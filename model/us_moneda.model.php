<?php
class us_moneda {
	
	private $id_us_moneda;
    private $id_moneda;
    private $id_usuario;

    public function __construct($id_us_moneda, $id_moneda, $id_usuario)
    {
        $this->id_us_moneda = $id_us_moneda;
        $this->id_moneda = $id_moneda;
        $this->id_usuario = $id_usuario;
    }

    public static function generar($id_us_moneda){
        global $baseDatos;
        $res = $baseDatos->query("SELECT `id_us_moneda`, `id_moneda`, `id_usuario` FROM `us_moneda` WHERE id_us_moneda = $id_us_moneda");

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $us_moneda = new us_moneda($res_fil['id_us_moneda'],$res_fil['id_moneda'],$res_fil['id_usuario']);
            return $us_moneda;
        }
        else{
            return false;
        }
    }

    public static function generar_ggs($id_usuario){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_moneda` WHERE id_us_moneda = $id_us_moneda");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $monedas = array();

            foreach ($filas as $clave => $valor) {
                $id_us_moneda = $valor->getId_us_moneda();
                $monedas[] = new art_moneda();

            }
            $us_moneda = new us_moneda($id_us_moneda,$monedas,$id_usuario);
            return $us_moneda;
        }
        else{
           
            return false;
        }
        
    }


    public static function alta($id_moneda,$id_usuario){
        global $baseDatos;
        //$id_contacto_tel = $this::ta_contacto($telefono);
        $id_us_moneda = us_moneda::ultimo_id();
        $sql = "INSERT INTO `us_moneda`(`id_us_moneda`, `id_moneda`, `id_usuario`) VALUES (0,$id_moneda,$id_usuario)";
        $res = $baseDatos->query($sql);
        if ($res) {
            return $id_us_moneda;
        }else{
              
            return false;
        }

    }

    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='us_moneda'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        return $res_fil['LastId'];
    }

    public static function update_valores($id_us_moneda,$id_usuario){
        //obtener empleados por local
        global $baseDatos;
        $res = $baseDatos->query("UPDATE `art_categoria` SET `id_usuario`='$id_usuario'
                                  WHERE id_us_moneda = $id_us_moneda");  
        return $res;
    }

    public function getId_us_moneda()
    {
        return $this->id_us_moneda;
    }
    
    public function setId_us_moneda($id_us_moneda)
    {
        $this->id_us_moneda = $id_us_moneda;
        return $this;
    }

    public function getId_moneda()
    {
        return $this->id_moneda;
    }
    
    public function setId_moneda($id_moneda)
    {
        $this->id_moneda = $id_moneda;
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