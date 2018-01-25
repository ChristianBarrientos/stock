<?php 
	require_once '../../include_config.php';

	$id_local = $_GET['id_local'];
	$total = $_GET['total'];
	$medios_pago = $_GET['medios_pago'];
	$articulos = $_GET['articulos'];


	$Respuesta = Articulo_Controller::facturacion_finalizar($id_lote,$total,$medios_pago,$articulos);
		  
	if (count($Respuesta) == 0) {
		echo "<h2>Sin coincidencia.</h2>";
	}else{
		//print_r($Respuesta);
		//echo $Respuesta;
		echo json_encode($Respuesta);
			
	}