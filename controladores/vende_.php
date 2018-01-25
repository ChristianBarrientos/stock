<?php
require_once '../include_config.php';
class Vender{
	public $Busqueda;
	public function BusquedaArt(){
		$Datos = $this->Busqueda;
		$Respuesta = Articulo_Controller::cargar_art_venta($Datos);
		if (count($Respuesta) == 0 OR $Respuesta == '' OR $Respuesta == null) {
			echo "<h3>Sin Resultados</h3>";
		}else{
			echo json_encode($Respuesta);		
		}
	}
}
$a = new Vender();
if (isset($_POST['Venta_'])) {
	
	$data = json_decode($_POST['Venta_']);
	$data = var_dump($data);
	
	$devuelve = $data["ventas"];
	//print_r($devuelve);
	//print_r($data);
	//echo $devuelve;

	echo json_encode($data);
	//echo ;
	//echo $var[$i]->algo; 

	//$a->Busqueda = $_POST['Venta_'];
	//$a->BusquedaArt();
}
?>