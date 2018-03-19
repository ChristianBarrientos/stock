<?php
 

require_once '../../include_config.php';
 
//$baseDatos = new mysqli($config["dbhost"],$config["dbuser"],$config["dbpass"],$config["db"]);

class Ajax_lista{

	public $Busqueda;


	public function stock_art(){
		
		$Datos = $this->Busqueda;

		$Respuesta = Articulo_Controller::busqueda_art_id($Datos);
		  
		if (count($Respuesta) == 0 OR $Respuesta == '' OR $Respuesta == null) {

			echo json_encode("<h3>Sin Resultados</h3>");
		}else{
			echo json_encode($Respuesta);
			
		}
	}

	public function act_stock_art(){
		 

		$id_lote_local = $_GET['id_lote_local'];
		$stock = $_GET['stock'];
		$id_lote = $_GET['id_lote'];
		echo "Entra en Act Stock";
		$Respuesta = Articulo_Controller::act_stock_($id_lote_local,$id_lote,$stock);
		 echo "Sale de Act Stock";
		if (count($Respuesta) == 0 OR $Respuesta == '' OR $Respuesta == null) {

			echo json_encode("<h3>Sin Resultados</h3>");
		}else{
			echo json_encode($Respuesta);
			
		}
	}


	public function precio_art(){

		$Datos = $this->Busqueda;

		$Respuesta = Articulo_Controller::busqueda_art_id2($Datos);
		  
		if (count($Respuesta) == 0 OR $Respuesta == '' OR $Respuesta == null) {
			//echo "";
			echo json_encode("<h3>Sin Resultados</h3>");
		}else{
		 	//print_r($Respuesta);//
		 	//echo "<script type='text/javascript'>
			//		console.log();
					//console.log("<?php ")
		 	//</script>";
			echo json_encode($Respuesta);
			
		}
	}

	public function act_precio_art(){
		 

		$id_lote = $_GET['id_lote'];
		$costo = $_GET['costo'];
		$moneda = $_GET['id_moneda'];
		$importe = $_GET['importe'];
		  
		$Respuesta = Articulo_Controller::act_precio_($id_lote,$costo,$importe,$moneda);
		  
		if (count($Respuesta) == 0 OR $Respuesta == '' OR $Respuesta == null OR $Respuesta == false) {

			echo json_encode("<h3>Sin Resultados</h3>");
		}else{
			echo json_encode($Respuesta);
			
		}
	}

	public function comprueba_pass(){
		 

		$pass = $_GET['pass'];
		 
		  
		$Respuesta = Articulo_Controller::comprueba_pass($pass);
		$data['status'] = 'ok';
		if ($Respuesta) {
			$data['result'] = true;
		}else{
			$data['result'] = false;
		}
		
        
		echo json_encode($data);
			
		
	}

	

	
}
if (isset($_GET['opcion'])) {
 	 
	 
	$a = new Ajax_lista();
	//print_r($_POST['BusquedaArt']);
	$a->Busqueda = $_GET['id_lote'];
	switch ($_GET['opcion']) {
		case 1:
			$a->stock_art();
			break;

		case 2:
			$a->precio_art();
			break;

		case 3:
			$a->act_stock_art();
			break;

		case 4:
			$a->act_precio_art();
			break;

		case 5:
			$a->comprueba_pass();
			break;

		default:
			# code...
			break;
	}
} 



?>
