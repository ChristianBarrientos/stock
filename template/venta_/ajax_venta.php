<?php
 

require_once '../../include_config.php';
 
//$baseDatos = new mysqli($config["dbhost"],$config["dbuser"],$config["dbpass"],$config["db"]);

class Ajax{

	public $Busqueda;


	public function BusquedaArt(){

		$Datos = $this->Busqueda;

		$Respuesta = Articulo_Controller::cargar_art_venta($Datos);
		  
		if (count($Respuesta) == 0) {
			echo "<h2>Sin coincidencia.</h2>";
		}else{
		 
			echo json_encode($Respuesta);
			
		}
	}

	public function BusquedaArt2(){

		$Datos = $this->Busqueda;

		$Respuesta = Articulo_Controller::cargar_art_venta($Datos);
		  
		if (count($Respuesta) == 0) {
			echo "<h2>Sin coincidencia.</h2>";
		}else{
		 
			echo json_encode($Respuesta);
			
		}
	}
}

$a = new Ajax();
//print_r($_POST['BusquedaArt']);
if (isset($_POST['BusquedaArt'])) {
	# code...
	$a->Busqueda = $_POST['BusquedaArt'];
	$a->BusquedaArt();
}

if (isset($_POST['BusquedaArt2'])) {
	# code...
	$a->Busqueda = $_POST['BusquedaArt2'];
	$a->BusquedaArt2();
}


?>