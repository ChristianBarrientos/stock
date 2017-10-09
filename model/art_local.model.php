<?php
class art_local {
	
	private $id_local;
    private $nombre;
    private $descripcion;
    private $id_zona;
    private $cantidad_empl;

    


    public function __construct($id_local, $nombre, $descripcion,$id_zona,$cantidad_empl)
    {
        $this->id_local = $id_local;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id_zona = $id_zona;
        $this->cantidad_empl = $cantidad_empl;
       
    }

    public static function alta_local($nombre,$descripcion,$id_zona){
        global $baseDatos;
        $sql = "INSERT INTO art_local VALUES (0,'$nombre','$descripcion',$id_zona)";
        $res = $baseDatos->query($sql);
        return $res;
    }

    public static function generar_local_empleados($id_zona){
        //obtener empleados por local
        $empleados = us_local::obtener_empleados_local($id_zona);
        $empleados_lista = array();
        foreach ($empleados as $key => $value) {
            $empleados_lista[] = usuario::obtener_tabla_usuario($value['id_usuarios']);

        }
        return $empleados_lista;
       


    }

      public static function generar_local($id_zona,$cant_empl){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_local WHERE id_zona = $id_zona");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $zona = mp_zona::obtener_zona__explicita($id_zona);
            
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