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
			/*
			$user = htmlentities($_GET["usuario"], ENT_QUOTES);
			$pass = htmlentities($_GET["pass"], ENT_QUOTES);
			*/
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
					//$id_usuario_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
                    //$user_admin = usuario::generar_usuario($id_usuario_jefe);
                    //$_SESSION['usuario']::obtener_locales($user_admin);
                    //usuario::obtener_lote_us($user_admin->getId_user());
					return Ingreso_Controller::menu_operador();
				}
				else{
	 
					$_SESSION['usuario']::obtener_locales($_SESSION['usuario']);
            		usuario::obtener_lote_us($_SESSION['usuario']->getId_user());
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
			if (isset($_SESSION['locales'])) {
				//foreach ($_SESSION['locales'] as $key => $value) {
                
                //	$total_empl = $total_empl + $value->getCantidad_empl() -1;
                
                                
            	//}


            	$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Locales');
				$tpl->assign("total", count($_SESSION['locales']));

				$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Empleados');
				$tpl->assign("total", count($_SESSION["locales_empleados"]) - 1 );

				$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Proveedores');
				if ($_SESSION["proveedores"] == false) {
					$tpl->assign("total", 0 );
				}else{
					$tpl->assign("total", count($_SESSION["proveedores"]) );
				}
				

				$tpl->newBlock("con_sucursales");
				$tpl->assign("titulo", ' Articulos');
				$tpl->assign("total", count($_SESSION["lotes"]));
				 
				$tpl->newBlock("con_datos_reportes");


				//Select Medio
				$id_usuario = $_SESSION['usuario']->getId_user();
				$medio_descripcion = art_venta_medio::obtener_medios($id_usuario);
            	foreach ($medio_descripcion as $key => $value) {
                # code...
                
                	$tpl->newBlock("carga_medio_");
                	$tpl->assign("id_medio_",$value->getId_medio());
                	$tpl->assign("nombre_medio",$value->getNombre());
            	}

            	foreach ($_SESSION['locales']  as $key => $value) {
                # code...
                
                	$tpl->newBlock("carga_local");
                	$tpl->assign("id_local_",$value->getId_local());
                	$tpl->assign("nombre_local", htmlentities($value->getNombre(), ENT_QUOTES));
            	}

            	
            	$tpl->newBlock("fecha_desde_hasta_fecha");
				 
				
			}
			else{
				$tpl->newBlock("sin_sucursales");
				$tpl->newBlock("sin_datos_reportes");
				
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
		//Ingreso_Controller::setear_conf();
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
        	//Aca vamos a trabajar despues se acomodara.
        		$id_medio = $_POST['venta_medio_parametro_descripcion'];
        		$id_local = $_POST['venta_local_id'];
        		if ($id_medio != 0) {
        			# code...
        			$medio = art_venta_medio::generar_venta_medio($id_medio);
        			$local = art_local::generar_local_2($id_local);
        		} else{
        			$medio = 0;
        			$local = 0;
        		}
        		
        		Ingreso_Controller::reporte_co($fecha_desde,$fecha_hasta,$medio,$local);
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
        	case 8:
        		# Ver listado de Ventas

        		$tpl = Ingreso_Controller::registro_ventas();

        		break;
        	
        	default:
        		# code...
        		break;
        }
        return $tpl->getOutputContent();
    }

    public static function registro_ventas(){

    	$respuesta = reporte::reporte_av_todos();
     
    	$tpl = new TemplatePower("template/seccion_admin_articulos_vendidos.html");
		$tpl->prepare();
		if ($respuesta <= 0) {

			$tpl->newBlock("sin_articulos_lista");
		}
		else{

			$contador = 1;
			$tpl->newBlock("con_articulos_lista");
			//$tpl->newBlock("con_articulos_lista_cabeza");
			$tpl->newBlock("buscador_visible");
			$reversed = array_reverse($respuesta);
			foreach ($reversed as $key => $value) {
				$tpl->newBlock("con_articulos_lista_cuerpo");


				$tpl->assign("numero", $contador);
				$contador = $contador + 1;
				//Nombre Articulo
				$nombre_art = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
				$nom_marca = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
				$nom_tipo = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
				$nombre_art_vendido = $nombre_art.','.$nom_marca.','.$nom_tipo;

				//generar lote local para obtener el local
				$gc = $value->getId_lote_local()->getId_lote()->getId_gc()->getId_categoria();
                foreach ($gc as $clave => $valor) {
                    if (strcmp($valor->getNombre(), "Medida" ) == 0 ) {
                            $medida = $valor->getValor();
                    }
                   }

				




				$tpl->assign("art", $nombre_art_vendido);
				$nom_usuario = $value->getId_venta()->getId_usuario()->getUsuario();

				$tpl->assign("usuario", $nom_usuario);
				$nom_local = $value->getId_lote_local()->getId_local()->getNombre();

				$tpl->assign("local", $nom_local);
				$fecha_venta = $value->getId_venta()->getFecha_hora();

				$tpl->assign("medida", $medida);

				$tpl->assign("fecha_venta", $fecha_venta);
				$id_venta_ =  $value->getId_venta()->getId_venta();
				//Obtener si ubo un cambio
			 	
				if ($value->getId_venta()->getId_cambio() == null) {
					# code...
					$tpl->newBlock("fecha_cambio");
					$tpl->assign("fecha_cambio","Sin Cambio");
				}
				else{
					$tpl->newBlock("fecha_cambio");
					$fecha_cambio = $value->getId_venta()->getId_cambio()->getId_venta()->getFecha_hora();
					 
					$tpl->assign("fecha_cambio",$fecha_cambio);
				}
				$tpl->newBlock("boton_cambio");
				$tpl->assign("id_venta", $id_venta_);
				$tpl->assign("id_lote_local", $value->getId_lote_local()->getId_lote_local());
				
				

				/*$tpl->assign("id_venta2", $id_venta_);
				$tpl->newBlock("modal_modificar_venta");
				$tpl->assign("id_venta2", $id_venta_);
				$tpl->assign("id_venta", $id_venta_);
				$tpl->assign("id_lote_local", $value->getId_lote_local()->getId_lote_local());*/
				

			}
		}

		return $tpl;
	}

    

    public static function reporte_av($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_av($fecha_desde,$fecha_hasta);
    	// 
    	
    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora."\n".'El simbolo (*) simboloza un cambio en la venta.' );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		//$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );
		
		// Nombre Columnas
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		//se saco medios de pago
		$columnas = ['Cant','Articulo','Local','Vendedor','Precio Final'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 47, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		$medio_limpio = array();
	 	$precio_recaudacion_ = 0;
	 	$numero_cont = 1;
		foreach ($respuesta as $key => $value) {
			// 
			$nombre_art = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
			$nom_marca = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
			$nom_tipo = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();

			$nom_completo = $nom_marca.','.$nom_tipo;
			$local_venta = $value->getId_lote_local()->getId_local()->getNombre();
			$vendedor = $value->getId_venta()->getId_usuario()->getUsuario();

			//Medio Pago
			$descuento = $value->getId_venta()->getMedio()->getDescuento();
			$medio_pago = $value->getId_venta()->getMedio()->getNombre();
			$medio_pago = $medio_pago.' (%'.$descuento.')';
			 
			
			
			$precio_final = $value->getId_venta()->getTotal();

			$medio_sin = str_replace('$','',$precio_final);

			 
			$porciones = explode("x", $medio_sin);
			
			 
		 
			 
			if (count($porciones) >1) {
				$cantidad_cuotas = $porciones[0];
				$precio_base = $porciones[1];
				$precio_final_final = (integer)$cantidad_cuotas * (integer)$precio_base;
			}else{
				//entra sin cuotas
				$precio_final_final = $porciones[0];
			}
			$precio_recaudacion_ = $precio_recaudacion_ + $precio_final_final;
			$medio_limpio[] = $medio_sin;
			if ($value->getId_venta()->getId_cambio() != null) {
				# code...
				$respuesta_final[] = [$numero_cont,$nom_completo,$local_venta,$vendedor.' * ',$precio_final];
			}else
			{
				$respuesta_final[] = [$numero_cont,$nom_completo,$local_venta,$vendedor,$precio_final];
			}
			
			//Se saco medio de pago
			
			$numero_cont = $numero_cont + 1;

		}

		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		/*$pdf->SetFont( 'Arial', 'B', 15 );
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor( 255, 255, 255 );
	  		$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );*/

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		$pdf->Cell( 0, 15, 'Total Recaudado: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "report.pdf", "I" );
    	
    }

    public static function reporte_vt($fecha_desde,$fecha_hasta){
    	//$respuesta = reporte::reporte_vt($fecha_desde,$fecha_hasta);
    	$respuesta = reporte::reporte_av($fecha_desde,$fecha_hasta);
    	// 
    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		//$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );
		
		// Nombre Columnas
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['Cant','Articulo','Medio de Pago','Cuotas','Precio Final'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 36, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		$medio_limpio = array();
	 	$precio_recaudacion_ = 0;
	 	$numero_cont = 1;
		foreach ($respuesta as $key => $value) {
			// 
			$medio_pago = $value->getId_venta()->getMedio();
			if (strcmp($medio_pago, "Tarjeta de Credito")  == 0 ) {
				$nombre_art = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
				$nom_marca = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
				$nom_tipo = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();

				$nom_completo = $nom_marca.','.$nom_tipo;
				$local_venta = $value->getId_lote_local()->getId_local()->getNombre();
				$vendedor = $value->getId_venta()->getId_usuario()->getUsuario();

			
			 
			
			
				$precio_final = $value->getId_venta()->getTotal();

				$medio_sin = str_replace('$','',$precio_final);

			 
				$porciones = explode("x", $medio_sin);
			
			 
		 
			 
				if (count($porciones) >1) {
					$cantidad_cuotas = $porciones[0];
					$precio_base = $porciones[1];
					$precio_final_final = (integer)$cantidad_cuotas * (integer)$precio_base;
				}else{
				//entra sin cuotas
					$precio_final_final = $porciones[0];
				}
				$precio_recaudacion_ = $precio_recaudacion_ + $precio_final_final;
				$medio_limpio[] = $medio_sin;
				$respuesta_final[] = [$numero_cont,$nom_completo,$local_venta,$vendedor,$medio_pago,$precio_final];
				$numero_cont = $numero_cont + 1;
			}
			

		}

		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		/*$pdf->SetFont( 'Arial', 'B', 15 );
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor( 255, 255, 255 );
	  		$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );*/

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 36, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 36, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		$pdf->Cell( 0, 15, 'Total Recaudado: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "reportvt.pdf", "I" );
    	
    }
    public static function reporte_vc($fecha_desde,$fecha_hasta){
    	//$respuesta = reporte::reporte_vc($fecha_desde,$fecha_hasta);
    	$respuesta = reporte::reporte_av($fecha_desde,$fecha_hasta);
    	// 
    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		//$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );
		
		// Nombre Columnas
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['Cant','Articulo','Local','Vendedor','Medio de Pago','Precio Final'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 36, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		$medio_limpio = array();
	 	$precio_recaudacion_ = 0;
	 	$numero_cont = 1;
		foreach ($respuesta as $key => $value) {
			// 
			$medio_pago = $value->getId_venta()->getMedio();
			if (strcmp($medio_pago, "Credito Personal")  == 0 ) {
				$nombre_art = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
				$nom_marca = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
				$nom_tipo = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();

				$nom_completo = $nom_marca.','.$nom_tipo;
				$local_venta = $value->getId_lote_local()->getId_local()->getNombre();
				$vendedor = $value->getId_venta()->getId_usuario()->getUsuario();

			
			 
			
			
				$precio_final = $value->getId_venta()->getTotal();

				$medio_sin = str_replace('$','',$precio_final);

			 
				$porciones = explode("x", $medio_sin);
			
			 
		 
			 
				if (count($porciones) >1) {
					$cantidad_cuotas = $porciones[0];
					$precio_base = $porciones[1];
					$precio_final_final = (integer)$cantidad_cuotas * (integer)$precio_base;
				}else{
				//entra sin cuotas
					$precio_final_final = $porciones[0];
				}
				$precio_recaudacion_ = $precio_recaudacion_ + $precio_final_final;
				$medio_limpio[] = $medio_sin;
				$respuesta_final[] = [$numero_cont,$nom_completo,$local_venta,$vendedor,$medio_pago,$precio_final];
				$numero_cont = $numero_cont + 1;
			}
			

		}

		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		/*$pdf->SetFont( 'Arial', 'B', 15 );
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor( 255, 255, 255 );
	  		$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );*/

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 36, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 36, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		$pdf->Cell( 0, 15, 'Total Recaudado: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "reportvc.pdf", "I" );
    	
    }
    public static function reporte_co($fecha_desde,$fecha_hasta,$medio,$local){
    	//$respuesta = reporte::reporte_co($fecha_desde,$fecha_hasta);
    	$todo = false;
    	 
    	if (is_numeric($medio)) {
    		# code...
    		$respuesta = reporte::reporte_co($fecha_desde,$fecha_hasta,0);
    		$todo = true;
    	}else{
    		$respuesta = reporte::reporte_co($fecha_desde,$fecha_hasta,$medio->getId_medio());
    		$medio_nombre_segmentacion = $medio->getNombre();
    	}
    	
    	//p 

    	//$medio_obj = art_venta_medio::generar_venta_medio($medio);
    	
    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Write( 6, "Reporte de Articulos Vendidos por medio de pago definido\nAscenso Positivo\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		if ($local != 0) {
			# code...
			$pdf->Write( 6, "\nLocal: ".$local->getNombre());
			$locales_todos = false;
		}else{
			$pdf->Write( 6, "\nLocal: ".'Todos');
			$locales_todos = true;
		}
		
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		//$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );
		
		// Nombre Columnas
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['Cant','Articulo','Medio de Pago','Cuotas','Precio Final'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 47, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		$medio_limpio = array();
	 	$precio_recaudacion_ = 0;
	 	$numero_cont = 1;
	 	
	 	
	 	 
		foreach ($respuesta as $key => $value) {
			// 
			
			$id_medio_descripcion = $value->getId_venta()->getMedio()->getDescripcion();
			$descripcion_medio = art_venta_medio_descripcion::generar_venta_medio_descripcion($id_medio_descripcion);

			$nombre_medio_pago = $value->getId_venta()->getMedio()->getNombre();
			if ($todo) {
				# code...
				 
				$medio_nombre_segmentacion = $nombre_medio_pago;

			}

			$id_local_respuesta = $value->getId_lote_local()->getId_local()->getId_local();
			if ($locales_todos) {
				# code...

			}else{
				if ($id_local_respuesta == $local->getId_local()) {
					# code...

				}else{
					break;
				}
			}


			//$medio_pago = $descripcion_medio->getNombre();
			if (strcmp($nombre_medio_pago, $medio_nombre_segmentacion)  == 0 ) {

				$nombre_art = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
				$nom_marca = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
				$nom_tipo = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();

				$nom_completo = $nom_marca.','.$nom_tipo;
				

			
			 
			
			
				$precio_final = $value->getId_venta()->getTotal();

				$medio_sin = str_replace('$','',$precio_final);

			 
				$porciones = explode("x", $medio_sin);
			
			 	$cuotas = $value->getId_venta()->getCuotas();
		 
			 
				if (count($porciones) >1) {
					$cantidad_cuotas = $porciones[0];
					$precio_base = $porciones[1];
					$precio_final_final = (integer)$cantidad_cuotas * (integer)$precio_base;
				}else{
				//entra sin cuotas
					$precio_final_final = $porciones[0];
				}
				$precio_recaudacion_ = $precio_recaudacion_ + $precio_final_final;
				$medio_limpio[] = $medio_sin;
				$respuesta_final[] = [$numero_cont,$nom_completo,$nombre_medio_pago,$cuotas,$precio_final];
				$numero_cont = $numero_cont + 1;
			}
			

		}

		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		/*$pdf->SetFont( 'Arial', 'B', 15 );
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor( 255, 255, 255 );
	  		$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );*/

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		$pdf->Cell( 0, 15, 'Total Recaudado: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "reportvc.pdf", "I" );

    	
    }
    public static function reporte_vem($fecha_desde,$fecha_hasta){
    	//$respuesta = reporte::reporte_vem($fecha_desde,$fecha_hasta);
    	$respuesta = reporte::reporte_av($fecha_desde,$fecha_hasta);
    	// 
    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		//$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );
		
		// Nombre Columnas
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['Empleado','Articulo','Local','Fecha y Hora'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			
			 
			
				$pdf->Cell( 47, 12, $columnas[$i], 1, 0, 'C', true );
			
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		 
		foreach ($respuesta as $key => $value) {
			// 
			 
				$nombre_art = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
				$nom_marca = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
				$nom_tipo = $value->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();

				$nom_completo = $nom_marca.','.$nom_tipo;
				$local_venta = $value->getId_lote_local()->getId_local()->getNombre();
				$vendedor = $value->getId_venta()->getId_usuario()->getUsuario();

				$venta_fecha = $value->getId_venta()->getFecha_hora();
				
				$respuesta_final[] = [$vendedor,$nom_completo,$local_venta,substr($venta_fecha, 0, -3)];
				 
			

		}

		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		/*$pdf->SetFont( 'Arial', 'B', 15 );
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor( 255, 255, 255 );
	  		$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );*/

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				
	  					
	  				
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				
	  				
	  				$banban2 = false;
	  			}else{

	  				
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		//$pdf->Cell( 0, 15, 'Total Recaudado: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "reportvc.pdf", "I" );
    	
    }
    public static function reporte_sa($fecha_desde,$fecha_hasta){
    	$respuesta = reporte::reporte_sa($fecha_desde,$fecha_hasta);

    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Write( 6, "Reporte de Stock de Articulos\nAscenso Positivo\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		//$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		//$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );
		
		// Nombre Columnas
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['Num','Articulo','Medida','Stock','Local'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 47, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		
	 	$numero_cont = 1;
	 	 
		//foreach ($respuesta as $key2 => $value2) {
			// 
		
			foreach ($respuesta as $key => $value) {
				# code...
			
				$nombre_art = $value->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
				$nom_marca = $value->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
				$nom_tipo = $value->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();

				$nom_completo = $nom_marca.','.$nom_tipo;
				$local_venta = $value->getId_local()->getNombre();
				$canitdad_parcial = $value->getCantidad_parcial();
				$gc = $value->getId_lote()->getId_gc()->getId_categoria();
                foreach ($gc as $clave => $valor) {
                    if (strcmp($valor->getNombre(), "Medida" ) == 0 ) {
                        $medida = $valor->getValor();

                                            
                    }
                }

				
				$respuesta_final[] = [$numero_cont,$nom_completo,$medida,$local_venta,$canitdad_parcial];
				$numero_cont = $numero_cont + 1;
			
			}

		//}

		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		/*$pdf->SetFont( 'Arial', 'B', 15 );
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor( 255, 255, 255 );
	  		$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );*/

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		//$pdf->Cell( 0, 15, 'Total Recaudado: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "reportvc.pdf", "I" );
    	
    }
    public static function reporte_aem($fecha_desde,$fecha_hasta){
    	
    	$respuesta = reporte::reporte_aem($fecha_desde,$fecha_hasta);

    	
    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );
		$pdf->Write( 6, "Reporte de Acceso de empleados al sistema\nAscenso Positivo\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		//$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );
		
		// Nombre Columnas
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['Num','Usuario','Local','Inicio','Fin'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 47, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		
	 	$numero_cont = 1;
	 	 
		//foreach ($respuesta as $key2 => $value2) {
			// 
		
			foreach ($respuesta as $key => $value) {
				# code...
			
				$usuario = $value->getUsuario()->getUsuario();
				$local_venta = $value->getId_local()->getNombre();
				$inicio = $value->getFechaHora_Inicio();

				$fin = $value->getFechaHora_Fin();
				if (strcmp($fin, "0000-00-00 00:00:00" ) == 0 ) {
                    $fin = 'Conectado';
                }
                
                
				$respuesta_final[] = [$numero_cont,$usuario,$local_venta,substr($inicio, 0, -3),substr($fin, 0, -3)];
				$numero_cont = $numero_cont + 1;
			
			}

		//}

		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		/*$pdf->SetFont( 'Arial', 'B', 15 );
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor( 255, 255, 255 );
	  		$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );*/

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		//$pdf->Cell( 0, 15, 'Total Recaudado: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "reportvc.pdf", "I" );
    	
    }

	

	
}
?>