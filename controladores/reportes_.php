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
		$medio_pago = $clave_reporte = $_POST['venta_medio_parametro_descripcion'];
		$local = $clave_reporte = $_POST['venta_local_id'];

		$tpl = Reportes_Controller::reporte_ventas($fecha_desde,$fecha_hasta,true,$medio_pago,$local);
		//Reportes_Controller::generar_pdf($tpl);
		break;

		case 4:
		//Reportes de Ventas
		

		$tpl = Reportes_Controller::reporte_ventas_2();
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
		$medio_pago = $clave_reporte = $_GET['medio_pago'];
		$local = $clave_reporte = $_GET['local'];

		$tpl = Reportes_Controller::reporte_ventas($fecha_desde,$fecha_hasta,false,$medio_pago,$local);
		Reportes_Controller::generar_pdf($tpl);
		break;

		case 4:
		//Reportes de Ventas
		

		$tpl = Reportes_Controller::reporte_ventas_2();
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

public static function reporte_ventas_2(){
	$respuesta = reporte::reporte_por_semana(0,0);
	$tpl = new TemplatePower("template/reportes/ventas.html");
	$tpl->prepare();
	$counter = 1;
	$respuesta = array_reverse($respuesta);
	foreach ($respuesta as $key => $value) {
		
		$medio_pago = $value->getId_gmedio_pago();
		if (is_array($medio_pago)) {
			foreach ($medio_pago as $key3 => $value3) {

				$rg_detalle_mp = $value3->getRg_detalle();
				$rg_detalle_mp_array = explode(',',$rg_detalle_mp);
				$nombre_mp = $rg_detalle_mp_array[0];
				$nombres_mp = $nombres_mp.' '.$nombre_mp;
			}
		}else{
			$rg_detalle_mp = $medio_pago->getRg_detalle();
			$rg_detalle_mp_array = explode(',',$rg_detalle_mp);
			$nombre_mp = $rg_detalle_mp_array[0];
			$nombres_mp =  $nombre_mp;
		}
		$id_venta = $value->getId_venta();
		$art_unico = art_unico::obtener_por($id_venta);
		
		if ($art_unico == null) {
			continue;
		}
		 
		$art_gunico = $art_unico->getId_gunico();
		$art_lote_local_array = $art_gunico->getId_lote_local();
		foreach ($art_lote_local_array as $key2 => $value2) {

			$tpl->newBlock("datos_fila_tabla_4");
			$tpl->assign("dato_fila0",$counter);
			$lote = $value2->getId_lote();

			$art = $lote->getId_art_conjunto()->getId_articulo()->getNombre();
			$marca = $lote->getId_art_conjunto()->getId_marca()->getNombre();
			$tipo = $lote->getId_art_conjunto()->getId_tipo()->getNombre();
			$nombre_ = $art.','.$marca.','.$tipo;
			//$nombre_ = str_replace(' ','',$nombre_);
			//$nombre_ = $marca.','.$tipo;

			if ($lote->getId_gc() != null) {
				$gc = $lote->getId_gc()->getId_categoria();
				$attrf = '';
				foreach ($gc as $clave => $valor) {

					$nombre_att = $valor->getNombre();
					$valor_att = $valor->getValor();
					if ($valor_att == 'unknow' || $valor_att == 'unknwon') {
						$valor_att = 'Sin Definir';
					}
					$attrf = $attrf.$valor_att.' ('.$nombre_att.')'.'<br>';
				}
			}
			else{
				$attrf = '';
			}

			$tpl->assign("dato_fila1",$nombre_.' '.$attrf);
			$tpl->assign("dato_fila2",$nombres_mp);
			$precio_costo = $lote->getPrecio_base();
			$importe = $lote->getImporte();
			$moneda = $lote->getId_moneda()->getValor();
			$precio_final_aux = ($precio_costo * $moneda) * $importe;
			$precio_final = $precio_final_aux + ($precio_costo * $moneda);
			$total_total = $total_total + $precio_final;
			$tpl->assign("dato_fila3",round($precio_final,2));
			$fecha_hora = $value->getFecha_hora();
			$tpl->assign("dato_fila4",$fecha_hora);


		}

		$tpl->assign("dato_fila4",$id_venta);
		$counter = $counter + 1;
	}

	return $tpl->getOutputContent();

}

public static function reporte_ventas($fecha_desde,$fecha_hasta,$bpdf,$mp,$local){

	$respuesta = reporte::reporte_por_semana($fecha_desde,$fecha_hasta);

	$tpl = new TemplatePower("template/reportes/tabla.html");
	$tpl->prepare();
	
	
	$encabezado_html =  Reportes_Controller::encabezado_reporte('Reporte de Ventas',$fecha_desde,$fecha_hasta,1,$mp,$local);
	$tpl->assign("encabezado",$encabezado_html);
	if ($bpdf) {
		$tpl->newBlock("boton_pdf_ventas");
		$tpl->assign("fecha_desde",$fecha_desde);
		$tpl->assign("fecha_hasta",$fecha_hasta);
		$tpl->assign("medio_pago",$mp);
		$tpl->assign("local",$local);
	}
	

	$opciones = ['N°','Articulo','Medio Pago','SubTotal'];
	foreach ($opciones as $key => $value) {
		$tpl->newBlock("columna_tabla");
		$tpl->assign("nombre_columna",$value);
	}

	$respuesta_final = array();
	$numero = 1;
	$total_total = 0;

	foreach ($respuesta as $key => $value) {
		
		if ($local != 0) {
			$rg_detalle = $value->getRg_detalle();
			$rg_detalle_array = explode(',',$rg_detalle);
			$id_local = $rg_detalle_array[3];
			if (!($local == $id_local)) {
				continue;
			}
		}
		
		$id_venta = $value->getId_venta();
		$medio_pago = $value->getId_gmedio_pago();

		$nombres_mp = '';

		if (is_array($medio_pago)) {
			foreach ($medio_pago as $key3 => $value3) {

				$rg_detalle_mp = $value3->getRg_detalle();
				$rg_detalle_mp_array = explode(',',$rg_detalle_mp);
				$nombre_mp = $rg_detalle_mp_array[0];
				$nombres_mp = $nombres_mp.' '.$nombre_mp;
			}
		}else{
			$rg_detalle_mp = $medio_pago->getRg_detalle();
			$rg_detalle_mp_array = explode(',',$rg_detalle_mp);
			$nombre_mp = $rg_detalle_mp_array[0];
			$nombres_mp =  $nombre_mp;
		}
		
		if ($mp != 0) {
			$mp_generado = art_venta_medio_pago::generar($mp);
			$mp_generado_nombre = $mp_generado->getNombre();

			if (!(strcmp($mp_generado_nombre,$nombres_mp ) == 0)) {
				
				continue;
			}
		}

		$art_unico = art_unico::obtener_por($id_venta);
		if (!$art_unico) {
			continue;
		}
		$art_gunico = $art_unico->getId_gunico();
		$art_lote_local_array = $art_gunico->getId_lote_local();


		foreach ($art_lote_local_array as $key2 => $value2) {
			$tpl->newBlock("filas_tabla");
			$tpl->newBlock("datos_fila_tabla_3");
			$tpl->assign("dato_fila0",$numero);

			//$lote_local = $value2->getId_lote();

			$lote = $value2->getId_lote();

			$art = $lote->getId_art_conjunto()->getId_articulo()->getNombre();
			$marca = $lote->getId_art_conjunto()->getId_marca()->getNombre();
			$tipo = $lote->getId_art_conjunto()->getId_tipo()->getNombre();
			$nombre_ = $art.','.$marca.','.$tipo;
			//$nombre_ = str_replace(' ','',$nombre_);
			//$nombre_ = $marca.','.$tipo;

			if ($lote->getId_gc() != null) {
				$gc = $lote->getId_gc()->getId_categoria();
				$attrf = '';
				foreach ($gc as $clave => $valor) {

					$nombre_att = $valor->getNombre();
					$valor_att = $valor->getValor();
					if ($valor_att == 'unknow' || $valor_att == 'unknwon') {
						$valor_att = 'Sin Definir';
					}
					$attrf = $attrf.$valor_att.' ('.$nombre_att.')'.'<br>';
				}
			}
			else{
				$attrf = '';
			}
			$tpl->assign("dato_fila1",$nombre_.' '.$attrf);
			$tpl->assign("dato_fila2",$nombres_mp);
			$precio_costo = $lote->getPrecio_base();
			$importe = $lote->getImporte();
			$moneda = $lote->getId_moneda()->getValor();
			$precio_final_aux = ($precio_costo * $moneda) * $importe;
			$precio_final = $precio_final_aux + ($precio_costo * $moneda);
			$total_total = $total_total + $precio_final;
			$tpl->assign("dato_fila3",round($precio_final,2));
		}
		$numero = $numero + 1;
	}
	$tpl->newBlock("total_ventas_2");
	
	$tpl->assign("cantidad_columnas",count($opciones));
	$tpl->assign("total_ventas",round($total_total,2));

	return $tpl->getOutputContent();		

}

public static function reporte_global($fecha_mes_anio,$sb = true){

	$fecha_array = explode('-', $fecha_mes_anio);
	$nombre_mes = Reportes_Controller::nombre_mes($fecha_array[0],$fecha_array[1]);

	$fecha_hasta = Reportes_Controller::ultimo_dia_mes($nombre_mes,$fecha_array[0]);
	
	$mes_siguiente = intval($fecha_array[1]) + 1;
	if ($mes_siguiente == 013 || $mes_siguiente == 13) {
		$mes_siguiente = 01;
		$fecha_array[0] = $fecha_array[0] + 1;
	}

	$fecha_hasta_sig = $fecha_array[0].'-'.'0'.$mes_siguiente.'-'.'10';

	$fecha_desde = $fecha_mes_anio.'-'.'01';

	$respuesta = reporte::reporte_por_semana($fecha_desde,$fecha_hasta);
	$tpl = new TemplatePower("template/reportes/tabla.html");
	$tpl->prepare();

	$encabezado_html =  Reportes_Controller::encabezado_reporte('Reporte Global',$fecha_desde,$fecha_hasta_sig);
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


		if (strcmp($nombre_local, "DEPOSITO" ) == 0) {
			continue;
		}


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
		$tpl->newBlock("datos_fila_tabla_3");

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


	$respuesta_gs = reporte::reporte_gs(0,$fecha_desde,$fecha_hasta_sig);
	$total_sl_pagar = 0;
	$total_gs_sl = 0;
	$total_anticipos_emp_sl_final = 0;
	$tpl->newBlock("gastos_tabla_sueldo");
	$tpl->assign("titulo_gasto",strtoupper('Sueldos'));

	foreach ($respuesta_gs as $key => $value) {
		$nombre_gs = $value[0][0];
		$detalles_gs = $value[1];

		if (!strcmp(strtoupper($nombre_gs), "SUELDOS" ) == 0) {

			continue;
		}else{
			
			foreach ($detalles_gs as $key2 => $value2) {
				$tpl->newBlock("filas_tabla_gs_detallado_sl");
				$total_anticipos_emp_sl = 0;
				
				$neto_cobrar = 0;

				
				$tpl->assign("nombre_emp_sl",strtoupper($value2->getnombre()));
				$tpl->assign("basico_emp_sl",strtoupper($value2->getValor()));

				$sub_gs = $value2->getId_gsub_gasto();

				if ($sub_gs != null) {

					$sub_gastos_array = $sub_gs->getId_sub_gasto();
					foreach ($sub_gastos_array as $key5 => $value5) {
						$condicion = $value5->getCondicion();
						if (strcmp(strtoupper($condicion), "-" ) == 0) {
							$total_anticipos_emp_sl = $total_anticipos_emp_sl + $value5->getValor(); 
						}
					}
				}

				$tpl->assign("total_anticipos_emp_sl",$total_anticipos_emp_sl);
				$total_anticipos_emp_sl_final = $total_anticipos_emp_sl_final + $total_anticipos_emp_sl;
 				//Obtener empleado
				$total_gs_sl = $total_gs_sl + $value2->getValor();

				$_us_gmv = us_gmv::obtener_por($value2->getId_gasto_unico());
				 
				$_sueldo = us_sueldos::obtener_por($_us_gmv->getId());

				$aguinaldo = $_sueldo[0]->getAguinaldo();
				$basico = $_sueldo[0]->getBasico();
				if ($aguinaldo) {

					$aguinaldo = floatval($basico) / 2;
					$total_gs_sl = $total_gs_sl + $aguinaldo;
				}else{
					$aguinaldo = 0;
				}
				$tpl->assign("aguinaldo_emp_sl",$aguinaldo);
				$neto_cobrar = (floatval($basico) + floatval($aguinaldo)) - floatval($total_anticipos_emp_sl);
				$total_sl_pagar = $total_sl_pagar + $neto_cobrar;

				$tpl->assign("neto_cobrar_emp_sl",round($neto_cobrar,2));

			}
		}

	}
	$tpl->newBlock("total_gs_sueldo");
	$tpl->assign("total_sueldos",$total_gs_sl);
	$tpl->assign("total_sueldos_anticipos",$total_anticipos_emp_sl_final);
	$tpl->assign("total_sueldos_neto",round($total_sl_pagar),2);




	if ($sb) {

		$tpl->newBlock("boton_pdf_global");
		$tpl->assign("fecha_mes_anio",$fecha_mes_anio);

	}
	return $tpl->getOutputContent();

}

public static function encabezado_reporte($titulo,$fecha_desde,$fecha_hasta,$id_encabezado = 0,$medio_pago = null, $local = null){
	switch ($id_encabezado) {
		case 0:
		
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
		break;

		case 1:

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

		$encabezado->newBlock("mp_local");
		if ($medio_pago == 0) {
			$encabezado->assign("medio_pago",'Todos');
		}else{
			//Obtener mp
			$mp = art_venta_medio_pago::generar($medio_pago);
			$nombre_mp = $mp->getNombre();

			$encabezado->assign("medio_pago",$nombre_mp);
		}
		
		if ($local == 0) {
			$encabezado->assign("local",'Todos');
		}else{
			//Obtener local
			$local = art_local::generar_local_2($medio_pago);
			$nombre_local = $local->getNombre();

			$encabezado->assign("local",$nombre_local);
		}
		
		return $encabezado->getOutputContent();
		break;
		
		default:
			# code...
		break;
	}
	
}

public static function fecha_dma_($fecha){
	$fecha_dma = explode('-', $fecha);
	$fcha__ = $fecha_dma[2].'/'.$fecha_dma[1].'/'.$fecha_dma[0];
	return $fcha__;
}
public static function reporte_global_detallado($fecha_desde,$fecha_hasta,$sb = true){
	$respuesta = reporte::reporte_por_semana($fecha_desde,$fecha_hasta);
	
	$tpl = new TemplatePower("template/reportes/tabla.html");
	$tpl->prepare();

	$encabezado_html =  Reportes_Controller::encabezado_reporte('Reporte Global Detallado',Reportes_Controller::fecha_dma_($fecha_desde),Reportes_Controller::fecha_dma_($fecha_hasta));
	$tpl->assign("encabezado",$encabezado_html);

	if (isset($_SESSION['locales'])) {
		$cantidad_locales = count($_SESSION['locales']);
		$tpl->newBlock("columna_tabla");
		$tpl->assign("nombre_columna",'DIAS');

		foreach ($_SESSION['locales'] as $key2 => $value2) {

			$nombre_local = $value2->getNombre();
			if (strcmp($nombre_local, "DEPOSITO" ) == 0) {
				$cantidad_locales = $cantidad_locales - 1;

				continue;
			}
			$tpl->newBlock("columna_tabla");
			$tpl->assign("nombre_columna",$nombre_local);

		}
    	//$ventas_local_dias = new SplFixedArray($cantidad_locales);
		$ventas_local_dias = array();
		if (condition) {
			# code...
		}

		$ot_cl = ot_cliente::generar($_SESSION["usuario"]->getId_user());
		$nombre_cliente = $ot_cl->getNombre();
		if ($nombre_cliente == 'MOTOMATCH') {
			$dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
		}else{
			$dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
		}
		
		$total_ventas = 0;
		$ventas_nocontado = array();
		foreach ($dias as $key => $value) {

			foreach ($_SESSION['locales'] as $key2 => $value2) {
				$id_local = $value2->getId_local();
				$ventas_del_dia_aux = 0;
				$nombre_local = $value2->getNombre();
				if (strcmp($nombre_local, "DEPOSITO" ) == 0) {
					continue;
				}
				foreach ($respuesta as $key3 => $value3) {
					$rg_detalle = $value3->getRg_detalle();
					$rg_detalle_array = explode(',', $rg_detalle);

					$id_local_resp = $rg_detalle_array[3];

					$fecha_venta = $value3->getFecha_hora();
					$dia_de_venta = Reportes_Controller::obtener_dia($fecha_venta);
					$medio_pago_venta_nombre = $value3->getId_gmedio_pago()->getRg_detalle(); 

					if (((strpos(strtoupper($medio_pago_venta_nombre),'CONTADO')) ||  (strpos(strtoupper($medio_pago_venta_nombre),'EFECTIVO')))) {
						

						if (($id_local_resp == $id_local) && (strcmp(strtoupper($value),strtoupper($dia_de_venta)) == 0 )  )  {

							$total_res = $value3->getTotal();
							$ventas_del_dia_aux = $ventas_del_dia_aux + $total_res;
						}else{

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

		if (($cantidad_locales == 4)) {

			for ($i=0; $i < count($ventas_local_dias); $i+=4) {
				$tpl->newBlock("filas_tabla");
				$tpl->newBlock("datos_fila_tabla_4");
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

		if (($cantidad_locales == 3)) {

			for ($i=0; $i < count($ventas_local_dias); $i+=3) {
				$tpl->newBlock("filas_tabla");
				$tpl->newBlock("datos_fila_tabla_3");
				$tpl->assign("dato_fila0",$dias[$counter]);
				$aux1 = $i + 1;
				$aux2 = $i + 2;
				$tpl->assign("dato_fila1",$ventas_local_dias[$i]);
				$tpl->assign("dato_fila2",$ventas_local_dias[$aux1]);
				$tpl->assign("dato_fila3",$ventas_local_dias[$aux2]);


				$counter = $counter + 1 ;

			}
		}

		if ($cantidad_locales == 1) {

			for ($i=0; $i < count($ventas_local_dias); $i++) { 
				$tpl->newBlock("filas_tabla");
				$tpl->newBlock("datos_fila_tabla_1");
				$tpl->assign("dato_fila0",$dias[$counter]);
				$tpl->assign("dato_fila1",$ventas_local_dias[$i]);
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
				
				$tpl->assign("fecha_gasto",Reportes_Controller::fecha_dma_($fecha_gs));
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
		
		$tpl->newBlock("titulo_otros_medios");

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

					//if (strcmp($nombre_cliente,'MOTOMATCH') == 0 ) {
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
						$tpl->assign("fecha_gasto",Reportes_Controller::fecha_dma_($fecha_venta));
						$tpl->assign("nrocomp",$nrocomp);
						$tpl->assign("monto",$total_res);

						$total_ventas_mp_nocontado = $total_ventas_mp_nocontado + $total_res; 
						$value2->setId_venta('BORRADO');
					}
					//}

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