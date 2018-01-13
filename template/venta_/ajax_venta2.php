<?php 
	require_once '../../include_config.php';

	$id_lote = $_GET['lote'];
	$Respuesta = Articulo_Controller::facturacion_input($id_lote);
		  
	if (count($Respuesta) == 0) {
		echo "<h2>Sin coincidencia.</h2>";
	}else{
		 
		echo json_encode($Respuesta);
			
	}