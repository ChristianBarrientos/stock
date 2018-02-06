<?php 
	require_once '../../include_config.php';

	//$id_lote = $_GET['lote'];

	//$Respuesta = Articulo_Controller::facturacion_input($id_lote);
	$_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user());
	if (isset($_SESSION["lotes"])) {
		foreach ($_SESSION['lotes'] as $key => $value) {
			$art = $value->getId_art_conjunto()->getId_articulo()->getNombre();            
            $marca = $value->getId_art_conjunto()->getId_marca()->getNombre();
            $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
            $nombre_ = $art.','.$marca.','.$tipo;
            $nombre_ = str_replace(' ','',$nombre_);
            
            $nombre_ = $marca.','.$tipo;

		}
		$Respuesta = $_SESSION['lotes'];
	}else{
		$Respuesta = 'Sin Articulos Cargados';
	}
	
	//if (count($Respuesta) == 0) {
	//	echo "<h2>Sin coincidencia.</h2>";
	//}else{
		//print_r($Respuesta);
		//echo $Respuesta;
		echo json_encode($Respuesta);
			
	//}

?>