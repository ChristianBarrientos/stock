<?php
//===========================================================================================================
// OPEN SESSION |
//---------------
	

//===========================================================================================================
// INCLUDES |
//-----------
//Setear TimeZone
date_default_timezone_set("America/Argentina/Catamarca");
	
set_time_limit(300);
include("include_config.php");

/*global $config;
if ($config["dbEngine"]=="MYSQL"){
	$baseDatos = new mysqli($config["dbhost"],$config["dbuser"],$config["dbpass"],$config["db"]);
	
	
}*/



//===========================================================================================================
// INSTANCIA CLASES Y METODOS |
//-----------------------------

	if ((!isset($_REQUEST["action"])) || ($_REQUEST["action"]=="")) {
        $_REQUEST["action"] = "Ingreso::login"; 
    }
	if ($_REQUEST["action"]=="") {
        $html = "";
    }
    
    if ($_REQUEST["action"]=="Ingreso::generar_reporte") {
    	Ingreso_Controller::generar_reporte();
        exit;
    }
	else{
		if (!strpos($_REQUEST["action"],"::")) {
            $_REQUEST["action"].="::login";
        }
		list($classParam,$method) = explode('::',$_REQUEST["action"]);
		if ($method=="") {
		    $method="login";// AGREGAR Condición PARA SABER SI YA INICIO Sesión
        }
		$classToInstaciate = $classParam."_Controller";

		if (class_exists($classToInstaciate)){

			if (method_exists($classToInstaciate,$method)) {
				$claseTemp = new $classToInstaciate;
				$html=call_user_func_array(array($claseTemp, $method),array());
			}
			else{
				
				$html="falso";//Ingreso_Controller::login();
			}
		}
		else{
			 
			$html="La página solicitada no está disponible.";
		}
	}
	
//===========================================================================================================
// INSTANCIA TEMPLATE |
//---------------------

	$tpl = new TemplatePower("template/index.html");
	$tpl->prepare();
	
//===========================================================================================================
// LEVANTA TEMPLATE	|
//-------------------		

	$tpl->gotoBlock("_ROOT");

	$tpl->assign("menu_bar",Ingreso_Controller::menu_bar($_REQUEST["action"]));
   
    // controll login
    if ($html == 'falso') {
    	$tpl->assign("contenido",Ingreso_Controller::login());
    }
    else{
    	
 		 
    	$tpl->assign("contenido",$html);

    	
    }
    


	$tpl->printToScreen();
    
    	  

?>
