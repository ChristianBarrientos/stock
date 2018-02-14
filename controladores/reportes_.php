<?php
use Dompdf\Dompdf;
//require __DIR__.'/../vendor/autoload.php';
//use Spipu\Html2Pdf\Html2Pdf;


class Reportes_Controller{

	public static function generar_pdf($html = null){
		if (isset($_SESSION["usuario"])){
			if ($_SESSION["permiso"] == 'ADMIN') {
				ini_set("session.auto_start", 0);
				$dompdf = new Dompdf();

				//$html = file_get_contents("tabla.html");
				$html = preg_replace('/>\s+</', '><', $html);

				$html=utf8_decode($html);

				$dompdf->loadHtml($html);
				//ini_set("memory_limit","128M");
				$dompdf->setPaper('A4');
				$dompdf->render();
				ob_end_clean();
				$dompdf->stream("otium",array("Attachment"=>0)); 
				
				
				//error_reporting(E_ALL & ~E_NOTICE);
				//ini_set('display_errors', 0);
				//ini_set('log_errors', 1);
				/*$html2pdf = new Html2Pdf();
				$html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
				
				$html2pdf->output();*/
			}
			
		}
		else{
			return Ingreso_Controller::salir();
		}

		return $tpl->getOutputContent();

	}


	public static function generar_html(){
		if (isset($_SESSION["usuario"])){
			if ($_SESSION["permiso"] != 'ADMIN') {
				return Ingreso_Controller::salir();
			}
		}
		$clave_reporte = $_GET['clave_reporte'];

		$fecha_desde = $_POST['fecha_desde'];
		$fecha_hasta = $_POST['fecha_hasta'];
		$fecha_mes_anio = $_POST['fecha_mes_anio'];

		switch ($clave_reporte) {
			case 1:
				//Reportes Semanales

			$tpl = Reportes_Controller::reporte_global_detallado($fecha_desde,$fecha_hasta);
				//Reportes_Controller::generar_pdf($tpl);
			break;	

			case 2:
			//Reporte Global sin Detalles

			$tpl = Reportes_Controller::reporte_global($fecha_mes_anio);
			//Reportes_Controller::generar_pdf($tpl);
			break;

			case 3:
			//Reportes de Ventas

			$tpl = Reportes_Controller::reporte_ventas($fecha_desde,$fecha_hasta);
			//Reportes_Controller::generar_pdf($tpl);
			break;

			
			default:
				# code...
			break;
		}

		return $tpl;
	}

	public static function armar_pdf(){

		if (isset($_SESSION["usuario"])){
			if ($_SESSION["permiso"] != 'ADMIN') {
				return Ingreso_Controller::salir();
			}
		}
		$clave_reporte = $_GET['clave_reporte'];
		if (isset($_POST['fecha_desde'])) {
			$fecha_desde = $_POST['fecha_desde'];
			$fecha_hasta = $_POST['fecha_hasta'];
			$fecha_mes_anio = $_POST['fecha_mes_anio'];
		}else{
			$fecha_desde = $_GET['fecha_desde'];
			$fecha_hasta = $_GET['fecha_hasta'];
			$fecha_mes_anio = $_GET['fecha_mes_anio'];
		}
		

		switch ($clave_reporte) {
			case 1:
			//Reporte Global con Detalles

			$tpl = Reportes_Controller::reporte_global_detallado($fecha_desde,$fecha_hasta,false);
			Reportes_Controller::generar_pdf($tpl);
			break;	

			case 2:
			//Reporte Global sin Detalles

			$tpl = Reportes_Controller::reporte_global($fecha_mes_anio,false);
			Reportes_Controller::generar_pdf($tpl);
			break;

			case 3:
			//Reportes de Ventas

			$tpl = Reportes_Controller::reporte_ventas($fecha_desde,$fecha_hasta);
			Reportes_Controller::generar_pdf($tpl);
			break;
			
			default:
				# code...
			break;
		}
	}

	public static function obtener_dia($fecha,$sb = true){
		$dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
		$dia = $dias[date('N', strtotime($fecha))];
		return $dia;
	}

	public static function nombre_mes($anio,$mes){
		//setlocale(LC_TIME, 'spanish');  
		$nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, $anio)); 
		return $nombre;
	} 

	public static function ultimo_dia_mes($mes,$anio){
		$my_date = new DateTime();

		$my_date->modify('last day of'.' '.$mes.' '.$anio);
		return $my_date->format('Y-m-d');

		 
	}

	public static function reporte_ventas($fecha_desde,$fecha_hasta){

	}

	public static function reporte_global($fecha_mes_anio,$sb = true){

		$fecha_array = explode('-', $fecha_mes_anio);
		$nombre_mes = Reportes_Controller::nombre_mes($fecha_array[0],$fecha_array[1]);

		$fecha_hasta = Reportes_Controller::ultimo_dia_mes($nombre_mes,$fecha_array[0]);
		$fecha_desde = $fecha_mes_anio.'-'.'01';

		$respuesta = reporte::reporte_por_semana($fecha_desde,$fecha_hasta);
		$tpl = new TemplatePower("template/reportes/tabla.html");
		$tpl->prepare();

		$encabezado_html =  Reportes_Controller::encabezado_reporte('Reporte Global',$fecha_desde,$fecha_hasta);
		$tpl->assign("encabezado",$encabezado_html);
		
		
		 
		$opciones = ['LOCAL','Ventas CONTADO-EFECTIVO','Ventas OTROS MEDIOS','Ventas TOTAL'];
		foreach ($opciones as $key => $value) {
			$tpl->newBlock("columna_tabla");
			$tpl->assign("nombre_columna",$value);
		}

		$total_ventas_contado = 0;
		$total_ventas_omp = 0;
		$total_ventas_total = 0;

		$total_total_ventas_contado = 0;
		$total_total_ventas_omp = 0;
		$total_total_ventas_total = 0;

		$resultado_final = array();
		foreach ($_SESSION['locales'] as $key2 => $value2) {

			$nombre_local = $value2->getNombre();
			$id_loca_flag = $value2->getId_local();
			foreach ($respuesta as $key => $value) {
				$id_venta = $value->getId_venta();
				if ($id_venta == 'BORRADO') {
					continue;
				}else{
					$rg_detalle = $value->getRg_detalle();
					$rg_detalle_array = explode(',', $rg_detalle);
					$id_local = $rg_detalle_array[3];
					
					$rg_detalle_mp = $value->getId_gmedio_pago()->getRg_detalle();
					$rg_detalle_mp_array = explode(',', $rg_detalle_mp);
					$nombre_mp = $rg_detalle_mp_array[0];
					if ($id_loca_flag == $id_local) {
					
						if (((strpos(strtoupper($nombre_mp),'CONTADO')) ||  (strpos(strtoupper($nombre_mp),'EFECTIVO')))) {

							$total_ventas_contado = floatval($total_ventas_contado) + floatval($value->getTotal());
							

						}else{
							$total_ventas_omp = floatval($total_ventas_omp) + floatval($value->getTotal());
						}
						$total_ventas_total = floatval($total_ventas_total) + floatval($value->getTotal());
						$value->setId_venta('BORRADO');
					}

				}
			}

			$total_total_ventas_contado = floatval($total_total_ventas_contado) + floatval($total_ventas_contado);

			$total_total_ventas_omp = floatval($total_total_ventas_omp) + floatval($total_ventas_omp);

			$total_total_ventas_total = floatval($total_total_ventas_total) + floatval($total_ventas_total);

			$resultado_final[] = [$nombre_local,$total_ventas_contado,$total_ventas_omp,$total_ventas_total];

			$total_ventas_contado = 0;
			$total_ventas_omp = 0;
			$total_ventas_total = 0; 
		}
		 
		foreach ($resultado_final as $key => $value) {
 			
 			$tpl->newBlock("filas_tabla");
 			$tpl->newBlock("datos_fila_tabla_global");
 			
 			$tpl->assign("dato_fila0",$value[0]);
 			$tpl->assign("dato_fila1",$value[1]);
 			$tpl->assign("dato_fila2",$value[2]);
 			$tpl->assign("dato_fila3",$value[3]);
		}

		
		$tpl->newBlock("total_global");
		$tpl->assign("cantidad_columnas",count($opciones));
		$tpl->assign("total_total_ventas_contado",$total_total_ventas_contado);
 		$tpl->assign("total_total_ventas_omp",$total_total_ventas_omp);
 		$tpl->assign("total_total_ventas_total",$total_total_ventas_total);

 		$respuesta_gs = reporte::reporte_gs(0,$fecha_desde,$fecha_hasta);
 		$total_egresos = 0;
 		$total_gs_unico = 0;
 		$tpl->newBlock("gastos_tabla_global");

 		foreach ($respuesta_gs as $key => $value) {
 			$nombre_gs = $value[0][0];
 			$detalles_gs = $value[1];

 			$tpl->newBlock("filas_tabla_gs");
 			$tpl->assign("nombre_gasto",strtoupper($nombre_gs));

 			foreach ($detalles_gs as $key2 => $value2) {
 				$nombre_detalle_gs = $value2->getnombre();
 				$fecha_gs = $value2->getFecha_hora();

 				$fecha_gs = explode(" ", $fecha_gs);
 				$fecha_gs = $fecha_gs[0];


 				$monto_gs = $value2->getValor();
 				 
 				$total_gs_unico = $total_gs_unico + $monto_gs;

 			}
 			$tpl->assign("total",$total_gs_unico);
 			
 			$total_egresos = $total_egresos + $total_gs_unico;
 			$total_gs_unico = 0;
 		}
 		$tpl->newBlock("total_gs_global");
 		$tpl->assign("total_gastos",$total_egresos);
 		

		if ($sb) {

			$tpl->newBlock("boton_pdf_global");
			$tpl->assign("fecha_mes_anio",$fecha_mes_anio);

		}
		return $tpl->getOutputContent();

	}

	public static function encabezado_reporte($titulo,$fecha_desde,$fecha_hasta){
		$encabezado = new TemplatePower("template/reportes/encabezados.html");
		$encabezado->prepare();
		$encabezado->newBlock("encabezado_reporte_1");
		$ot_cl = ot_cliente::generar($_SESSION["usuario"]->getId_user());
		$nombre_cliente = $ot_cl->getNombre();
		$encabezado->assign("nombre_cliente",$nombre_cliente);
		$encabezado->assign("titulo",$titulo);
		$usuario = $_SESSION["usuario"]->getUsuario();
		$encabezado->assign("usuario",$usuario);
		$hoy = getdate();
		$ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$encabezado->assign("fecha_actual",$ahora);
		$dia_desde = Reportes_Controller::obtener_dia($fecha_desde);
		$dia_hasta = Reportes_Controller::obtener_dia($fecha_hasta);

		$encabezado->assign("fecha_desde",'('.$dia_desde.') '.$fecha_desde);
		$encabezado->assign("fecha_hasta",'('.$dia_hasta.') '.$fecha_hasta);
		return $encabezado->getOutputContent();
	}

	public static function reporte_global_detallado($fecha_desde,$fecha_hasta,$sb = true){
		$respuesta = reporte::reporte_por_semana($fecha_desde,$fecha_hasta);
		
		$tpl = new TemplatePower("template/reportes/tabla.html");
		$tpl->prepare();

		$encabezado_html =  Reportes_Controller::encabezado_reporte('Reporte Global Detallado',$fecha_desde,$fecha_hasta);
		$tpl->assign("encabezado",$encabezado_html);

		if (isset($_SESSION['locales'])) {
			$cantidad_locales = count($_SESSION['locales']);
			$tpl->newBlock("columna_tabla");
			$tpl->assign("nombre_columna",'DIAS');

			foreach ($_SESSION['locales'] as $key2 => $value2) {

				$nombre_local = $value2->getNombre();
				$tpl->newBlock("columna_tabla");
				$tpl->assign("nombre_columna",$nombre_local);

			}
        	//$ventas_local_dias = new SplFixedArray($cantidad_locales);
			$ventas_local_dias = array();
			$dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
			$total_ventas = 0;
			$ventas_nocontado = array();
			foreach ($dias as $key => $value) {

				foreach ($_SESSION['locales'] as $key2 => $value2) {
					$id_local = $value2->getId_local();
					$ventas_del_dia_aux = 0;
					foreach ($respuesta as $key3 => $value3) {
						$rg_detalle = $value3->getRg_detalle();
						$rg_detalle_array = explode(',', $rg_detalle);

						$id_local_resp = $rg_detalle_array[3];
						$fecha_venta = $value3->getFecha_hora();
						$dia_de_venta = Reportes_Controller::obtener_dia($fecha_venta);
						$medio_pago_venta_nombre = $value3->getId_gmedio_pago()->getRg_detalle(); 
						if (((strpos(strtoupper($medio_pago_venta_nombre),'CONTADO')) ||  (strpos(strtoupper($medio_pago_venta_nombre),'EFECTIVO')))) {


							if (($id_local_resp == $id_local) && (strcmp($value,$dia_de_venta) == 0 )  )  {

								$total_res = $value3->getTotal();
								$ventas_del_dia_aux = $ventas_del_dia_aux + $total_res;
							}
						}else{
							$ventas_nocontado[] = $value3;
						}
					}
					$total_ventas = $total_ventas + $ventas_del_dia_aux;
					$ventas_local_dias[] = $ventas_del_dia_aux;
				}



			}
			$counter = 0;

			for ($i=0; $i < count($ventas_local_dias); $i+=4) { 

				if ($cantidad_locales == 1) {

					$tpl->newBlock("filas_tabla");
					$tpl->newBlock("datos_fila_tabla_1");
					$tpl->assign("dato_fila0",$dias[$counter]);
					$tpl->assign("dato_fila1",$ventas_local_dias[$i]);
					$counter = $counter + 1 ;

				}

				if ($cantidad_locales == 4) {

					$tpl->newBlock("filas_tabla");
					$tpl->newBlock("datos_fila_tabla_3");
					$tpl->assign("dato_fila0",$dias[$counter]);
					$aux1 = $i + 1;
					$aux2 = $i + 2;
					$aux3 = $i + 3;
					$tpl->assign("dato_fila1",$ventas_local_dias[$i]);
					$tpl->assign("dato_fila2",$ventas_local_dias[$aux1]);
					$tpl->assign("dato_fila3",$ventas_local_dias[$aux2]);
					$tpl->assign("dato_fila4",$ventas_local_dias[$aux3]);

					$counter = $counter + 1 ;

				}
			}
			$tpl->newBlock("total_ventas");
			$tpl->assign("cantidad_locales",$cantidad_locales + 1);
			$tpl->assign("total_ventas",round($total_ventas,2));
			
			$fecha_desde_dia_array_ = explode('-', $fecha_desde);
			$fecha_hasta_dia_array_ = explode('-', $fecha_hasta);


			$datetime1 = date_create($fecha_desde);
			$datetime2 = date_create($fecha_hasta);
			$interval = date_diff($datetime1, $datetime2);

			$diff_dias = intval($interval->format('%a')) +1;
			$tpl->assign("cantidad_dias",$diff_dias);
			$tpl->assign("total_ventas_promedio",round(($total_ventas /$diff_dias),2));


			$respuesta_gs = reporte::reporte_gs(0,$fecha_desde,$fecha_hasta);

			$total_gs_unico = 0;
			$total_egresos = 0;
			foreach ($respuesta_gs as $key => $value) {
				$nombre_gs = $value[0][0];
				$detalles_gs = $value[1];

				$tpl->newBlock("gastos_tabla");
				$tpl->assign("titulo_gasto",strtoupper($nombre_gs));
				foreach ($detalles_gs as $key2 => $value2) {
					$nombre_detalle_gs = $value2->getnombre();
					$fecha_gs = $value2->getFecha_hora();

					$fecha_gs = explode(" ", $fecha_gs);
					$fecha_gs = $fecha_gs[0];


					$monto_gs = $value2->getValor();
					$tpl->newBlock("filas_tabla_gs_detallado");
					$tpl->assign("fecha_gasto",$fecha_gs);
					$tpl->assign("nombre_subagsto",strtoupper($nombre_detalle_gs));
					$tpl->assign("subtotal",$monto_gs);
					$total_gs_unico = $total_gs_unico + $monto_gs;

				}
				$tpl->newBlock("total_gs");
				$tpl->assign("total_gsunico_valor",$total_gs_unico);
				$total_egresos = $total_egresos + $total_gs_unico;
				$total_gs_unico = 0;


			}

			$tpl->newBlock("total_egresos");
			$tpl->assign("total_egresos",$total_egresos);

        	//SOLO PARA MOTOMATCH
			$ot_cl = ot_cliente::generar($_SESSION["usuario"]->getId_user());
			$nombre_cliente = $ot_cl->getNombre();

			$tpl->newBlock("ventas_nocontado");

			$medios_pago_user = array();
			foreach ($ventas_nocontado as $key => $value) {
				$rg_detalle_mp = $value->getId_gmedio_pago()->getRg_detalle();
				$rg_detalle_mp_array = explode(',', $rg_detalle_mp);

				$medios_pago_user[] = $rg_detalle_mp_array[0];

			}
			
			$medios_pago_user = array_unique($medios_pago_user);
			$total_ventas_mp_nocontado = 0;
			$total_ventas_nocontado = 0;
			foreach ($medios_pago_user as $key => $value) {


				$tpl->newBlock("tabla_nocontado");
				$tpl->assign("titulo_nombre_mp",$value);

				foreach ($ventas_nocontado as $key2 => $value2) {


					$rg_detalle_mp = $value2->getId_gmedio_pago()->getRg_detalle();
					$rg_detalle_mp_array = explode(',', $rg_detalle_mp);
					$nombre_mp = $rg_detalle_mp_array[0];
					

					if (strcmp(strtoupper($nombre_mp),strtoupper($value)) == 0) {

						if (strcmp($nombre_cliente,'MOTOMATCH') == 0 ) {
							$id_venta = $value2->getId_venta();
							//CONSUME MUCHOS RECURSOR VER OTRA MANERA DE QUE NO RECCORRA DOS VECES LO MISMO
							if ($id_venta == 'BORRADO') {
								continue;
							}else{


								if ($value2->getRg_detalle() != '' && $value2->getRg_detalle() != null) {

									$rg_detalle = $value2->getRg_detalle();
									$rg_detalle_array = explode(',', $rg_detalle);
									$nrocomp = $rg_detalle_array[0];
									$local = $rg_detalle_array[3];

									$local_genera = art_local::generar_local_2($local);
									$local = $local_genera->getNombre();

								}else{
									$nrocomp = 's/d';
									$local = 's/d';
								}
								$fecha_venta = $value2->getFecha_hora();
								$fecha_venta = explode(" ", $fecha_venta);
								$fecha_venta = $fecha_venta[0];
								$total_res = $value2->getTotal();
								
								$tpl->newBlock("filas_tabla_nocontado");
								$tpl->assign("local",$local);
								$tpl->assign("fecha_gasto",$fecha_venta);
								$tpl->assign("nrocomp",$nrocomp);
								$tpl->assign("monto",$total_res);

								$total_ventas_mp_nocontado = $total_ventas_mp_nocontado + $total_res; 
								$value2->setId_venta('BORRADO');
							}
						}

					}
				}
				
				$tpl->newBlock("total_nocontado");
				$tpl->assign("nombre_mp",$value);
				$tpl->assign("total_por_mp",$total_ventas_mp_nocontado);
				$total_ventas_nocontado = $total_ventas_nocontado + $total_ventas_mp_nocontado;
				$total_ventas_mp_nocontado = 0;

			}
			/*$tpl->newBlock("total_total_nocontado");
			$tpl->assign("total_ventas_otros_medios",$total_ventas_nocontado);
			*/
			if ($sb) {

				$tpl->newBlock("boton_pdf");
				$tpl->assign("fecha_desde",$fecha_desde);
				$tpl->assign("fecha_hasta",$fecha_hasta);
			}
			return $tpl->getOutputContent();

		}
	}
}
?>