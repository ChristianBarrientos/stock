<?php 
	require_once '../../include_config.php';

	$_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user());
	$id_lote = $_GET['lote'];
	if (isset($_SESSION["lotes"])) {
		foreach ($_SESSION['lotes'] as $key => $value) {
			$id_lote_ = $value->getId_lote();
			if ($id_lote_ == $id_lote) {
				$art = $value->getId_art_conjunto()->getId_articulo()->getNombre();            
	            $marca = $value->getId_art_conjunto()->getId_marca()->getNombre();
	            $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
	            $nombre_ = $art.','.$marca.','.$tipo;
	            $nombre_ = str_replace(' ','',$nombre_);
	            
	            $nombre_ = $marca.','.$tipo;

	            $precio_base = $value->getPrecio_base();
	            $importe = $value->getImporte();
	            $Respuesta = [$nombre_,$precio_base,$importe];
	            break;
			}else{
				continue;
			}
			

		}
		
	}else{
		$Respuesta = 'Sin Articulos Cargados';
	}
	
	echo json_encode($Respuesta);
			
	

?>