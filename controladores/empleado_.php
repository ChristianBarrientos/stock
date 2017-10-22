<?php
class Empleado_Controller{

	public static function cargar_empleado(){
		if (isset($_SESSION["usuario"])){
        	if ($_SESSION["permiso"] == 'ADMIN') {
        		$tpl = new TemplatePower("template/cargar_empleado.html");
				$tpl->prepare();
				

				foreach ($_SESSION['locales'] as $key => $value) {
                        $tpl->newBlock("locales_empleado_alta");
                        $cadena = $value->getId_zona();
                        $direccion = after_last (',', $cadena);
                        $tpl->assign("id_local", $direccion);
                        $tpl->assign("nombre_local", $value->getNombre());
                                
                                
                }
                if (count($_SESSION['locales']) == 0) {
                	$tpl->newBlock("sin_locales_empl");
                	$tpl->newBlock("sin_locales_empl_2");
                }else{
                	$tpl->newBlock("con_locales_empl");
                }
				
        	}
        	
        }
        else{
       		return Ingreso_Controller::salir();
		}

		return $tpl->getOutputContent();

	}

	public static function menu(){
			if (Ingreso_Controller::es_admin()) {
				$tpl = new TemplatePower("template/seccion_admin_empleado.html");
				$tpl->prepare();
			$empleados_si = false;

			if ($_SESSION['usuario']::obtener_locales($_SESSION['usuario'])) {

				

				foreach ($_SESSION["locales_empleados"] as $key => $value) {

					foreach ($_SESSION["locales_empleados"][$key] as $clave => $valor) {
						//print_r($valor->getId_datos()->getFecha_alta());

						if ($valor->getAcceso() == 'OPER') {
							$empleados_si = true;
							$tpl->newBlock("con_empleados");
							$tpl->assign("empl_nombre", $valor->getId_datos()->getNombre().' '.$valor->getId_datos()->getApellido());
                            $fecha_alta = $valor->getId_datos()->getFecha_alta();
                            //if (strcmp($fecha_alta, "0000-00-0" ) == 0 ) {
                            if ($fecha_alta ==  0000-00-0 ) {
                                $tpl->assign("empl_fecha_alta", 'Sin Definir.');
                            }else{

                                $tpl->assign("empl_fecha_alta", $fecha_alta);    
                            }
							$empl_dni = $valor->getId_datos()->getDni();
                            if ($empl_dni == null) {
                                $tpl->assign("empl_dni", 'Sin Definir.');
                            }
                            else{
                                $tpl->assign("empl_dni",$empl_dni );
                            }
							$fecha_nac = $valor->getId_datos()->getFecha_nac();
                            if ($fecha_nac == 0000-00-0) {
                                $tpl->assign("empl_fecha_nac", 'Sin Definir.');
                            }
                            else{
                                $tpl->assign("empl_fecha_nac",$fecha_nac );
                            }
							$empl_direccion = $valor->getId_contacto()->getDireccion();
                            if ($empl_direccion == 'NULL') {
                                $tpl->assign("empl_direccion", 'Sin Definir.');
                            }else{
                                $tpl->assign("empl_direccion",$empl_direccion );
                            }
							$empl_correo = $valor->getId_contacto()->getCorreo();
                            if ($empl_correo == 'NULL') {
                                $tpl->assign("empl_correo", 'Sin Definir.');
                            }
                            else{
                                $tpl->assign("empl_correo",$empl_correo );
                            }
							$empl_telefono = $valor->getId_contacto()->getNro_caracteristica().'-'.$valor->getId_contacto()->getNro_telefono();
                            if ($empl_telefono == '0-') {
                                $tpl->assign("empl_telefono", 'Sin Definir.');
                            }else{
                                $tpl->assign("empl_telefono",$empl_telefono );
                            }
							
							$tpl->assign("empl_foto", $valor->getId_datos()->getFoto());

                            $tpl->assign("id_empleado", $valor->getId_user());
						}
					}	
				}
				
				

			}
            
			if (!($empleados_si)) {
				$tpl->newBlock("sin_empleados");
			}
			
			

			}
			else{
				return Ingreso_Controller::salir();
			}
		
			return $tpl->getOutputContent();
	}


    public static function alta_empelado(){
        $nombre = ucwords(strtolower($_POST['empl_nombre']));
        $apellido = ucwords(strtolower($_POST['empl_apellido']));
        $genero = $_POST['empl_genero'];
        $dni = $_POST['empl_dni'];
        $fecha_nac = $_POST['empl_fecha_nac'];
        $fecha_alta = $_POST['empl_fecha_alta'];
        $direccion = ucwords(strtolower($_POST['empl_direccion']));
        $correo = ucwords(strtolower($_POST['empl_correo']));
        $telefono = $_POST['empl_telefono'];
        $usuario = $_POST['empl_usuario'];
        $pass = $_POST['empl_pass'];
        $locales = $_POST['empl_local'];
        if ($dni == null) {
            $dni = 'NULL';
        }
        if ($fecha_nac == null) {
            $fecha_nac = 'NULL';
        }
        if ($fecha_alta == null) {
            $fecha_alta = 'NULL';
        }
        if ($direccion == null) {
            $direccion = 'NULL';
        }
        if ($correo == null) {
            $correo = 'NULL';
        }
        if ($telefono == null) {
            $telefono = 'NULL';
        }
        //Cargar en tabla us_datos
        //ucwords(strtolower($_POST['empl_correo']))
        /*echo "FECHA ALTA:";
        echo $fecha_alta;
        echo "NOMBRE";
        echo $nombre;
        echo "APELLIDO";
        echo $apellido;
        echo "FECHA ANCIMIENTO";
        echo $fecha_nac;
        echo "GENERO";
        echo $genero;
        echo "DNI";
        echo $dni;
        echo "%%";
        echo $direccion;
        echo $correo;
        echo $telefono;
        */
        $id_datos = us_datos::alta_datos($fecha_alta,$nombre,$apellido,$fecha_nac,$dni,2,$genero);
        $id_contacto = us_prvd_contacto::alta_contacto($direccion,$correo,$telefono);
          
        if ($id_datos && $id_contacto) {
            $id_usuario = usuario::alta_usuario($id_datos,$id_contacto,'OPER',$usuario,$pass);
            if ($id_usuario != 'null' OR $id_usuario != 0) {
                 
                foreach ($locales as $key) {
                     
                    $id_zona = mp_zona::obtener_zona($key);
                    us_local::agregar_us_a_local($id_usuario,$id_zona);
                }
                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
            }
            else{
                echo "malusuario";
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
            }
        }
        else{
            echo "maldatoscontacto";
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
        return $tpl->getOutputContent();
    }

	/*public static function alta_empelado(){

		$nombre = ucwords(strtolower($_POST['empl_nombre']));
        $apellido = ucwords(strtolower($_POST['empl_apellido']));
        $genero = $_POST['empl_genero'];
        $dni = $_POST['empl_dni'];
        $fecha_nac = $_POST['empl_fecha_nac'];
        $fecha_alta = $_POST['empl_fecha_alta'];
        $direccion = ucwords(strtolower($_POST['empl_direccion']));
 		$correo = ucwords(strtolower($_POST['empl_correo']));
        $telefono = $_POST['empl_telefono'];
        $usuario = $_POST['empl_usuario'];
        $pass = $_POST['empl_pass'];
        $locales = $_POST['empl_local'];
        if ($dni == null) {
            $dni = 'NULL';
        }
        if ($fecha_nac == null) {
            $fecha_nac = 'NULL';
        }
        if ($fecha_alta == null) {
            $fecha_alta = 'NULL';
        }
        if ($direccion == null) {
            $direccion = 'NULL';
        }
        if ($correo == null) {
            $correo = 'NULL';
        }
        if ($telefono == null) {
            $telefono = 'NULL';
        }

        
        $us_existe = usuario::verificar_existencia($usuario);
        $dni_existe = usuario::verificar_dni($dni);
        if ($us_existe && $dni_existe) {
            $id_datos = us_datos::alta_datos($fecha_alta,$nombre,$apellido,$fecha_nac,$dni,2,$genero);
            $id_contacto = us_prvd_contacto::alta_contacto($direccion,$correo,$telefono);
        
            $id_usuario = usuario::alta_usuario($id_datos,$id_contacto,'OPER',$usuario,$pass);
            
        	
        	if ($id_usuario != 'null' OR $id_usuario != 0) {
        		 
        		foreach ($locales as $key) {
        			 
        			$id_zona = mp_zona::obtener_zona($key);
        			us_local::agregar_us_a_local($id_usuario,$id_zona);
        		}

        		$tpl = new TemplatePower("template/exito.html");
				$tpl->prepare();
        	}
        	else{
        		
        		
                echo "maldatoscontacto";
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();

        	}
        }
        else{
            echo "malusuariodni";
        	$tpl = new TemplatePower("template/cargar_empleado.html");
            $tpl->prepare();
            $tpl->newBlock("error_usuario");
        }
        return $tpl->getOutputContent();
	}*/
	
    function modificar(){
        $id_usuario = $_GET['id_empleado'];
        $tpl = new TemplatePower("template/modificar_empleado.html");
        $tpl->prepare();
        

        foreach ($_SESSION["locales_empleados"] as $key => $value) {

                foreach ($_SESSION["locales_empleados"][$key] as $clave => $valor) {
                        //print_r($valor->getId_datos()->getFecha_alta());
                   
                    if ($id_usuario == $valor->getId_user()) {
                        $nombre_ = $valor->getId_datos()->getNombre();
                        $apellido_ = $valor->getId_datos()->getApellido();
                        $dni_ = $valor->getId_datos()->getDni();
                        $fecha_alt_ = $valor->getId_datos()->getFecha_alta();
                        $genero_ = $valor->getId_datos()->getGenero();
                        $fecha_nac_ =  $valor->getId_datos()->getFecha_nac();
                        $direccion_ = $valor->getId_contacto()->getDireccion();
                        $correo_ = $valor->getId_contacto()->getCorreo();
                        $telefono_ = $valor->getId_contacto()->getNro_caracteristica().'-'.$valor->getId_contacto()->getNro_telefono();
                        $usuario_ = $valor->getUsuario();
                        $pass_ = $valor->getPass();

                        $tpl->newBlock("form");
                        $tpl->assign("id_empleado", $id_usuario);
                        $tpl->assign("nombre__", $nombre_);
                        $tpl->assign("apellido__", $apellido_);
                        $tpl->assign("dni__", $dni_);
                        $tpl->assign("fecha_alt__", $fecha_alt_);
                        $tpl->assign("fecha_nac__", $fecha_nac_);
                        $tpl->assign("direccion__", $direccion_);
                        $tpl->assign("correo__", $correo_);
                        $tpl->assign("telefono__", $telefono_);
                        $tpl->assign("usuario__", $usuario_);
                        $tpl->assign("pass__", $pass_);
                        $tpl->assign("genero__", $genero_);

                        $tpl->newBlock("datos_modificar_empleado");

                        $tpl->assign("nombre_", $nombre_);
                        $tpl->assign("apellido_", $apellido_);
                        $tpl->assign("dni_", $dni_);
                        $tpl->assign("fecha_alt_", $fecha_alt_);
                        $tpl->assign("fecha_nac_", $fecha_nac_);
                        $tpl->assign("direccion_", $direccion_);
                        $tpl->assign("correo_", $correo_);
                        $tpl->assign("telefono_", $telefono_);
                        $tpl->assign("usuario_", $usuario_);
                        $tpl->assign("pass_", $pass_);
                        //$tpl->assign("genero_", $genero_);


                    }
                    
                }
                foreach ($_SESSION['locales'] as $key => $value) {
                    $tpl->newBlock("locales_empleado_alta");
                    $cadena = $value->getId_zona();
                    $direccion = after_last (',', $cadena);
                    $tpl->assign("id_local", $direccion);
                    $tpl->assign("nombre_local", $value->getNombre());
                }
        }   
    return $tpl->getOutputContent();    

    }

    public static function alta_modificacion(){
        $id_usuario_empleado = $_GET['id_empleado'];

        $nombre__ = ucwords(strtolower($_GET['nombre__']));
        $apellido__ = ucwords(strtolower($_GET['apellido__']));
        $genero__ = $_GET['genero__'];
        $dni__ = $_GET['dni__'];
        $fecha_nac__ = $_GET['fecha_nac__'];
        $fecha_alta__ = $_GET['fecha_alt__'];
        $direccion__ = ucwords(strtolower($_GET['direccion__']));
        $correo__ = ucwords(strtolower($_GET['correo__']));
        $telefono__ = $_GET['telefono__'];
        $usuario__ = $_GET['usuario__'];
        $pass__ = $_GET['pass__'];
        $locales__ = $_GET['locales__'];

        $datos_viejos = array($nombre__,$apellido__,$genero__,$dni__,$fecha_nac__,$fecha_alta__,$direccion__,$correo__,$telefono__,$usuario__,$pass__,$locales__);
                                
                                 
        $nombre = ucwords(strtolower($_POST['empl_nombre']));
        $apellido = ucwords(strtolower($_POST['empl_apellido']));
        $genero = $_POST['empl_genero'];
        $dni = $_POST['empl_dni'];
        $fecha_nac = $_POST['empl_fecha_nac'];
        $fecha_alta = $_POST['empl_fecha_alta'];
        $direccion = ucwords(strtolower($_POST['empl_direccion']));
        $correo = ucwords(strtolower($_POST['empl_correo']));
        $telefono = $_POST['empl_telefono'];
        $usuario = $_POST['empl_usuario'];
        $pass = $_POST['empl_pass'];
        $locales = $_POST['empl_local'];

        $datos_nuevos = array($nombre,$apellido,$genero,$dni,$fecha_nac,$fecha_alta,$direccion,$correo,$telefono,$usuario,$pass,$locales);
         
        $locales_empleado = usuario::obtener_locales_empleado($id_usuario_empleado);
        foreach ($_SESSION["locales_empleados"] as $key => $value) {

                foreach ($_SESSION["locales_empleados"][$key] as $clave => $valor) {
                        //print_r($valor->getId_datos()->getFecha_alta());
                   
                    if ($id_usuario_empleado == $valor->getId_user()) {
                         
                        $id_datos = $valor->getId_datos()->getId_datos();
                        $id_contacto = $valor->getId_contacto()->getId_contacto();
                         
                    }
                }
        }
        for ($i=0; $i < count($datos_nuevos) ; $i++) { 
            $ok_up = true;
            if ($datos_nuevos[$i] == $datos_viejos[$i]) {
                
                //no pasa naa
            }
            else{
                
                switch ($i) {
                    case 0:
                        # cambiar nombre
                         
                        $ok_up = us_datos::up_nombre($id_datos,$datos_nuevos[$i]);
                        break;
                    case 1:
                        # cambiar apellido
                        $ok_up = us_datos::up_apellido($id_datos,$datos_nuevos[$i]);
                        break;
                    case 2:
                        # cambiar genero
                        $ok_up = us_datos::up_genero($id_datos,$datos_nuevos[$i]);
                        break;
                    case 3:
                        # cambiar dni
                        $ok_up = us_datos::up_dni($id_datos,$datos_nuevos[$i]);
                        break;
                    case 4:
                        # cambiar fecha_nac
                        $ok_up = us_datos::up_fecha_nac($id_datos,$datos_nuevos[$i]);
                        break;
                    case 5:
                        # cambiar fecha_alta
                        //us_datos::up_fecha_alta($id_datos,$datos_nuevos[$i]);
                        break;
                    case 6:
                        # cambiar direccion
                        $ok_up = us_prvd_contacto::up_direccion($id_contacto,$datos_nuevos[$i]);
                        break;
                    case 7:
                        # cambiar correo
                        $ok_up = us_prvd_contacto::up_correo($id_contacto,$datos_nuevos[$i]);
                        break;
                    case 8:
                        # cambiar telefono
                        break;
                    case 9:
                        # cambiar usuario
                        $ok_up = usuario::up_usuario($id_usuario_empleado,$datos_nuevos[$i]);
                        break;
                    case 10:
                        # cambiar pass
                        $ok_up = usuario::up_pass($id_usuario_empleado,$datos_nuevos[$i]);
                        break;
                    case 11:
                        # cambiar locales
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
        }

        if ($ok_up) {
                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
            }
        else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();

        }

        return $tpl->getOutputContent();
    }

    function art_vender_empelado(){
        $tpl = new TemplatePower("template/exito.html");
        $tpl->prepare();
    }
}
?>