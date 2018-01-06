<?php
class art_moneda {
	
	private $id_moneda;
    private $nombre;
    private $valor;

    public function __construct($id_moneda, $nombre, $valor)
    {
        $this->id_moneda = $id_moneda;
        $this->nombre = $nombre;
        $this->valor = $valor;
    }

    public static function generar($id_moneda){
        global $baseDatos;
        $res = $baseDatos->query("SELECT `id_moneda`, `nombre`, `valor` FROM `art_moneda` WHERE id_moneda = $id_moneda");

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $moneda = new art_moneda($res_fil['id_moneda'],$res_fil['nombre'],$res_fil['valor']);
            return $moneda;
        }
        else{
            return false;
        }
    }

    public static function alta($nombre,$valor ){
        global $baseDatos;
        //$id_contacto_tel = $this::ta_contacto($telefono);
        $id_moneda = art_moneda::ultimo_id();
        $sql = "INSERT INTO `art_moneda`(`id_moneda`, `nombre`, `valor`) VALUES (0,'$nombre',$valor)";
        $res = $baseDatos->query($sql);
        if ($res) {
            return $id_moneda;
        }else{
              
            return false;
        }

    }

    public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_moneda'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        return $res_fil['LastId'];
    }

    public static function update_valores($id_moneda,$valor){
        //obtener empleados por local
        global $baseDatos;
        $res = $baseDatos->query("UPDATE `art_categoria` SET `valor`='$valor'
                                  WHERE id_moneda = $id_moneda");  
        return $res;
    }

    public function getId()
    {
        return $this->id_moneda;
    }
    
    public function setId($id_moneda)
    {
        $this->id_moneda = $id_moneda;
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
}

?>