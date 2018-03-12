<?php
	
	//$data = new Spreadsheet_Excel_Reader();
	//$data->setOutputEncoding('CP1251');
	class Archivos_Controller{

		public static function importar(){

		//usuario::obtener_locales($_SESSION['usuario']);

		$path=  archivo::cargar_datos ($_FILES["archivo_xls"]["name"], 
               $_FILES["archivo_xls"]["size"],
               $_FILES["archivo_xls"]["type"],
               $_FILES["archivo_xls"]["tmp_name"], 
               'articulos','archivos/');
		$opcion = $_POST['opcion_datos_imp'];
		if ($opcion == 1) {
			# code...

		$data = new Spreadsheet_Excel_Reader($path,true,"UTF-16");
		$data->setOutputEncoding('UTF-16');
		$data->read($path);
		$filas = $data->sheets[0]['numRows']; 
		$cantidad_filas = $filas - 1;
		$lista_art_locales = array();
		$lista_art = array();	 
		for ($i=2; $i <= $cantidad_filas; $i++) { 

			$codigo = $data->sheets[0]['cells'][$i][1];
			$nombre = utf8_encode($data->sheets[0]['cells'][$i][2]);
			$costo =  $data->sheets[0]['cells'][$i][3];
			$prvd = utf8_encode($data->sheets[0]['cells'][$i][4]);
			$stock = $data->sheets[0]['cells'][$i][5];
			$moneda = $data->sheets[0]['cells'][$i][6];
			$importe = $data->sheets[0]['cells'][$i][7];
			
				 
			if ($nombre == null) {

				continue;
			}
			if ($prvd == null) {
				 
				$id_prvd_alta = 10;
			}else{

				$id_prvd_alta = 10;
				/*foreach ($_SESSION["proveedores"] as $key => $value) {
					$nombre_prv = $value->getid_datos_prvd()->getNombre();
					$id_prvd =  $value->getId_proveedor();
					
					$pos = strpos(strtoupper($prvd),strtoupper($nombre_prv));
					if ($pos === false OR (strcmp($prvd, $nombre_prv) != 0)) {
					    //No existe
					    $id_prvd_alta = 10;
					} else {
					 	//Si existe
					 	$id_prvd_alta = $id_prvd;
					}
				}*/
			}
			 
			if (floatval($moneda) > 5) {
				 
				$id_moneda = 1;
			}else{
				 
				$id_moneda = 2;
			}
			//Deposito
			$lista_art_locales[]=[1,$stock];
			//Motomatch Central
			$lista_art_locales[]=[2,0];
			//Motomatch III
			$lista_art_locales[]=[3, 0];
			//Motomatch IV
			$lista_art_locales[]=[4,0];

			$lista[] = [$nombre,$id_prvd_alta,$costo,$importe,$codigo,$id_moneda,$stock];
			//$okok = Articulo_Controller::alta_articulo(true,57,33,$nombre,null,$id_prvd_alta,$costo,$importe,4,$codigo,$id_moneda,$stock,$lista_art_locales);
			 
		}

		$okok = Articulo_Controller::alta_masica_desde_archivo_art($lista);

		 
		if ($okok && (!is_string($okok))) {

			$tpl = new TemplatePower("template/exito.html");
	        $tpl->prepare();
	        $tpl->newBlock("articulos_carga");
	        $tpl->assign("cantidad_art",$cantidad_filas);
	    }else{
	    	$tpl = new TemplatePower("template/error.html");
	        $tpl->prepare();
	        print_r($okok);
	    }
        return $tpl->getOutputContent(); 
        }else{
			
		}
	}
}
	
	
	
?>