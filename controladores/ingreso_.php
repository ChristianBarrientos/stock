<?php
class Ingreso_Controller{


	public static function login (){
        
        if (isset($_SESSION["usuario"])){
        	if ($_SESSION["permiso"] == 'ADMIN') {
        		return Ingreso_Controller::menu_admin();
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
		
		if (isset($_SESSION["usuario"])){
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
				return Ingreso_Controller::menu_admin();
	
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

	

	public static function es_admin(){
		if ($_SESSION["permiso"] == 'ADMIN') {
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
		session_unset();
		session_destroy();

		return Ingreso_Controller::login();
	}

	

	
}
?>