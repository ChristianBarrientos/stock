<?php
class mp_zona {

	private $id_zona;
    private $pais;
    private $provincia;
    private $localidad;
    private $direccion;


    public function __construct($id_zona, $pais, $provincia,$localidad,$direccion)
    {
        $this->id_zona = $id_zona;
        $this->pais = $pais;
        $this->provincia = $provincia;
        $this->localidad = $localidad;
        $this->direccion = $direccion;
    }

    public static function alta_zona($id_pais,$id_provincia,$id_localidad,$direccion){
        global $baseDatos;
        //momentaneo
        $sql = "INSERT INTO mp_zona VALUES (0,$id_pais,$id_provincia,$id_localidad,'$direccion')";
        $res = $baseDatos->query($sql);
        return $res;
    }

    public static function update_zona($id_pais,$id_provincia,$id_localidad,$direccion){
        global $baseDatos;
        //momentaneo
        $sql = "INSERT INTO mp_zona VALUES (0,$id_pais,$id_provincia,$id_localidad,'$direccion')";
        $res = $baseDatos->query($sql);
        return $res;
    }

    public static function obtener_zona($direccion){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM mp_zona WHERE direccion = '$direccion'");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            return $res_fil['id_zona'];
        }
        else{
            return false;
        }
    }

     public static function obtener_zona__explicita($id_zona){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM mp_zona WHERE id_zona = $id_zona");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $pais = mp_zona::obtener_pais($res_fil['id_pais']);
            $provincia = mp_zona::obtener_provincia($res_fil['id_provincia']);
            $localidad = mp_zona::obtener_localidad($res_fil['id_localidad']);
            $zona_completa = $pais['valor'].','.$provincia['valor'].','.$localidad['valor'].','.$res_fil['direccion'];
            return $zona_completa;
        }
        else{
            return false;
        }
    }

    public static function obtener_zona__explicita_2($id_zona){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM mp_zona WHERE id_zona = $id_zona");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {

            $pais = mp_zona::obtener_pais($res_fil['id_pais']);
            $provincia = mp_zona::obtener_provincia($res_fil['id_provincia']);
            $localidad = mp_zona::obtener_localidad($res_fil['id_localidad']);
            $zona_completa = ["id_zona" => $res_fil['id_zona'],"pais" => $pais, "provincia" => $provincia, "localidad" => $localidad];
            return $zona_completa;
        }
        else{
            return false;
        }
    }

     public static function obtener_pais($id_pais){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM mp_pais WHERE id_pais = $id_pais");  

        
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return $res_fil;
        }
        else{
            return false;
        }

    }

     public static function obtener_provincia($id_provincia){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM mp_provincia WHERE id_provincia = $id_provincia");  

        
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return $res_fil;
        }
        else{
            return false;
        }

        
    }

     public static function obtener_localidad($id_localidad){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM mp_localidad WHERE id_localidad = $id_localidad");  

        
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return $res_fil;
        }
        else{
            return false;
        }

        
    }


    public static function verificar_zona($pais,$provincia,$localidad,$direccion){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM mp_zona WHERE id_pais = $pais AND id_provincia = $provincia AND id_localidad = $localidad AND direccion = '$direccion'");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            return false;
        }
        else{
            return true;
        }

    }

   

    public static function obtener_paises(){
    	global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM mp_pais");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           
            return $filas;
        }
        else{
            return false;
        }

    }

     public static function obtener_provincias(){
     	global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM mp_provincia");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           
            return $filas;
        }
        else{
            return false;
        }

    	
    }

     public static function obtener_localidades(){
     	global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM mp_localidad");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           
            return $filas;
        }
        else{
            return false;
        }

    	
    }


    public function getId_zona()
    {
        return $this->id_zona;
    }
    
    public function setId_zona($id_zona)
    {
        $this->id_zona = $id_zona;
        return $this;
    }

    public function getPais()
    {
        return $this->pais;
    }
    
    public function setPais($pais)
    {
        $this->pais = $pais;
        return $this;
    }

    public function getProvincia()
    {
        return $this->provincia;
    }
    
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
        return $this;
    }

    public function getLocalidad()
    {
        return $this->localidad;
    }
    
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
        return $this;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }
    
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
        return $this;
    }

}

?>