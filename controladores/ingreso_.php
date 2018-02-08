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
			$id_user = $_SESSION["usuario"]->getId_user();
        	//$cliente = ot_cliente::obtener($id_user);
        	
        	$ot_cl = ot_cliente::generar($_SESSION["usuario"]->getId_user());
        	$nombre_cliente = $ot_cl->getNombre();
        	
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

			if ($seccion == "Gasto::menu" ) {
				     
		   			$tpl->assign("select_gastos", $active);

			}

			$tpl->assign("nombre_cliente", $nombre_cliente);

			/*$usuario = $_SESSION['usuario'];
			 $usuario = unserialize($usuario);
			 //$usuario::obtener_locales($usuario);*/
			 
			$tpl->assign("usuario", $_SESSION['usuario']->getUsuario());
		}
		if ($_SESSION["permiso"] == 'OPER') {
			$tpl->newBlock("operador"); 
			$tpl->assign("usuario", $_SESSION["usuario"]->getUsuario());
			$tpl->assign("select_menu", $active);
			//Obtener Admin
			$id_jefe = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
			$ot_cl = ot_cliente::generar($id_jefe);
        	$nombre_cliente = $ot_cl->getNombre();
			$tpl->assign("nombre_cliente", $nombre_cliente);
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

                    //return Articulo_Controller::vender();

                     
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

				$medios_pago = us_medio_pago::obtener($id_usuario);
			 
				if (count($medios_pago)) {
					# code...
					$tpl->newBlock("carga_medio_ok");
					
					foreach ($medios_pago as $key => $value) {
                	# code...
                
                		$tpl->newBlock("carga_medio_");
                		$tpl->assign("id_medio_",$value->getId());
                		$tpl->assign("nombre_medio",$value->getNombre());
            		}
            	}

            	foreach ($_SESSION['locales']  as $key => $value) {
                # code...
                
                	$tpl->newBlock("carga_local");
                	$tpl->assign("id_local_",$value->getId_local());
                	$tpl->assign("nombre_local", htmlentities($value->getNombre(), ENT_QUOTES));
            	}

            	
            	$tpl->newBlock("fecha_desde_hasta_fecha");
				
				//Obtener gastos

				
				if ($_SESSION['usuario']->getAcceso() == 'ADMIN') {
					# code...
					$id_user_admin = $_SESSION['usuario']->getId_user();
				}else{
					$id_user_admin = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
				}

				$gastos = us_gastos::obtener($id_user_admin);

				if ($gastos) {
					# code...
					foreach ($gastos->getId_us_ggs() as $key => $value) {
						$gasto = $value->getId_gasto();
						$gs_tipo_nombre = $gasto->getId_gs_des()->getNombre();
						$id_gs_tipo = $gasto->getId_gs_des()->getId_gs_des();
						$tpl->newBlock("cargar_tipo_gasto");

						$tpl->assign("id_gs_tipo",$id_gs_tipo);
						$tpl->assign("nombre_tipo",$gs_tipo_nombre);
						
					}

					
				}

				$tpl->newBlock("fecha_desde_hasta_fecha_2");

				
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
				 
				//$zona = mp_zona::obtener_zona__explicita_2($value["id_zona"]);
				//$local = us_local::obtener_empleados_local($value["id_local"]);
				//$locales_info_id = art_local::obtener_id_local($zona["id_zona"]);
				$local_ok = art_local::generar_local_2($value["id_local"]);
				
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
		if (isset($_SESSION["permiso"])) {
			# code...
			if ($_SESSION["permiso"] == 'ADMIN') {
				return true;
			}
			else{
				return false;
			}
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
        //if ($clave_reporte != 12) {
        	$fecha_desde = $_POST['fecha_desde'];
        	$fecha_hasta = $_POST['fecha_hasta'];
        //}
        

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
        		/*if ($id_medio != 0) {
        			# code...
        			$medio = art_venta_medio::generar_venta_medio($id_medio);
        			$local = art_local::generar_local_2($id_local);
        		} else{
        			$medio = 0;
        			$local = 0;
        		}*/


        		
        		Ingreso_Controller::reporte_co($fecha_desde,$fecha_hasta,$id_medio,$id_local);
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
        	case 9:
        		# Ver listado de Ventas
        		$gs_tipo = $_POST['gs_tipo'];
        		$gs_fecha_desde = $_POST['gs_fecha_desde'];
        		$gs_fecha_hasta = $_POST['gs_fecha_hasta'];

        		$tpl = Ingreso_Controller::registro_gs($gs_tipo,$gs_fecha_desde,$gs_fecha_hasta);

        		break;

        	case 10:
        		# Imprimir sueldos sin detalle
        		$sl_fecha_desde = $_POST['fecha_desde'];
        		$sl_fecha_hasta = $_POST['fecha_hasta'];

        		$tpl = Ingreso_Controller::registro_sl($sl_fecha_desde,$sl_fecha_hasta);

        		break;

        	case 11:
        		# Imprimir sueldos con detalles
        		$sl_fecha_desde = $_POST['fecha_desde'];
        		$sl_fecha_hasta = $_POST['fecha_hasta'];

        		$tpl = Ingreso_Controller::registro_gs($gs_tipo,$gs_fecha_desde,$gs_fecha_hasta);

        		break;

        	case 12:
        		# Imprimir sueldos con detalles
        		/*$rpm_mes = $_POST['rpm_mes'];
        		$aux = explode('-', $rpm_mes);

        		$anio = $aux[0];
        		$mes = $aux[1];*/
        		 
        		$tpl = Ingreso_Controller::reporte_por_semana($fecha_desde,$fecha_hasta);

        		break;
        	
        	default:
        		# code...
        		break;
        }
        //return $tpl->getOutputContent();
    }


public static function registro_sl($fecha_desde,$fecha_hasta){
    	$total_sueldos = 0;
        $total_anticipo = 0;
        $total_pagar = 0;
        $respuesta = us_sueldos::obtener();

    	ini_set("session.auto_start", 0);
       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );

		$permiso = $_SESSION['usuario']->setId_Acceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();
		$nombre_negocio = $nombre_ng."\n";

		$pdf->Write( 6, $nombre_negocio."Reporte de Sueldos\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		$pdf->Ln( 12 );

		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->Ln( 15 );
		$pdf->SetTextColor( 0, 0, 0);
		$pdf->SetFillColor( 255, 255, 255 );
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['N','Nombre','Basico','Adelantos','Neto a Cobrar'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 47, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}

		$respuesta_final = array();
		
	 	$numero_cont = 1;
			foreach ($respuesta as $key => $value) {
				$nombre = $value->getId_usuario()->getId_datos()->getNombre();
                $apellido = $value->getId_usuario()->getId_datos()->getApellido();
				$nom_completo = $nombre.','.$apellido;
				$basico = $value->getBasico();

				$total_anticipos = 0;
				$anticipos = $value->getId_gmv()->getId_gs_mv()[0]->getId_gsub_gasto();
				if ($anticipos != null) {
                        
                    $anticipos = $anticipos->getId_sub_gasto();
                    foreach ($anticipos as $key2 => $value2) {
                        $valor_subgasto = $value2->getValor();
                        $total_anticipos = $total_anticipos + $valor_subgasto;
                    }
                }else{
                    $total_anticipos = 0;
                    }

                $aguinaldo = $value->getAguinaldo();
                    //Comprobar si es mes de que se paga el aguinaldo
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
				$respuesta_final[] = [$numero_cont,$nom_completo,$basico,$total_anticipos,$neto];
				$numero_cont = $numero_cont + 1;
				
				$total_sueldos = $total_sueldos + $basico + $aguinaldo;
                $total_anticipo = $total_anticipo + $total_anticipos;
                $total_pagar = $total_pagar + $neto;
			}
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {
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
		$pdf->Write( 6, "\nTotal de Sueldos: $".$total_sueldos."\nTotal Anticipos: $".$total_anticipo."\nTotal a Pagar: $".$total_pagar);
		$pdf->Ln( 12 );
		ob_end_clean();
		$pdf->Output( "reportvc.pdf", "I" );
    	
    }

public static function obtener_dia($fecha_hasta){
	$dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
	$dia = $dias[date('N', strtotime($fecha_hasta))];
	return $dia;
}

public static function reporte_por_semana($fecha_desde,$fecha_hasta){
	//$respuesta = reporte::reporte_gs($gs_tipo,$fecha_desde,$fecha_hasta);
	//Obtener Ingresos por Locales
	$respuesta = reporte::reporte_por_semana($fecha_desde,$fecha_hasta);

		ini_set("session.auto_start", 0);

       	$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );

		$permiso = $_SESSION['usuario']->getId_Acceso();

		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";

		
		$pdf->Write( 6, $nombre_negocio."Reporte Semanal\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora."\n");
		
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
		$pdf->Write( 6, "Ingresos:" );
		$pdf->Ln( 12 );
		$columnas = array();
		$columnas[] = 'Dia';
		foreach ($_SESSION["locales"] as $key => $value) {
			$nombre_local = $value->getNombre();
			if (strcmp($nombre_local, "DEPOSITO" ) == 0 ) {
				continue;
			}else{
				if (strlen($nombre_local) > 13) {
					$nombre_aux = explode(' ', $nombre_local);
					$nombre_aux_finali = '';
					foreach ($nombre_aux as $ja => $jaja) {
						$da = substr($jaja, 0, 4);
						$nombre_aux_finali = $nombre_aux_finali.' '.$da;
					}
					//$nombre_local = substr($nombre_local, 0, 13);
					$nombre_local = $nombre_aux_finali;
				}
				$columnas[] = $nombre_local;
			}
			
		}
		 

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 37, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		$array_aux_ventas = array();
	 	$precio_recaudacion_ = 0;
	 	$numero_cont = 1;
	 	

		foreach ($respuesta as $key => $value) {

			$fecha_venta = $value->getFecha_hora();

			$dia_venta = Ingreso_Controller::obtener_dia($fecha_venta);

			$rg_detalle_ = $value->getRg_detalle();
			$rg_detalle__aux = explode(',', $rg_detalle_);

			$id_local = $rg_detalle__aux[3];
			
			$nro_comprobante = $rg_detalle__aux[0];

			$ganancia_venta = floatval($value->getTotal());
			//$ganancia_venta = floatval($rg_detalle__aux[2]);

			$medio_pago_aux = $value->getId_gmedio_pago()->getId_medio_pago();

			$tipo_medio_pago = $medio_pago_aux[0]->getId_medio_tipo()->getNombre();

			$nombre_medio_pago = $medio_pago_aux[0]->getNombre();

			$precio_recaudacion_ = floatval($precio_recaudacion_) + floatval($ganancia_venta);

			$array_aux_ventas[] = [$fecha_venta,$dia_venta,$id_local,$nro_comprobante,$ganancia_venta,$tipo_medio_pago,$nombre_medio_pago];		
		}

		$lunes_array = array();
		$martes_array = array();
		$miercoles_array = array();
		$jueves_array = array();
		$viernes_array = array();
		$sabado_array = array();
		$domindo_array = array();

		foreach ($array_aux_ventas as $key2 => $value2) {

			$dia_aux = $value2[1];
			switch ($dia_aux) {
				case 'Lunes':
					$lunes_array[] = $value2;
					break;
				case 'Martes':
					$martes_array[] = $value2;
					break;
				case 'Miercoles':
					$miercoles_array[] = $value2;
					break;
				case 'Jueves':
					$jueves_array[] = $value2;
					break;
				case 'Viernes':
					$viernes_array[] = $value2;
					break;
				case 'Sabado':
					$sabado_array[] = $value2;
					break;
				case 'Domingo':
					$domingo_array[] = $value2;
					break;
				
				default:
					# code...
					break;
			}	
		}
		echo "Cantidad de Ventas";
		print_r(count($martes_array));
		$total_ventas_array_lunes = array();
		$venta_total_dia = 0;

		/*echo "Lunes";
		print_r($lunes_array);
		echo "Martes";
		print_r($martes_array);
		echo "Miercoles";
		print_r($miercoles_array);
		echo "Jueves";
		print_r($jueves_array);
		echo "Viernes";
		print_r($viernes_array);
		echo "Sabado";
		print_r($sabado_array);
		echo "Domingo";
		print_r($domindo_array);*/
		/*$dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
		foreach ($dias as $key => $value) {
			switch ($value) {
				case 'Lunes':
					foreach ($_SESSION["locales"] as $key => $value3) {
						$id_local_ = $value3->getId_local();
						
						foreach ($lunes_array as $key => $value) {
							$id_venta_local = $value[2];
							$venta_del_dia = $value[4];

							if ($id_local_ == $id_venta_local) {
								$venta_total_dia = $venta_total_dia + $venta_del_dia;
							}
						}

						$total_ventas_array_lunes[] = $venta_total_dia;
						$venta_del_dia = 0;
						$venta_total_dia = 0;
						  
					}
					 
					break;
				case 'Martes':
					
					break;
				case 'Miercoles':
					 
					break;
				case 'Jueves':
					
					break;
				case 'Viernes':
					
					break;
				case 'Sabado':
					
					break;
				case 'Domingo':
					
					break;
				
				default:
					# code...
					break;
			}
		}*/
		$ultima_fila_reporte = array();
		foreach ($_SESSION["locales"] as $key => $value) {
			$id_local_ = $value->getId_local();
			$ultima_fila_reporte[$id_local_] = 0;
		}

		foreach ($_SESSION["locales"] as $key => $value3) {
			$id_local_ = $value3->getId_local();
			$nombre_local = $value->getNombre();
			if (!strcmp($nombre_local, "DEPOSITO" ) == 0 ) {
				foreach ($lunes_array as $key => $value) {
					$id_venta_local = $value[2];
					$venta_del_dia = $value[4];

					if ($id_local_ == $id_venta_local) {
						$venta_total_dia = $venta_total_dia + $venta_del_dia;

						$ultima_fila_reporte[$id_local_] = $ultima_fila_reporte[$id_local_] + floatval($venta_total_dia) ;
					}
				}
			}else{
				continue;
			}
			$total_ventas_array_lunes[] = $venta_total_dia;
			$venta_del_dia = 0;
			$venta_total_dia = 0;
			  
		}

		$respuesta_final[] = ['LUN',$total_ventas_array_lunes];

		$total_ventas_array_martes = array();
		//print_r($martes_array);
		foreach ($_SESSION["locales"] as $key => $value3) {
			$id_local_ = $value3->getId_local();
			$nombre_local = $value3->getNombre();
			if (!strcmp($nombre_local, "DEPOSITO" ) == 0 ) {
				foreach ($martes_array as $key => $value) {
					$id_venta_local = $value[2];
					$venta_del_dia = $value[4];
					
					if ($id_local_ == $id_venta_local) {
						$venta_total_dia = floatval($venta_total_dia) + floatval($venta_del_dia);

						$ultima_fila_reporte[$id_local_] = $ultima_fila_reporte[$id_local_] + floatval($venta_total_dia) ;
					}
				}
			}else{
				continue;
			}
			$total_ventas_array_martes[] = $venta_total_dia;
			$venta_del_dia = 0;
			$venta_total_dia = 0;
			  
		}


		$respuesta_final[] = ['MAR',$total_ventas_array_martes];
		$total_ventas_array_miercoles = array();

		foreach ($_SESSION["locales"] as $key => $value3) {
			$id_local_ = $value3->getId_local();
			$nombre_local = $value3->getNombre();
			if (!strcmp($nombre_local, "DEPOSITO" ) == 0 ) {
				foreach ($miercoles_array as $key => $value) {
					$id_venta_local = $value[2];
					$venta_del_dia = $value[4];
					if ($id_local_ == $id_venta_local) {
						$venta_total_dia = $venta_total_dia + $venta_del_dia;

						$ultima_fila_reporte[$id_local_] = $ultima_fila_reporte[$id_local_] + floatval($venta_total_dia) ;
					}
				}
			}else{
				continue;
			}
			$total_ventas_array_miercoles[] = $venta_total_dia;
			$venta_del_dia = 0;
			$venta_total_dia = 0;
			  
		}

		$respuesta_final[] = ['MIE',$total_ventas_array_miercoles];
		$total_ventas_array_jueves = array();

		foreach ($_SESSION["locales"] as $key => $value3) {
			$id_local_ = $value3->getId_local();
			$nombre_local = $value3->getNombre();
			if (!strcmp($nombre_local, "DEPOSITO" ) == 0 ) {
				foreach ($jueves_array as $key => $value) {
					$id_venta_local = $value[2];
					$venta_del_dia = $value[4];
					if ($id_local_ == $id_venta_local) {
						$venta_total_dia = $venta_total_dia + $venta_del_dia;

						$ultima_fila_reporte[$id_local_] = $ultima_fila_reporte[$id_local_] + floatval($venta_total_dia) ;
					}
				}
			}else{
				continue;
			}
			$total_ventas_array_jueves[] = $venta_total_dia;
			$venta_del_dia = 0;
			$venta_total_dia = 0;
			  
		}

		$respuesta_final[] = ['JUE',$total_ventas_array_jueves];
		$total_ventas_array_viernes = array();

		foreach ($_SESSION["locales"] as $key => $value3) {
			$id_local_ = $value3->getId_local();
			$nombre_local = $value3->getNombre();
			if (!strcmp($nombre_local, "DEPOSITO" ) == 0 ) {
				foreach ($viernes_array as $key => $value) {
					$id_venta_local = $value[2];
					$venta_del_dia = $value[4];
					if ($id_local_ == $id_venta_local) {
						$venta_total_dia = $venta_total_dia + $venta_del_dia;

						$ultima_fila_reporte[$id_local_] = $ultima_fila_reporte[$id_local_] + floatval($venta_total_dia) ;
					}
				}
			}
			else{
				continue;
			}
			$total_ventas_array_viernes[] = $venta_total_dia;
			$venta_del_dia = 0;
			$venta_total_dia = 0;
			  
		}

		$respuesta_final[] = ['VIE',$total_ventas_array_viernes];
		$total_ventas_array_sabado = array();

		foreach ($_SESSION["locales"] as $key => $value3) {
			$id_local_ = $value3->getId_local();
			$nombre_local = $value3->getNombre();
			if (!strcmp($nombre_local, "DEPOSITO" ) == 0 ) {
				foreach ($sabado_array as $key => $value) {
						$id_venta_local = $value[2];
						$venta_del_dia = $value[4];
						if ($id_local_ == $id_venta_local) {
							$venta_total_dia = $venta_total_dia + $venta_del_dia;

							$ultima_fila_reporte[$id_local_] = $ultima_fila_reporte[$id_local_] + floatval($venta_total_dia) ;
						}
					}
			}else{
				continue;
			}
			$total_ventas_array_sabado[] = $venta_total_dia;
			$venta_del_dia = 0;
			$venta_total_dia = 0;
			  
		}

		$respuesta_final[] = ['SAB',$total_ventas_array_sabado];
		$total_ventas_array_domingo = array();

		foreach ($_SESSION["locales"] as $key => $value3) {
			$id_local_ = $value3->getId_local();
			$nombre_local = $value3->getNombre();
			if (!strcmp($nombre_local, "DEPOSITO" ) == 0 ) {

				if (isset($domingo_array)) {
				
					foreach ($domingo_array as $key => $value) {
						$id_venta_local = $value[2];
						$venta_del_dia = $value[4];
						if ($id_local_ == $id_venta_local) {
							$venta_total_dia = $venta_total_dia + $venta_del_dia;

							$ultima_fila_reporte[$id_local_] = $ultima_fila_reporte[$id_local_] + floatval($venta_total_dia) ;
						}
					}
				}
			}else{
				continue;
			}

			$total_ventas_array_domingo[] = $venta_total_dia;
			$venta_del_dia = 0;
			$venta_total_dia = 0;
			  
		}
		
		$respuesta_final[] = ['DOM',$total_ventas_array_domingo];

		/*print_r($respuesta_final[0][0]);

		print_r($respuesta_final[0][1][0]);
		print_r($respuesta_final[0][1][1]);
		print_r($respuesta_final[0][1][2]);
		print_r($respuesta_final[0][1][3]);
		*/
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;

		 
		/*foreach ( $respuesta_final as $dataRow ) {

	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[0], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 37, 12, $dataRow[1][$i] , 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[0], 1, 0, 'C', true );
	  				}
	  				else{
	  					

	  					$pdf->Cell( 37, 12, $dataRow[1][$i] , 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		$pdf->Cell( 0, 15, 'Total de Ingresos: $'.$precio_recaudacion_, 1, 0, 'C', true );

		
		$pdf->Ln( 12 );
		ob_end_clean(); 
		$pdf->Output( "ReportePorSemana.pdf", "I" );*/				
		 
		
		//Ingreso_Controller::registro_gs(0,$fecha_desde,$fecha_hasta);

	//Obtener Gastos
	//Obtener Resumenes por Medio de Pago
	 
			//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
	 
		//$pdf->Ln( 150 );

		//$pdf->Write( 6, "Egresos:" );
		//$pdf->Ln( 12 );
		 

}

public static function registro_gs($gs_tipo,$fecha_desde,$fecha_hasta,$pdf = null){
    	$respuesta = reporte::reporte_gs($gs_tipo,$fecha_desde,$fecha_hasta);
    	// 
    	$permiso = $_SESSION['usuario']->getId_Acceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";
		if ($gs_tipo == 0) {
			# code...
			$tipo_gs_nombre = "Todos";
		}else{
			$tipo_gs_nombre = $respuesta[0][0][1];
		}
		$bandera_pdf = true;
    	if ($pdf != null) {
    		$bandera_pdf = false;
    	}
    	if ($bandera_pdf) {
    		ini_set("session.auto_start", 0);
    		$pdf = new FPDF( 'P', 'mm', 'A4' );
	    	$pdf->AddPage();
			$hoy = getdate();
	        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
			$pdf->Ln( 16 );
			$pdf->SetFont( 'Arial', '', 12 );
			$pdf->Write( 6, $nombre_negocio."Reporte de Gastos\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora."\n".'Tipo de Gastos: '.$tipo_gs_nombre );
		
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
    	}
       	
		$columnas = ['N°','Nombre','Tipo','Valor','Fecha','Movimientos'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}
			 
			else{
				$pdf->Cell( 37, 12, $columnas[$i], 1, 0, 'C', true );
			}
		   
		}
		//Agregando las Filas
		
		$respuesta_final = array();
		$medio_limpio = array();
	 	$precio_recaudacion_ = 0;
	 	$numero_cont = 1;
	 	
 	 
		foreach ($respuesta as $key => $value) {
 			 
 			 
			$tipo_gs = $value[0][0];

			$gs_unico = $value[1][0];
			
			 

			if ($gs_tipo == 0) {
				# code...
			//$gs_unico[0]
			 
			foreach ($gs_unico as $key2 => $value2) {
				# code...
				 
				//$gs_unico_ = $value2->getId_ggs()->getId_gasto_unico();
				//print_r($value2);
			 	
				$nombre_gs = $value2->getNombre();
				$valor_gs = $value2->getValor();
				$fecha_gs = $value2->getFecha_hora();
				$estado_gs = $value2->getHabilitado();

				$precio_recaudacion_ = $precio_recaudacion_ + $valor_gs;

				$movimientos_gs = $value2->getId_gsub_gasto();
			 
				if ($movimientos_gs != null) {
					# code...
				 
					$sgd_unico = $movimientos_gs->getId_sub_gasto();

					$nombres_sgs = array();
					foreach ($sgd_unico as $key3 => $value3) {
					# code...
						$nombre_sgs = $value3->getNombre();
						$valor_sgs = $value3->getValor();
						$condicion_sgs = $value3->getCondicion();

						$nombres_sgs[] = $nombre_sgs.' ['.$valor_sgs.']'.'('.$condicion_sgs.')';
					}

					$respuesta_final[] = [$numero_cont,$nombre_gs,$tipo_gs,$valor_gs,$fecha_gs,$nombres_sgs];
					continue;
				}else{

					$respuesta_final[] = [$numero_cont,$nombre_gs,$tipo_gs,$valor_gs,$fecha_gs,'Sin movimientos'];	
					continue;
				}

				$numero_cont = $numero_cont + 1;
			}

			}else{
				foreach ($gs_unico as $key2 => $value2) {
				# code...
				 
				//$gs_unico_ = $value2->getId_ggs()->getId_gasto_unico();
				$nombre_gs = $value2->getNombre();
				$valor_gs = $value2->getValor();
				$fecha_gs = $value2->getFecha_hora();
				$estado_gs = $value2->getHabilitado();

				$precio_recaudacion_ = $precio_recaudacion_ + $valor_gs;

				$movimientos_gs = $value2->getId_gsub_gasto();
			 
				if ($movimientos_gs != null) {
					# code...
				
					$sgd_unico = $movimientos_gs->getId_sub_gasto();

					$nombres_sgs = ' ';
					$valor_sgs = 0;
					foreach ($sgd_unico as $key3 => $value3) {
					# code...
						$nombre_sgs = $value3->getNombre();
						$valor_sgs = $value3->getValor();
						$condicion_sgs = $value3->getCondicion();

						$nombres_sgs = $nombres_sgs.' ['.$valor_sgs.']'.'('.$condicion_sgs.')';
					}

					$respuesta_final[] = [$numero_cont,$nombre_gs,$tipo_gs,$valor_gs,$fecha_gs,$nombres_sgs];
					//$respuesta_final[] = [$numero_cont,$nombre_gs,$tipo_gs,$valor_gs,$fecha_gs,'Si posee'];
					continue;
				}else{
					$respuesta_final[] = [$numero_cont,$nombre_gs,$tipo_gs,$valor_gs,$fecha_gs,'Sin movimientos'];	
					continue;
				}

				$numero_cont = $numero_cont + 1;
			}

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
	  					$pdf->Cell( 37, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  				else{
	  					$pdf->Cell( 37, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}
	  			}
	  		  
	  		}

	  		$row++;
	  		$fill = !$fill;
	  		$pdf->Ln( 12 );
		}
		$pdf->Cell( 0, 15, 'Total de Gastos: $'.$precio_recaudacion_, 1, 0, 'C', true );
		//$pdf->Write( 6, "Reporte de Articulos Vendidos\nAscenso Positivo\n"." Generado por: ".$_SESSION["usuario"]->getUsuario()."\n Fecha de Generacion: ".$ahora );
		
		if ($bandera_pdf) {
			$pdf->Ln( 12 );
			ob_end_clean();
			$pdf->Output( "ReporteDeGastos.pdf", "I" );
		}
		
    	
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
			//print_r($reversed);
			foreach ($reversed as $key => $value) {
				$tpl->newBlock("con_articulos_lista_cuerpo");


				$tpl->assign("numero", $contador);
				$contador = $contador + 1;
				//Nombre Articulo
				$gunico = $value->getId_gunico();
				$nombre_art = $gunico->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
				$nom_marca = $gunico->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
				$nom_tipo = $gunico->getId_lote_local()->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();

				$nombre_art_vendido = $nombre_art.','.$nom_marca.','.$nom_tipo;

				//generar lote local para obtener el local
				if ($gunico->getId_lote_local()->getId_lote()->getId_gc()) {
					# code...
			
				if ($gunico->getId_lote_local()->getId_lote()->getId_gc() != null) {
					# code...
				
				$gc = $gunico->getId_lote_local()->getId_lote()->getId_gc()->getId_categoria();

				$attrs = '';
                foreach ($gc as $clave => $valor) {

                	$valorattr = $valor->getValor();
                	$nombre = $valor->getNombre();
                	
                	$attrs = $attrs.$valorattr.'('.$nombre.')';
                	
                   
                   }

				}
				else{
					$attrs = 'Sin definir';
				}

				}




				$tpl->assign("art", $nombre_art_vendido);
				$nom_usuario = $value->getId_venta()->getId_usuario()->getUsuario();

				$tpl->assign("usuario", $nom_usuario);
				$nom_local = $value->getId_lote_local()->getId_local()->getNombre();

				$tpl->assign("local", $nom_local);
				$fecha_venta = $value->getId_venta()->getFecha_hora();

				$tpl->assign("attrs", $attrs);

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
				$tpl->assign("disabled_ok", 'disabled');
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

		$permiso = $_SESSION['usuario']->setId_Acceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";

		$pdf->Write( 6, $nombre_negocio."Reporte de Articulos Vendidos\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora."\n".'El simbolo (*) simboloza un cambio en la venta.' );
		
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
			$descuento = $value->getId_venta()->getMedio_pago()->getDesImp()->getValor();
			$medio_pago = $value->getId_venta()->getMedio_pago()->getNombre();
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

		$permiso = $_SESSION['usuario']->setId_Acceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";

		$pdf->Write( 6, $nombre_negocio."Reporte de Articulos Vendidos\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
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

		$permiso = $_SESSION['usuario']->setId_Acceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";

		$pdf->Write( 6, $nombre_negocio."Reporte de Articulos Vendidos\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
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
    	$todo = false;
    	if ($medio != 0) {
    		$medio_ = art_venta_medio_pago::generar($medio);
    	}
    	if ($local != 0) {
    		$local_ = art_local::generar_local_2($local);
    	}
    	
    	if ($medio == 0) {
    		if ($local == 0) {
    			$respuesta = reporte::reporte_co($fecha_desde,$fecha_hasta,0,0);
    		}
    		
    		if (is_numeric($local)) {
    		}else{
    			$respuesta = reporte::reporte_co($fecha_desde,$fecha_hasta,0,$local->getId_local());
    		}	
    		$todo = true;
    	}else{
    		$respuesta = reporte::reporte_co($fecha_desde,$fecha_hasta,$medio,$local);
    		$medio_generado = art_venta_medio_pago::generar($medio);
    		$medio_nombre_segmentacion = $medio_generado->getNombre();
    	}
    	
    	ini_set("session.auto_start", 0);
    	//ob_start();
		//error_reporting(E_ALL & ~E_NOTICE);
		//ini_set('display_errors', 0);
		//ini_set('log_errors', 1);

		$respuesta_final = array();
		$medio_limpio = array();
	 	$precio_recaudacion_ = 0;
	 	$numero_cont = 1;
	  	//print_r($respuesta);
	   	$nombres_mp2 = '';
		$nom_completo2 = '';
		$una_veez = true;
		$precio_unitario_art2 = 0;

		foreach ($respuesta as $key => $value) {

			$medio_pago = $value->getId_venta()->getId_gmedio_pago()->getId_medio_pago();

			foreach ($medio_pago as $key1 => $value1) {
				 
				$nombre_mediopago = $value1->getNombre();
				$des_imp_valor = $value1->getDesImp()->getValor();
				if ($des_imp_valor != 0) {
					$des_imp_signo = $value1->getDesImp()->getSigno();
					$des_imp = '('.$des_imp_signo.$des_imp_valor.'%)';
				}else{
					$des_imp = '';
				}

				if ($una_veez) {
					 $nombres_mp2 =$nombres_mp2.$nombre_mediopago.$des_imp;
				}else{
					$nombres_mp2 =$nombres_mp2.'-'.$nombre_mediopago.$des_imp;
					$una_veez = false;
				}
			}
		

			$precio_final = $value->getId_venta()->getTotal();
			$medio_sin = str_replace('$','',$precio_final);
			$porciones = explode("x", $medio_sin);
			$cuotas = $value->getId_venta()->getCuotas();

			$gunico = $value->getId_gunico();
			$total_art_count = count($gunico);
			
		   
			//foreach ($gunico as $key6 => $value6) {
				$gunico_lote_local = $gunico->getId_lote_local();
				//$cantidad_lote_local = $value6->getCantidad();
				$cantidad = $gunico->getCantidad();
				$rg_detalle = $gunico->getRg_detalle();
				$counter = 0;

				foreach ($gunico_lote_local as $key2 => $value2) { 
					$lote = $value2->getId_lote();
					$nombre_art = $lote->getId_art_conjunto()->getId_articulo()->getNombre();
					$nom_marca = $lote->getId_art_conjunto()->getId_marca()->getNombre();
					$nom_tipo = $lote->getId_art_conjunto()->getId_tipo()->getNombre();
					$nom_completo2 = $nom_marca.','.$nom_tipo.'('.$cantidad[$counter].')';

					if (count($porciones) >1) {
						$cantidad_cuotas = $porciones[0];
						$precio_base = $porciones[1];
						$precio_final_final = (integer)$cantidad_cuotas * (integer)$precio_base;
					}else{
						$precio_final_final = $porciones[0];
					}
					//$prc_costo = $lote->getPrecio_base();
					//$prc_importe = $lote->getImporte();
					//$prc_moneda =$lote->getId_moneda()->getValor();
					//$prc_axu = floatval($prc_costo) * floatval($prc_moneda);

					$precio_aux = explode(",", $rg_detalle[0]);
					$precio_rg_aux = str_replace('(','',$precio_aux[3]);
					$precio_unitario_art2 = str_replace(')','',$precio_rg_aux);


					//$precio_unitario_art2 = floatval($prc_axu) * floatval($prc_importe);
					$precio_recaudacion_ = $precio_recaudacion_ + $precio_final_final;
					$medio_limpio[] = $medio_sin;

					$counter = $counter + 1 ;

				}
			//}

			$respuesta_final[] = [$numero_cont,$nom_completo2,$nombres_mp2,$precio_unitario_art2,$precio_final];
			$numero_cont = $numero_cont + 1;

			$nombres_mp2 = '';
				

		}
		
		//print_r($respuesta_final);

		$pdf = new FPDF( 'P', 'mm', 'A4' );
    	$pdf->AddPage();
		$hoy = getdate();
        $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$pdf->Ln( 16 );
		$pdf->SetFont( 'Arial', '', 12 );

		$permiso = $_SESSION['usuario']->getAcceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
	
		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";

		$pdf->Write( 6, $nombre_negocio."Reporte de Articulos Vendidos por medio de pago definido\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
		$pdf->Write( 6, "\nFecha Desde: ".$fecha_desde."\nFecha Hasta: ".$fecha_hasta);
		if ($local != 0) {
		 
			$pdf->Write( 6, "\nLocal: ".$local_->getNombre());
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
		$pdf->SetTextColor( 0, 0, 0 );
		$pdf->SetFillColor( 255, 255, 255 );
		$columnas = ['N°','Articulo','Medio de Pago','SubTotal','Total'];

		for ( $i=0; $i<count($columnas); $i++ ) {
			if ($i == 0) {
				$pdf->Cell( 10, 12, $columnas[$i], 1, 0, 'C', true );
			}else{
				if ($i == 1) {
					$pdf->Cell( 66, 12, $columnas[$i], 1, 0, 'C', true );
				}else{
					if ($i == 3) {
						$pdf->Cell( 20, 12, $columnas[$i], 1, 0, 'C', true );
					}
					else{
						$pdf->Cell( 47, 12, $columnas[$i], 1, 0, 'C', true );
					}
				}
			}
			
		   
		}
		 
		$fill = false;
		$row = 0;
		$banban = true;
		$banban2 = true;
		foreach ( $respuesta_final as $dataRow ) {

  			// Create the left header cell
	  		//$pdf->SetFont( 'Arial', 'B', 15 );
	  		//$pdf->SetTextColor( 0, 0, 0 );
	  		//$pdf->SetFillColor( 255, 255, 255 );
	  		//$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );

  			// Create the data cells
	  		$pdf->SetTextColor( 0, 0, 0 );
	  		$pdf->SetFillColor(  255, 255, 255 );
	  		$pdf->SetFont( 'Arial', '', 12 );

	  		for ( $i=0; $i<count($columnas); $i++ ) {
	  			if ($banban2) {
	  				$pdf->Ln( 12 );
	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}else{
	  					if ($i == 1) {
							$pdf->Cell( 66, 12, $dataRow[$i], 1, 0, 'C', true );
						}else{
							if ($i == 3) {
								$pdf->Cell( 20, 12, $dataRow[$i], 1, 0, 'C', true );
							}
							else{
								$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
							}
						}
	  				}
	  				
	  				
	  				$banban2 = false;
	  			}else{

	  				if ($i == 0) {
	  					$pdf->Cell( 10, 12, $dataRow[$i], 1, 0, 'C', true );
	  				}else{
	  					if ($i == 1) {
							$pdf->Cell( 66, 12, $dataRow[$i], 1, 0, 'C', true );
						}else{
							if ($i == 3) {
								$pdf->Cell( 20, 12, $dataRow[$i], 1, 0, 'C', true );
							}
							else{
								$pdf->Cell( 47, 12, $dataRow[$i], 1, 0, 'C', true );
							}
						}
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

		$permiso = $_SESSION['usuario']->setId_Acceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";

		$pdf->Write( 6, $nombre_negocio."Reporte de Articulos Vendidos\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
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

		$permiso = $_SESSION['usuario']->setId_Acceso();
		if (strcmp($permiso, "ADMIN" ) == 0 ) {
			# code...
			$id_jefe = $_SESSION['usuario']->getId_user();
		}else{
			
			$id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
		}
		

		$tocliente = ot_cliente::generar($id_jefe);
		$nombre_ng = $tocliente->getNombre();

		$nombre_negocio = $nombre_ng."\n";

		$pdf->Write( 6, $nombre_negocio."Reporte de Stock de Articulos\n"."Generado por: ".$_SESSION["usuario"]->getUsuario()."\nFecha de Generacion: ".$ahora );
		
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
				if ($value->getId_lote()->getId_us_gcat()) {
					# code...
				
				$gc = $value->getId_lote()->getId_us_gcat()->getId_categoria();
                foreach ($gc as $clave => $valor) {
                    if (strcmp($valor->getNombre(), "Medida" ) == 0 ) {
                        $medida = $valor->getValor();

                                            
                    }
                }

				}
				else{
					$medida = "Sin definir";
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