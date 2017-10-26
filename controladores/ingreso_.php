<?php
class Ingreso_Controller{


	public static function login (){
        
        if (isset($_SESSION["usuario"])){
        	if ($_SESSION["permiso"] == 'ADMIN') {
        		return Ingreso_Controller::menu_admin();
        	}

        	if ($_SESSION["permiso"] == 'OPER') {
        		return Ingreso_Controller::menu_operador();
        	}
        	
        	
        }
        else{
        	$tpl = new TemplatePower("template/login.html");
			$tpl->prepare();
			return $tpl->getOutputContent();
        }
		

	}

	public static function menu_bar ($seccion){
        
        $active = "class='active'";
		$tpl = new TemplatePower("template/menu_bar.html");
		$tpl->prepare();
		
		if (isset($_SESSION["usuario"]) && Ingreso_Controller::es_admin()){
			$tpl->newBlock("dentro"); 
			if ($seccion == "Local::mostrar") {
				     
		   			$tpl->assign("select_locales", $active);

			}
			if ($seccion == "Ingreso::menu_admin" OR $seccion == "Ingreso::index.php" OR $seccion == "Ingreso::login") {
				     
		   			$tpl->assign("select_menu", $active);

			}
			if ($seccion == "Empleado::menu" ) {
				     
		   			$tpl->assign("select_empleados", $active);

			}
			if ($seccion == "Articulo::mostrar" ) {
				     
		   			$tpl->assign("select_articulos", $active);

			}
			if ($seccion == "Proveedor::menu" ) {
				     
		   			$tpl->assign("select_proveedores", $active);

			}

			/*$usuario = $_SESSION['usuario'];
			 $usuario = unserialize($usuario);
			 //$usuario::obtener_locales($usuario);*/
			 
			$tpl->assign("usuario", $_SESSION['usuario']->getUsuario());
		}
		if ($_SESSION["permiso"] == 'OPER') {
			$tpl->newBlock("operador"); 
			$tpl->assign("usuario", $_SESSION["usuario"]->getUsuario());
			$tpl->assign("select_menu", $active);
		}
		else{
			$tpl->newBlock("fuera");    
		}
			
			
		return $tpl->getOutputContent();

	}

	public static function verificar_usuario(){

		//$filtro = new InputFilter();
		//$nombre = $filtro->process($_POST['usuario']);
		if (isset($_POST['usuario']) && isset($_POST['pass'])) {

			 
			/*$user = mysqli_real_escape_string($baseDatos,$_POST['usuario']);
			$pass = mysqli_real_escape_string($baseDatos,$_POST['pass']);*/
			$user =  $_POST['usuario'];
			$pass =  $_POST['pass'];
			/*echo $user;
			$user = htmlentities($_GET["usuario"], ENT_QUOTES);
			$pass = htmlentities($_GET["pass"], ENT_QUOTES);
			echo $user;*/
			//ucwords(strtolower($bar));
			//$nombre = $baseDatos->real_escape_string($_POST['nombre']);
			$usuario = new usuario($user, $pass);
			if($usuario -> verificar_user()){
				
				//if ($usuario -> obtener_us_datos() && $usuario -> obtener_us_prvd_contacto()) {
				$usuario -> obtener_us_datos();
				$usuario -> obtener_us_prvd_contacto();
				Ingreso_Controller::iniciar_session($usuario);
				//	return true;
				//}
				//else{
				//	return false;
				//}
				
				//$tpl->newBlock("error_login");
				if ($_SESSION["permiso"] == 'OPER') {
					return Ingreso_Controller::menu_operador();
				}
				else{
					return Ingreso_Controller::menu_admin();
				}
				
	
			}
			else{
				
				$tpl = new TemplatePower("template/login.html");
				$tpl->prepare();
				$tpl->newBlock("error_login");
				return $tpl->getOutputContent();
				
			}
		}
		else{
			
			return 'falso';
		}
		
	}

	public static function menu_admin(){

		if (Ingreso_Controller::es_admin()) {
			$tpl = new TemplatePower("template/menu_admin.html");
			$tpl->prepare();
			$total_empl = 0;
			if ($_SESSION['usuario']::obtener_locales($_SESSION['usuario'])) {
				foreach ($_SESSION['locales'] as $key => $value) {
                
                $total_empl = $total_empl + $value->getCantidad_empl() -1;
                                
            }
            	$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Locales');
				$tpl->assign("total", count($_SESSION['locales']));

				$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Empleados');
				$tpl->assign("total", $total_empl );

				$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Proveedores');
				$tpl->assign("total", count($_SESSION["proveedores"]) );

				$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Articulos');
				$tpl->assign("total", count($_SESSION["lotes"]));
			}
			else{
				$tpl->newBlock("sin_sucursales");
			}

		}
		else{
			return Ingreso_Controller::salir();
		}
		
		return $tpl->getOutputContent();
	}

	public static function menu_operador(){

		if (Ingreso_Controller::es_oper()) {
			$tpl = new TemplatePower("template/menu_oper.html");
			$tpl->prepare();
			$locales = us_local::obtener_locales_usuario($_SESSION["usuario"]->getId_user());
			$tpl->newBlock("con_sucursales");
			
			foreach ($locales as $key => $value) {
				//tenes que recorrer el array de lcoales y omstrar la informatcion asi lo pueda eleguir.
				
				$zona = mp_zona::obtener_zona__explicita_2($value["id_zona"]);
				$local = us_local::obtener_empleados_local($zona["id_zona"]);
				$locales_info_id = art_local::obtener_id_local($zona["id_zona"]);
				$local_ok = art_local::generar_local_2($locales_info_id);
				
				$tpl->newBlock("emp_sucursales");
				
				$tpl->assign("id_local_art", $local_ok->getId_local());
				$tpl->assign("titulo", $local_ok->getNombre());
				$tpl->assign("descripcion", $local_ok->getDescripcion());
				
			}
			

		}
		else{
			return Ingreso_Controller::salir();
		}
		
		return $tpl->getOutputContent();
	}

	

	public static function es_admin(){
		if ($_SESSION["permiso"] == 'ADMIN') {
			return true;
		}
		else{
			return false;
		}
	}

	public static function es_oper(){
		if ($_SESSION["permiso"] == 'OPER') {
			return true;
		}
		else{
			return false;
		}
	}

	public static function admin_ok(){
		if (isset($_SESSION["usuario"])) {
                        if ($_SESSION["permiso"] == 'ADMIN') {
                                return true;
                        }
                }
        else{
        	return false;
        }
	}




	function iniciar_session($usuario){

		$_SESSION["usuario"] = $usuario;
		$_SESSION["permiso"] = $usuario->getAcceso();
		

	}


	public static function verificar_session (){
		if (isset($_SESSION["usuario"])) {
			return true;
		}
		else{
			session_destroy();
			return falso;
		}
	}

	public static function salir(){
		if ($_SESSION["permiso"] == 'OPER') {
			$id_acceso = $_SESSION['usuario']->getId_Acceso();
			$hoy = getdate();
            $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
            us_acceso::update_fecha_hora_fina($id_acceso,$ahora);
           
        }
		session_unset();
		session_destroy();

		return Ingreso_Controller::login();
	}


	public static function reportes (){
        $clave_reporte = $_GET['clave_reporte'];
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        if (isset($_SESSION["usuario"])){
        	if ($_SESSION["permiso"] != 'ADMIN') {
        		return Ingreso_Controller::salir();
        	}
 		}
 		
        switch ($clave_reporte) {
        	case 1:
        		# Reporte Articulos Vendidos
        		Ingreso_Controller::reporte_av($fecha_desde,$fecha_hasta);
        		break;
        	case 2:
        		# Reporte Ventas Tarjetas
        		Ingreso_Controller::reporte_vt($fecha_desde,$fecha_hasta);
        		break;
        	case 3:
        		# Reporte Ventas Credito
        		Ingreso_Controller::reporte_vc($fecha_desde,$fecha_hasta);
        		break;
        	case 4:
        		# Reporte Ventas Contado
        		Ingreso_Controller::reporte_co($fecha_desde,$fecha_hasta);
        		break;
        	case 5:
        		# Reporte Ventas Empleado
        		Ingreso_Controller::reporte_vem($fecha_desde,$fecha_hasta);
        		break;
        	case 6:
        		# Reporte Asistencia Empleado
        		Ingreso_Controller::reporte_aem($fecha_desde,$fecha_hasta);
        		break;
        	case 7:
        		# Reporte Stock Articulo
        		Ingreso_Controller::reporte_sa($fecha_desde,$fecha_hasta);
        		break;
        	
        	default:
        		# code...
        		break;
        }
    }

    public static function reporte_av($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_av($fecha_desde,$fecha_hasta);
    }
    public static function reporte_vt($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_vt($fecha_desde,$fecha_hasta);
    	
    }
    public static function reporte_vc($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_vc($fecha_desde,$fecha_hasta);
    	
    }
    public static function reporte_co($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_co($fecha_desde,$fecha_hasta);
    	
    }
    public static function reporte_vem($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_vem($fecha_desde,$fecha_hasta);
    	
    }
    public static function reporte_aem($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_aem($fecha_desde,$fecha_hasta);
    	
    }
    public static function reporte_sa($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_sa($fecha_desde,$fecha_hasta);
    	
    }

	

	
}
?>