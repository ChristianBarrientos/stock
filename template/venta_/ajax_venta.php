<?php
 

require_once '../../include_config.php';
 
//$baseDatos = new mysqli($config["dbhost"],$config["dbuser"],$config["dbpass"],$config["db"]);

class Ajax{

	public $Busqueda;


	public function BusquedaArt(){

		$Datos = $this->Busqueda;

		$Respuesta = Articulo_Controller::cargar_art_venta($Datos);
		  
		if (count($Respuesta) == 0 OR $Respuesta == '' OR $Respuesta == null) {
			echo "<h3>Sin Resultados</h3>";
		}else{
		 	//print_r($Respuesta);//
		 	//echo "<script type='text/javascript'>
			//		console.log();
					//console.log("<?php ")
		 	//</script>";
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




?>