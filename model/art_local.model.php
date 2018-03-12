<?php
class art_local {
	
	private $id_local;
    private $nombre;
    private $descripcion;
    private $id_zona;
    private $cantidad_empl;
    private $id_gcj;

    


    public function __construct($id_local, $nombre, $descripcion,$id_zona,$cantidad_empl,$id_gcj = null)
    {
        $this->id_local = $id_local;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id_zona = $id_zona;
        $this->cantidad_empl = $cantidad_empl;
        $this->id_gcj = $id_gcj;
       
    }

    public static function alta_local($nombre,$descripcion,$id_zona = null,$id_gcj = null){
        global $baseDatos;
        $id_local = art_local::ultimo_id_local();

   
        $sql = "INSERT INTO `art_local`(`id_local`, `nombre`, `descripcion`, `id_zona`, `id_gcj`) VALUES (0,'$nombre','$descripcion',$id_zona,null )";
        $res = $baseDatos->query($sql);
        printf("Errormessage: %s\n", $baseDatos->error);
        return $id_local;
    }

    public static function ultimo_id_local(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_local'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_local_empleados($id_local){
        //obtener empleados por local
        $empleados = us_local::obtener_empleados_local($id_local);
        $empleados_lista = array();

        foreach ($empleados as $key => $value) {
            $empleados_lista[] = usuario::obtener_tabla_usuario($value['id_usuarios']);

        }
        return $empleados_lista;
       
    }

    public static function obtener_locales_usuario_operador(){
        //obtener empleados por local
        global $baseDatos;
        if (isset($_SESSION["usuario"]) && $_SESSION["usuario"] != null) {
            # code...
          
            $id_usuarios = $_SESSION["usuario"]->getId_user();
        }else{
           
            return false;
        }
        
         
        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_usuarios = $id_usuarios");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);

        if (count($filas) != 0) {
            $locales_operador = array();
            foreach ($filas as $clave => $valor) {
                 
                $locales_operador[] = array(
                            "id_us_local" =>  $valor['id_usuarios_local'],
                            "id_usuario" =>  $valor['id_usuarios'],
                            "id_local" =>  $valor['id_local'],
                        );
            }
            return $locales_operador;
        }
        else{
            return false;
        }
       


    }

      public static function generar_local($id_local,$cant_empl = 0){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_local WHERE id_local = $id_local");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $zona = mp_zona::obtener_zona__explicita($res_fil['id_zona']);
            
            $local = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            return $local;
        }
        else{
            
            return false;
        }
       


    }

    public static function generar_local_2($id_local){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_local WHERE id_local = $id_local");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $zona = mp_zona::obtener_zona__explicita($res_fil['id_zona']);
            $cant_empl = art_local::generar_local_empleados($res_fil['id_zona']);
            $local = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            return $local;
        }
        else{
            
            return false;
        }
       


    }

    

    public static function generar_local_3($id_local){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_local WHERE id_local = $id_local");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $zona = mp_zona::generar_zona($res_fil['id_zona']);
            $cant_empl = art_local::generar_local_empleados($res_fil['id_zona']);
            $local = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            return $local;
        }
        else{
            
            return false;
        }
       


    }

    public static function obtener_id_local($id_zona){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_local WHERE id_zona = $id_zona");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return $res_fil['id_local'];
        }
        else{
            
            return false;
        }
       


    }

    public static function update_($id_local, $nombre, $descripcion){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_local` SET `nombre`='$nombre',`descripcion`='$descripcion' WHERE id_local = $id_local");  
         
        return $res;
       


    }
   
    public static function update_zona($id_zona,$pais,$provincia,$localidad,$direccion){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `mp_zona` SET `id_pais`=$pais,`id_provincia`=$provincia,`id_localidad`=$localidad,`direccion`='$direccion' WHERE id_zona = $id_zona");  
         
        return $res;
    }

    

  


    public function getId_local()
    {
        return $this->id_local;
    }
    
    public function setId_local($id_local)
    {
        $this->id_local = $id_local;
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

    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
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

    

    public function getCantidad_empl()
    {
        return $this->cantidad_empl;
    }
    
    public function setCantidad_empl($cantidad_empl)
    {
        $this->cantidad_empl = $cantidad_empl;
        return $this;
    }

   

}

?>