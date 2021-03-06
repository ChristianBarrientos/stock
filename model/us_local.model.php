<?php
class us_local {
	
	private $id_usuario_local;
    private $id_usuario;

     public function __construct($id_usuario_local, $id_usuario)
    {
        $this->id_usuario_local = $id_usuario_local;
        $this->id_usuario = $id_usuario;
      
    }

    public static function generar_tabla_us_local($id_usuarios,$id_zona){
    	global $baseDatos;
        
        $sql = "INSERT INTO us_local VALUES (0,$id_usuarios,$id_zona)";
        $res = $baseDatos->query($sql);
        return $res;
    }

    public static function obtener_tabla_us_local($id_usuarios,$id_zona){
    	global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_usuarios = '$id_usuarios' AND id_zona = '$id_zona'");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            return $filas;
        }
        else{
            return false;
        }
    }

    public static function obtener_tabla_us_local_operador($id_local){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_local = '$id_local'");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            return $res_fil;
        }
        else{
            return false;
        }
    }


    public static function obtener_empleados_local($id_local){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_local = '$id_local'");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
         
        if (count($filas) != 0) {
            
            return $filas;
        }
        else{
            return false;
        }
    }

    public static function agregar_us_a_local($id_usuario,$id_local){
        global $baseDatos;

        $sql = "INSERT INTO `us_local`(`id_usuarios_local`, `id_usuarios`, `id_local`) VALUES (0,$id_usuario,$id_local)";
        $res = $baseDatos->query($sql);
        return $res;
    }

    public static function obtener_locales_usuario($id_usuarios){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_usuarios = '$id_usuarios'");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            
            return $filas;
        }
        else{
            return false;
        }
    }

    public static function empleado_local_esta($id_usuario,$id_local){
        global $baseDatos;
     
        
        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_usuarios = '$id_usuario' AND id_local = '$id_local'");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            
            return true;
        }
        else{
            return false;
        }
    }

    public static function borrar_registros($id_usuario){
        global $baseDatos;
     
        
        $res = $baseDatos->query("DELETE FROM us_local WHERE id_usuarios = '$id_usuario'");  

        $res = $baseDatos->query($sql);
        return $res;   
        
    }


    public function getId_usuario_local()
    {
        return $this->id_usuario_local;
    }
    
    public function setId_usuario_local($id_usuario_local)
    {
        $this->id_usuario_local = $id_usuario_local;
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