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
		 
		$Respuesta = Articulo_Controller::act_stock_($id_lote_local,$id_lote,$stock);
		  
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
		echo json_encode($Respuesta);
	
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

	public function cargar_art(){
		 
		if ($_GET['cantidad'] == 'null' OR $_GET['id_local'] == 'null' OR $_GET['cantidad'] <= 0) {
			$data['status'] = 'err2';
            $data['result'] = "Sin cantidad o Sin Local Introducido";  
			echo json_encode($data);
		}else{
			$id_lote = $this->Busqueda;
	        $cantidad = $_GET['cantidad'];
	        $tipo_mv = $_GET['tipo_mv'];
	        $detalle = $_GET['detalle'];
	        $id_local = $_GET['id_local'];
	        $id_lote_local = art_lote_local::obtener_lote_local_oper($id_lote,$id_local);

			$Respuesta = Articulo_Controller::alta_movimiento($id_lote,$id_local,$id_lote_local,$cantidad,$tipo_mv,$detalle);
			echo json_encode($Respuesta);
		}
	}

	public function codigobarras_art(){
		 
		if ($_GET['codigobarras'] == 'null' OR $_GET['id_lote'] == 'null') {
			$data['status'] = 'err2';
            $data['result'] = "Sin cantidad o Sin Local Introducido";  
			echo json_encode($data);
		}else{
			$id_lote = $this->Busqueda;
	        $codigobarras = $_GET['codigobarras'];
	        $tipo_mv = $_GET['tipo_mv'];
	         
	        $Respuesta = art_lote::existe_cb($codigobarras);
	        if ($Respuesta['status'] == 'ok') {
	        	$Respuesta = art_lote::update($id_lote,'codigo_barras',$codigobarras);
	        	if ($Respuesta) {
	        		$data['status'] = 'ok';
                	$data['result'] = "Existo al cargar Codigo de Barras";  
	        	}else{
	        		$data['status'] = 'err';
                	$data['result'] = "No se pudo cargar el Codigo de Barras";  
	        	}
	        	$Respuesta = $data;
	        }

	        echo json_encode($Respuesta);
			
		}
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

		case 6:
			$a->cargar_art();
			break;
		case 7:
			$a->codigobarras_art();
			break;

		default:
			# code...
			break;
	}
} 



?>
