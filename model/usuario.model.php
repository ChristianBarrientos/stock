<?php
class usuario {

	private $id_user;
    private $id_datos;
    private $id_contacto;
	private $usuario;
    private $pass;
    private $acceso;
    private $local_actual;
    private $id_acceso;
	
    public function __construct($usuario, $pass, $id_user = null,$id_datos = null,$id_contacto = null, $acceso = null, $local_actual = null)
    {
        $this->usuario = $usuario;
        $this->pass = $pass;
        $this->id_user = $id_user;
        $this->id_datos = $id_datos;
        $this->id_contacto = $id_contacto;
        $this->acceso = $acceso;

        $this->local_actual = $local_actual;
        
    }

    function verificar_user (){

        global $baseDatos;
        $user = $this->getUsuario();
        $pass = $this->getPass();
        $res = $baseDatos->query("SELECT * FROM usuarios WHERE usuario = '$user' AND pass = '$pass'");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $this->setId_user($res_fil['id_usuarios']);

            
            $this->setId_datos($this->obtener_us_datos($res_fil['id_datos']));
            $this->setId_contacto($this->obtener_us_prvd_contacto($res_fil['id_contacto']));
            $this->setAcceso($res_fil['acceso']);
            return true;
        }
        else{
            return false;
        }
        
    }

    function obtener_us_datos($id_datos = null ){
        global $baseDatos;
        if ($id_datos == null) {
            $id_datos = $this->getId_datos();
        }  

        $res = $baseDatos->query("SELECT * FROM us_datos WHERE id_datos = '$id_datos'");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $us_datos_foto = $this->obtener_us_datos_foto($res_fil['id_foto']);
            $us_datos_fecha_ab = $this->obtener_us_datos_fecha_ab($res_fil['id_fecha_ab']);
            $us_datos = new us_datos($res_fil['id_datos'], $res_fil['nombre'],$res_fil['apellido'],$res_fil['fecha_nac'],$res_fil['dni'],$us_datos_foto,$res_fil['genero'],$us_datos_fecha_ab['alta'],$us_datos_fecha_ab['baja']);
            $this->setId_datos($us_datos);
            return true;
        }
        else{
            return false;
        }
    }

    function obtener_us_datos_foto($id_foto){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_prvd_foto WHERE id_foto = $id_foto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return $res_fil['path_foto'];
        }
        else{
            return false;
        }
    }

    function obtener_us_datos_fecha_ab($id_fecha_ab){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_prvd_fecha_ab WHERE id_fecha_ab = $id_fecha_ab");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $fecha_ab = array('alta'  => $res_fil['alta'],'baja' => $res_fil['baja']);
            return $fecha_ab;
        }
        else{
            return false;
        }

    }

    function obtener_us_prvd_contacto($id_contacto = null){
        global $baseDatos;
         if ($id_contacto == null) {
             $id_contacto = $this->getId_contacto();
        } 
       

        $res = $baseDatos->query("SELECT * FROM us_prvd_contacto WHERE id_contacto = $id_contacto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $us_prvd_contacto_tel = $this->obtener_us_prvd_contacto_tel($res_fil['id_contacto_tel']);
           
            $us_contacto = new us_prvd_contacto($res_fil['id_contacto'], $res_fil['direccion'],$res_fil['correo'],$us_prvd_contacto_tel['nro_caracteristica'],$us_prvd_contacto_tel['nro_telefono']);
            $this->setId_contacto($us_contacto);
            return true;
        }
        else{
            return false;
        }
    }

    function obtener_us_prvd_contacto_tel($id_contacto_tel){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM us_prvd_contacto_tel WHERE id_contacto_tel = $id_contacto_tel");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $telefono = array('nro_caracteristica'  => $res_fil['nro_caracteristica'],'nro_telefono' => $res_fil['nro_telefono']);
            return $telefono;
        }
        else{
            return false;
        }
    }

    public static function obtener_locales($user){
        global $baseDatos;
        
        $id_user = $user->getId_user();

        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_usuarios = $id_user");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            $locales = array();
            $locales_empleados = array();
            $locales_articulos = array();
           // print_r($filas);
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
                $locales_empleados[] = art_local::generar_local_empleados($valor['id_local']);
                $locales [] = art_local::generar_local($valor['id_local'],count($locales_empleados[$clave]));
                $locales_articulos [] = $locales[$clave]->getId_local();
                
            }
            //if ($_SESSION["proveedores"] == false) {
                
            //    $_SESSION["proveedores"] = 0;
               
            //}else{  
                $_SESSION["proveedores"] = proveedor::obtener_prvd($id_user);
            //}
      
            $_SESSION["locales"] = $locales;
             
            $array_id_empelados = array();
             
            foreach ($locales_empleados as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    //array_push($array_id_empelados, $value4->getId_user())
                    $array_id_empelados[] = $value4->getId_user();

                }

                
            }
           
            $okok_empleados = array_unique($array_id_empelados);

            $okok_empleados_fin = array();
             
            foreach ($okok_empleados as $key5 => $value5) {
 
                $okok_empleados_fin[] = usuario::generar_usuario($value5);
            }
             
            $_SESSION["locales_empleados"] = $okok_empleados_fin;
         
            //$_SESSION["locales_empleados"] =  $locales_empleados; 
            //$_SESSION["locales_empleados"] = $locales_empleados;
            //$_SESSION["locales_articulos"] = $locales_articulos;
            
            /*foreach ($locales_empleados as $key => $value3) {
                foreach ($value3 as $key => $value4) {
                    print_r($value4->getId_user());
                }
                
            }
            echo "&&";
            foreach ($_SESSION["locales_empleados"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    print_r($value2->getId_user());
                }
                
            }*/
             
            return true;
        }
        else{
            return false;
        }
    }

    public static function obtener_locales_2($user){
        global $baseDatos;
        
        $id_user = $user->getId_user();

        $res = $baseDatos->query("SELECT * FROM us_local WHERE id_usuarios = $id_user");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            $locales = array();
            foreach ($filas as $clave => $valor) {

                $locales [] = art_local::generar_local($valor['id_local'],count($locales_empleados[$clave]));   
            }
            $_SESSION["locales"] = $locales;
             
            return true;
        }
        else{
            return false;
        }
    }

    public static function obtener_locales_empleado($id_empleado){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `us_local` WHERE `id_usuarios` = $id_empleado");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);

        if (count($filas) != 0) {
            $locales_empleados = array();
            foreach ($filas as $clave => $valor) {
                 
                $locales_empleados[] = array(
                            "id_us_local" =>  $valor['id_usuarios_local'],
                            "id_usuario" =>  $valor['id_usuarios'],
                            "id_zona" =>  $valor['id_zona'],
                        );
            }
            return $locales_empleados;
        }
        else{
            return false;
        }


    }

    public static function obtener_tabla_usuario($id_usuario){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM usuarios WHERE id_usuarios = $id_usuario");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $usuario = new usuario($res_fil['usuario'], $res_fil['pass']);
            $usuario -> obtener_us_datos($res_fil['id_datos']);
            $usuario -> obtener_us_prvd_contacto($res_fil['id_contacto']);
            $usuario -> setId_user($res_fil['id_usuarios']);
            $usuario -> setAcceso($res_fil['acceso']);
            return $usuario;
        }
        else{
            return false;
        }
    }

    public static function obtener_lote_us($id_user){
        global $baseDatos;
        
        //$res = $baseDatos->query("SELECT * FROM `lote_us` WHERE id_usuario = $id_user LIMIT 1000");  
        $res = $baseDatos->query("SELECT * FROM `lote_us` WHERE id_usuario = $id_user LIMIT 100");  
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            $lotes_us = array();
            //$lote_local = array();
            
            foreach ($filas as $clave => $valor) {
                $lotes_us[] = art_lote::generar_lote($valor['id_lote']);  
                //$lote_local[] = art_lote_local::generar_lote_local($valor['id_lote']); 
            }   
            //$_SESSION["lote_local"] = $lote_local;
            $_SESSION["lotes"] = $lotes_us;
            return true;
        }
        else{
           
            return false;
        }
    }

    public static function cantidad_locales($id_user){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT count(*) FROM `art_local`");  
        
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            return $res_fil;
        }else{
            return false;
        }  
    }

    public static function nombre_locales(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT nombre FROM `art_local` ");  
        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
            return $filas;
        }else{
            return false;
        }  
    }

    public static function cantidad_articulos(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT count(*) FROM `art_lote`");  
        
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            return $res_fil;
        }else{
            printf("Errormessage: %s\n", $baseDatos->error);
            return false;
        }
        
        
    }

    public static function cantidad_empleados(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT count(*) FROM `usuarios`");  
        
        $res_fil = $res->fetch_all(MYSQLI_ASSOC);
        if (count($res_fil) != 0) {
           
            return $res_fil[0]['count(*)'] - 1;
        }else{
            return false;
        }
        
        
    }
    
    public static function cantidad_proveedor(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT count(*) FROM `prvd_provedor`");  
        
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return $res_fil['count(*)'];
        }else{
            return false;
        }
  
    }
    

    public static function obtener_jefe($id_usuario_oper){
        global $baseDatos;
        $res = $baseDatos->query("SELECT `id_usuarios_local`, `id_usuarios`, `id_local` FROM `us_local` WHERE id_usuarios = $id_usuario_oper ");    
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $bandera = false;
            foreach ($filas as $clave => $valor) {
                //$local = art_local::generar_local($valor['id_zona']);
                 
                $usuarios_por_zona_us_locales = us_local::obtener_empleados_local($valor['id_local']);

                 
                if (count($usuarios_por_zona_us_locales) > 1) {
                    foreach ($usuarios_por_zona_us_locales as $key2 => $value2) {
                      
                        $user  = usuario::generar_usuario($value2['id_usuarios']);  
                        if (strcmp($user->getAcceso(), "ADMIN" ) == 0){
                            $id_admin = $user->getId_user();
                            $bandera = true;
                            break;
                        }
                    }
                }else{
                    $user  = usuario::generar_usuario($value2['id_usuarios']);  
                    if (strcmp($user->getAcceso(), "ADMIN" ) == 0){
                        $id_admin = $user->getId_user();
                        $bandera = true;
                        break;
                    }
                }
                
                if ($bandera == true) {
                    break;
                }
            }
            return $id_admin;
        }
        else{
            return false;
        }
    }

    public static function generar_usuario($id_usuarios){
        global $baseDatos;
        

        $res = $baseDatos->query("SELECT * FROM usuarios WHERE id_usuarios = $id_usuarios");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $id_datos = us_datos::generar_us_datos($res_fil['id_datos']);
            $id_contacto = us_prvd_contacto::generar_us_contacto($res_fil['id_contacto']);
            $user = new usuario($res_fil['usuario'],$res_fil['pass'],$res_fil['id_usuarios'],$id_datos,$id_contacto,$res_fil['acceso']);
            return $user;
        }
        else{
            return false;
        }

    }


    public static function alta_lote_us($id_lote){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        //$id_lote = art_lote::ultimo_id_lote();
        $id_usuario = $_SESSION['usuario']->getId_user();

        
        $sql = "INSERT INTO `lote_us`(`id_lote_us`, `id_usuario`, `id_lote`) VALUES (0,$id_usuario,$id_lote)";
        $res = $baseDatos->query($sql);
        if ($res) {
              
            return true;
        }else{
            echo "Alta Lote_Us";
            echo "\n";
            echo "Id_lote:";echo $id_lote;
            echo "id_usuario:";echo $id_usuario;
            
            printf("Errormessage: %s\n", $baseDatos->error);
            return false;
             
        }

    }


    public static function alta_usuario($id_dato,$id_contacto,$acceso,$usuario,$pass){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_usuario = usuario::ultimo_id_usuarios();
        $sql = "INSERT INTO `usuarios`(`id_usuarios`, `id_datos`, `id_contacto`, `acceso`, `usuario`, `pass`) 
                VALUES (0,$id_dato,$id_contacto,'$acceso','$usuario','$pass');";
        $res = $baseDatos->query($sql);
        return $id_usuario;


    }
      public static function ultimo_id_usuarios(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='usuarios'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    function verificar_existencia ($user){

        global $baseDatos;
         
        $res = $baseDatos->query("SELECT * FROM usuarios WHERE usuario LIKE '$user'");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return false;
        }
        else{
            
            return true;
        }
        
    }

    function verificar_dni ($dni){

        global $baseDatos;
         
        $res = $baseDatos->query("SELECT * FROM us_datos WHERE dni = $dni");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return false;
        }
        else{
            return true;
        }
        
    }


    public static function up_usuario($id_usuarios, $usuario){
        global $baseDatos;
        
        //`pass`=[value-6] WHERE 1
        $id_usuarios = 2;
        $sql = "UPDATE `usuarios` SET `usuario`='$usuario'  WHERE id_usuarios = $id_usuarios";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    public static function up_pass($id_usuarios, $pass){
        global $baseDatos;
        
        //`pass`=[value-6] WHERE 1
        $sql = "UPDATE `usuarios` SET `pass`='$pass'  WHERE id_usuarios = $id_usuarios";
        $res = $baseDatos->query($sql);
        return $res;   
    }

    public static function genera_temp_art(){
        global $baseDatos;


        //$sql = "CREATE TEMPORARY TABLE temp_art (id INTEGER,nombre varchar(50),prvd varchar(50),costo DEC(15,2),importe DEC(15,2),moneda DEC(15,2),cantidad varchar(20),PRIMARY KEY pk_id(id))";
        $sql = "CREATE TEMPORARY TABLE temp_art (id INTEGER,nombre varchar(50),costo DEC(15,2),importe DEC(15,2),moneda DEC(15,2),PRIMARY KEY pk_id(id))";

        $res = $baseDatos->query($sql);
        if ($res) {

            $sql = "INSERT INTO temp_art (id,nombre,costo,importe,moneda)
            SELECT art_lote.id_lote,art_tipo.nombre,art_lote.precio_base,art_lote.importe,art_moneda.valor
            FROM art_conjunto,art_lote,art_tipo,art_moneda WHERE (art_tipo.id_tipo = art_conjunto.id_tipo AND art_conjunto.id_art_conjunto = art_lote.id_art_conjunto)  AND art_lote.id_moneda = art_moneda.id_moneda";
            $res = $baseDatos->query($sql);
            if ($res) {

                
                 
            }else{
                //echo "ERROR al INsertar";
                //printf("Errormessage: %s\n", $baseDatos->error);
                 
            }
            
        }else{

            //echo "Error al crear Tabla";
            //printf("Errormessage: %s\n", $baseDatos->error);

        }


        return $res;

         


    }

    public static function genera_temp_art_cargar(){
        global $baseDatos;


        //$sql = "CREATE TEMPORARY TABLE temp_art (id INTEGER,nombre varchar(50),prvd varchar(50),costo DEC(15,2),importe DEC(15,2),moneda DEC(15,2),cantidad varchar(20),PRIMARY KEY pk_id(id))";
        $sql = "CREATE TEMPORARY TABLE temp_art_cargar (id INTEGER,nombre varchar(50),codigo varchar(50),PRIMARY KEY pk_id2(id))";

        $res = $baseDatos->query($sql);
        if ($res) {

            $sql = "INSERT INTO temp_art_cargar (id,nombre,codigo)
            SELECT art_lote.id_lote,art_tipo.nombre,art_lote.codigo_barras
            FROM art_conjunto,art_lote,art_tipo WHERE (art_tipo.id_tipo = art_conjunto.id_tipo AND art_conjunto.id_art_conjunto = art_lote.id_art_conjunto)";
            $res = $baseDatos->query($sql);
            if ($res) {
  
            }else{
                //echo "ERROR al INsertar";
                //printf("Errormessage: %s\n", $baseDatos->error);  
            }
            
        }else{

            //echo "Error al crear Tabla";
            //printf("Errormessage: %s\n", $baseDatos->error);

        }
        return $res;

    }

    public static function genera_temp_art_traslado(){
        global $baseDatos;


        //$sql = "CREATE TEMPORARY TABLE temp_art (id INTEGER,nombre varchar(50),prvd varchar(50),costo DEC(15,2),importe DEC(15,2),moneda DEC(15,2),cantidad varchar(20),PRIMARY KEY pk_id(id))";
        $sql = "CREATE TEMPORARY TABLE temp_art_traslado (id INTEGER,nombre varchar(50),deposito INTEGER,central INTEGER, III INTEGER, IV INTEGER)";

        $res = $baseDatos->query($sql);
        if ($res) {
            //Obtener array
            $sql_aux = "SELECT art_lote.id_lote,art_tipo.nombre,art_local.id_local,art_lote_local.cantidad_parcial            
                FROM art_conjunto,art_lote,art_tipo,art_local,art_lote_local 
                WHERE (art_tipo.id_tipo = art_conjunto.id_tipo AND art_conjunto.id_art_conjunto = art_lote.id_art_conjunto) 
                AND (art_lote.id_lote = art_lote_local.id_lote AND art_local.id_local = art_lote_local.id_local)";

            $res = $baseDatos->query($sql_aux);  
            $filas = $res->fetch_all(MYSQLI_ASSOC);

            print_r($filas);
            $array_final = array();
            foreach ($filas as $key => $value) { 
                $codigo = $value['id_lote'];  
                $nombre = $value['nombre'];  
                $local = $value['id_local'];  
                $local = $value['cantidad_parcial']; 

            }

            $sql = "INSERT INTO temp_art_traslado (id,nombre,local,cantidad)
            VALUES ()";
            $res = $baseDatos->query($sql);
            if ($res) {
  
            }else{
                echo "ERROR al Insertar";
                printf("Errormessage: %s\n", $baseDatos->error);  
            }
            
        }else{

            echo "Error al crear Tabla";
            printf("Errormessage: %s\n", $baseDatos->error);

        }
        return $res;

    }

    public static function obtener_temp_art(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `temp_art`");  
        
        $res_fil = $res->fetch_all();
        if (count($res_fil) != 0) {
           
            return $res_fil;
        }else{
            //printf("Errormessage: %s\n", $baseDatos->error);
            return false;
        }

    }

    public static function obtener_temp_art_cargar(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `temp_art_cargar`");  
        
        $res_fil = $res->fetch_all();
        if (count($res_fil) != 0) {
           
            return $res_fil;
        }else{
            //printf("Errormessage: %s\n", $baseDatos->error);
            return false;
        }

    }

    public static function elimina_temp_art(){
        global $baseDatos;
        $sql = "DROP TABLE temp_art";
        $res = $baseDatos->query($sql);
        return $res;

    }

    

    



    public function getId_user()
        {
            return $this->id_user;
        }
        
    public function setId_user($id_user)
        {
            $this->id_user = $id_user;
            return $this;
        }    
    public function getId_datos()
    {
        return $this->id_datos;
    }
    
    public function setId_datos($id_datos)
    {
        $this->id_datos = $id_datos;
        return $this;
    }

    public function getId_contacto()
    {
        return $this->id_contacto;
    }
    
    public function setId_contacto($id_contacto)
    {
        $this->id_contacto = $id_contacto;
        return $this;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
    
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getPass()
    {
        return $this->pass;
    }
    
    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    public function getAcceso()
    {
        return $this->acceso;
    }
    
    public function setAcceso($acceso)
    {
        $this->acceso = $acceso;
        return $this;
    }

    public function getLocal_Actual()
    {
        return $this->local_actual;
    }
    
    public function setLocal_Actual($local_actual)
    {
        $this->local_actual = $local_actual;
        return $this;
    }

    public function setId_Acceso($id_acceso)
    {
        $this->id_acceso = $id_acceso;
        return $this;
    }

    public function getId_Acceso()
    {
        return $this->id_acceso;
    }
}
?>