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

                        $tpl->assign("id_local", $value->getId_local());
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
            $tpl = new TemplatePower("template/seccion_admin_empleado.html");
            $tpl->prepare();
			if (Ingreso_Controller::es_admin()) {
				
			    $empleados_si = false;
            }else{
                return Ingreso_Controller::salir();

            }
			if ($_SESSION['usuario']::obtener_locales($_SESSION['usuario'])) {

				
				foreach ($_SESSION["locales_empleados"] as $clave => $valor) {

					//foreach ($_SESSION["locales_empleados"][$key] as $clave => $valor) {
						
                        

						if ($valor->getAcceso() == 'OPER') {
                            
							$empleados_si = true;
							$tpl->newBlock("con_empleados");
							$tpl->assign("empl_nombre",$valor->getId_datos()->getNombre().' '.$valor->getId_datos()->getApellido());
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
					//}	
				}
				
				

			}
            
			if (!($empleados_si)) {
				$tpl->newBlock("sin_empleados");
			}
			//else{
			//	return Ingreso_Controller::salir();
			//}
		
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

        $aguinaldo = $_POST['empl_aguinaldo'];
        $usuario = $_POST['empl_usuario'];
        $pass = $_POST['empl_pass'];
        $locales = $_POST['empl_local'];

        $sueldo = $_POST['empl_sueldo'];

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
       
        $id_datos = us_datos::alta_datos($fecha_alta,$nombre,$apellido,$fecha_nac,$dni,2,$genero);
        $id_contacto = us_prvd_contacto::alta_contacto($direccion,$correo,$telefono);
          
        if ($id_datos && $id_contacto) {
            //Preguntar si existe usuario con el mismo nombre de user
            $okuser = usuario::verificar_existencia($usuario);
            if ($okuser) {
                $id_usuario = usuario::alta_usuario($id_datos,$id_contacto,'OPER',$usuario,$pass);
            }
            
            if ($id_usuario != 'null' OR $id_usuario != 0 && $okuser) {
                 
                foreach ($locales as $key) {
                     
                    $id_zona = mp_zona::obtener_zona($key);
                    us_local::agregar_us_a_local($id_usuario,$key);
                }
                //Preguntar si tiene grupo de gastos definido
                $id_jefe = $_SESSION['usuario']->getId_user();

                $us_gastos = us_gastos::obtener($id_jefe);
                if ($us_gastos == null) {
                    # code...
                    //Generar Gasto desde 0
                    $id_gs_des = gs_descripcion::alta('Sueldos','Sueldos de los empleados');
                    $hoy = getdate();
                    $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
                    $id_gasto_unico = gs_gasto_unico::alta($nombre,$sueldo,'true',$ahora);
                    
                    $id_gs_grupo = gs_grupo::alta($id_gasto_unico);
                    
                    $id_gasto = gs_gastos::alta('Sueldos',$id_gs_des,$id_gs_grupo);
                    $id_us_ggs = us_ggs::alta($id_gasto);
                    $okok = us_gastos::alta($id_jefe,$id_us_ggs);

                }else{
                    
                    $us_ggs = $us_gastos->getId_us_ggs();

                    foreach ($us_ggs as $key => $value) {
                        # code...
                        $gasto = $value->getId_gasto();
                        $nombregs = $gasto->getNombre();
                        if (strcmp($nombregs, "Sueldos" ) == 0) {
                             
                            $id_gasto_sueldos = $value->getId_gasto();

                            break;
                        }else{
                            //Crear Gastos Sueldos
                            //Preguntar si tiene gastos el usuario

                            $id_gasto_sueldos = null;
                        }
                    }
                    //Preguntar si existe un gasto con el nombre de Sueldos
                    if ($id_gasto_sueldos != null) {
                        # code...
                        //Agregar Gasto al grupo

                        $hoy = getdate();
                        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
                        $id_gasto_unico = gs_gasto_unico::alta($nombre,$sueldo,'true',$ahora);
                        
                        $id_ggs = $id_gasto_sueldos->getId_ggs()->getId_ggs();
                        $okok = gs_grupo::agrega($id_ggs,$id_gasto_unico);
                       
                        if ($okok) {
                            # code...
                            echo "Bien";
                        }else{
                            echo "Mal";
                        }



                    }else{
                        $id_gs_des = gs_descripcion::alta('Sueldos','Sueldos de los empleados');
                

                        //Crear gasto
                        $hoy = getdate();
                        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
                        $id_gasto_unico = gs_gasto_unico::alta($nombre,$sueldo,'true',$ahora);

                        $id_ggs = gs_grupo::alta($id_gasto_unico);

                        $id_gasto = gs_gastos::alta('Sueldos',$id_gs_des,$id_ggs);

                        //Agregar a us_ggs
                        
                        $id_us_ggs = $us_ggs[0]->getId_us_ggs();
                        $okok = us_ggs::agrega($id_us_ggs,$id_gasto);
                        
                        //Crear Gasto 


                    }
                    
                }

                if ($okok && $okuser) {
                    //Agregar gasto unico a us_gmv
                    $id_gmv = us_gmv::alta($id_gasto_unico);
                    //Agregar gmv a us_sueldo
                    if ($aguinaldo == 1 OR $aguinaldo == 0) {
                        $id_us_sueldos = us_sueldos::alta($id_usuario,$id_gmv,$sueldo,$aguinaldo);
                    }else{
                        return Ingreso_Controller::salir();
                    }
                    
                    if ($id_us_sueldos) {
                        # code...
                        $tpl = new TemplatePower("template/exito.html");
                        $tpl->prepare();
                    }else{
                        $tpl = new TemplatePower("template/error.html");
                        $tpl->prepare();
                    }
                    
                }else{
                    if (!$okuser) {
                        # code...
                        $tpl = new TemplatePower("template/cargar_empleado.html");
                        $tpl->prepare();
                        $tpl->newBlock("error_usuario");
                        

                    }else{
                        $tpl = new TemplatePower("template/error.html");
                        $tpl->prepare();
                    }
                    
                }
               
            }
            else{
               
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
            }
        }
        else{
            
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
        		
        		
               
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();

        	}
        }
        else{
            
        	$tpl = new TemplatePower("template/cargar_empleado.html");
            $tpl->prepare();
            $tpl->newBlock("error_usuario");
        }
        return $tpl->getOutputContent();
	}*/
	
    function modificar($mal_local = false){
        $id_usuario = $_GET['id_empleado'];
        $tpl = new TemplatePower("template/modificar_empleado.html");
        $tpl->prepare();
        
        
        foreach ($_SESSION["locales_empleados"] as $key => $valor) {
             
               // foreach ($value as $clave => $valor) {
                       
                   
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

                        //obtener sueldo
                        $us_sueldos = us_sueldos::obtener($id_usuario);
                    
                        $sueldo_base = $us_sueldos->getBasico();
                  
                        $aguinaldo = $us_sueldos->getAguinaldo();
                        echo $aguinaldo;

                        $tpl->newBlock("form");
                        $tpl->assign("id_empleado", $id_usuario);
                        $tpl->assign("nombre__", $nombre_);
                        $tpl->assign("apellido__", $apellido_);
                        $tpl->assign("dni__", $dni_);
                        $tpl->assign("fecha_alt__", $fecha_alt_);
                        $tpl->assign("fecha_nac__", $fecha_nac_);
                        $tpl->assign("sueldo_base", $sueldo_base);

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
                        $tpl->assign("sueldo_base_", $sueldo_base);
                        if ($aguinaldo == true) {
                            
                            
                            $tpl->assign("selected_si", 'selected="selected"');
                             
                        }else{
                            $tpl->assign("selected_no", 'selected="selected"');
                        }

                        $tpl->assign("direccion_", $direccion_);
                        
                        if ($correo_ == 'NULL') {
                            $tpl->assign("correo_", '');
                        }else{
                            $tpl->assign("correo_", $correo_);
                        }
                        $tpl->assign("telefono_", $telefono_);
                        $tpl->assign("usuario_", $usuario_);
                        $tpl->assign("pass_", $pass_);
                        //$tpl->assign("genero_", $genero_);


                    }

                    
                }

                if ($mal_local) {
                    $tpl->newBlock("local_no_selecciono");
                }
                foreach ($_SESSION['locales'] as $key => $value) {

                    $tpl->newBlock("locales_empleado_alta");
                    $cadena = $value->getId_zona();
                    $id_local = $value->getId_local();
                    $direccion = after_last (',', $cadena);
                    $tpl->assign("id_local", $value->getId_local());
                    $tpl->assign("nombre_local", $value->getNombre());

                  
                    foreach ($_SESSION["locales_empleados"] as $key2 => $value2) {
                       
                       
                        if ($id_usuario == $value2->getId_user()) {
                            $local = art_local::generar_local($value->getId_local());
                            $id_zona = $local->getId_local();
                           
                            if (us_local::empleado_local_esta($id_usuario,$id_local)) {
                                # code...
                                
                                $tpl->assign("checked_sel", 'checked');
                            }
                        }
                        # code...
                    }
                }
        //}   
    return $tpl->getOutputContent();    

    }

    public static function alta_modificacion(){
 
        $id_usuario_empleado = $_GET['id_empleado'];
        $sueldo_base = $_POST['empl_sueldo'];
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
        $aguinaldo = $_POST['empl_aguinaldo'];   
                 
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
 
       
        $error = false;
       
        //Borrar en us_local
       
        if (count($locales) <= 0) {
          
            return Empleado_Controller::modificar(true);
        }
        $borrado = us_local::borrar_registros($id_usuario_empleado);
         
        if (!($borrado)) {
            //Cargar Nuevos Locales
            foreach ($locales as $clave => $valor) {

                $local = art_local::generar_local_3($valor);
                 
                $id_zona = $local->getId_zona()->getId_zona();
                $alta_nueva_locales = us_local::agregar_us_a_local($id_usuario_empleado,$id_zona);
              
                if ($alta_nueva_locales) {
                    # code...
                }else{
                  
                    $error = true;
                    break;
                }
            }
            
        }
        
        if (!(usuario::verificar_existencia($usuario)) && $usuario != $usuario__) {
               
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
                return $tpl->getOutputContent();
            
            
        }
        if ($error)
            {
                
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
                return $tpl->getOutputContent();
            }

        $datos_nuevos = array($nombre,$apellido,$genero,$dni,$fecha_nac,$fecha_alta,$direccion,$correo,$telefono,$usuario,$pass,$locales);
         
        $locales_empleado = usuario::obtener_locales_empleado($id_usuario_empleado);
        /*foreach ($_SESSION["locales_empleados"] as $key => $value) {

                foreach ($_SESSION["locales_empleados"][$key] as $clave => $valor) {
                      
                   
                    if ($id_usuario_empleado == $valor->getId_user()) {
                        
                        $id_datos = $valor->getId_datos()->getId_datos();
                        $id_contacto = $valor->getId_contacto()->getId_contacto();
                         
                    }
                }
        }*/

     
        $ser_empleado = usuario::generar_usuario($id_usuario_empleado);
        $id_datos = $ser_empleado->getId_datos()->getId_datos();
        $id_contacto = $ser_empleado->getId_contacto()->getId_contacto();
        
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
            //Update en us sueldos
                $us_sueldos = us_sueldos::obtener($id_usuario_empleado);
                $id_sueldo = $us_sueldos->getId();
               
                $okok = us_sueldos::update($id_sueldo,'basico',$sueldo_base);
                if ($okok) {
                    $okok = us_sueldos::update($id_sueldo,'aguinaldo',$aguinaldo);
                }
                

                if ($okok) {
                    //Actualizacion de Gastos
                    $id_gmv = $us_sueldos->getId_gmv();
                    $gmv = $id_gmv->getId_gs_mv(); 
                    $gs_uncio_habilitados = array();
                    foreach ($gmv as $key => $value) {
                        $estado = $value->getHabilitado();
                        if ($estado) {
                            //Verdadero esta habilitado, se guarda-
                            $id_gs_unico = $value->getId_gasto_unico();
                            $gs_uncio_habilitados[] = $value->getId_gasto_unico();
                         
                            $okok = $value->update($id_gs_unico,'valor',$sueldo_base);
                        }
                    }

                    //Solo deberia haber un solo gasto sueldo de un empleado habilitado, si exsiten mas de dos es xq no se liquido sueldo anteriormente.   
                }
                if ($okok) {
                        $tpl = new TemplatePower("template/exito.html");
                        $tpl->prepare(); 
                    }
                else{
                    $tpl = new TemplatePower("template/error.html");
                    $tpl->prepare();
                }
               
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

    function liquidar_sueldo(){
        if (Ingreso_Controller::es_admin()) {
            $us_sueldos = us_sueldos::obtener();
            if ($us_sueldos) {
                $counter = 1;
                $tpl = new TemplatePower("template/seccion_admin_sueldos.html");
                $tpl->prepare();
                $tpl->newBlock("lista_sueldo");
                foreach ($us_sueldos as $key => $value) {

                    $tpl->newBlock("lista_datos_sueldos");
                    $nombre = $value->getId_usuario()->getId_datos()->getNombre();
                    $apellido = $value->getId_usuario()->getId_datos()->getApellido();
                    $basico = $value->getBasico();
                    $total_anticipos = 0;
                    $anticipos = $value->getId_gmv()->getId_gs_mv()[0]->getId_gsub_gasto();
                    //print_r($anticipos);
                    //->getId_sub_gasto()
                    if ($anticipos != null) {
                        # code...
                        $anticipos = $anticipos->getId_sub_gasto();
                        foreach ($anticipos as $key2 => $value2) {
                            $valor_subgasto = $value2->getValor();
                            $total_anticipos = $total_anticipos + $valor_subgasto;
                        }
                    }else{
                        $total_anticipos = 0;
                    }
                    
                    $aguinaldo = $value->getAguinaldo();
                    if ($aguinaldo == true) {
                        $id_user = $value->getId_usuario()->getId_user();
                        if ($id_user == 21) {
                            $aguinaldo = 7500;
                        }else{

                         $aguinaldo = $basico/2;
                        }
                    }else{
                        $aguinaldo = 'NO';
                    }
                    $neto = $basico - $total_anticipos + $aguinaldo;
                    $tpl->assign("numero", $counter);
                    $tpl->assign("nombre", $nombre.','.$apellido);
                    $tpl->assign("basico", $basico);
                    $tpl->assign("anticipos", $total_anticipos);
                    $tpl->assign("aguinaldo", $aguinaldo);
                    $tpl->assign("neto", $neto);

                    $counter = $counter + 1;


                }
            }else{
                echo "mal";
            }
            


        }else{

            return Ingreso_Controller::salir();
        }
        return $tpl->getOutputContent();
        
    }
}
?>