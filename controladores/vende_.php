<?php 
	require_once '../include_config.php';

	$id_local = $_GET['id_local'];
	$total = $_GET['total'];
	$medios_pago = $_GET['medios_pago'];
	$articulos = $_GET['articulos'];
	$cuotas = $_GET['cuotas'];

	if (($id_local == null || $id_local == '' ) || ($total == null || $total == '' ) || ($medios_pago == null || $medios_pago == '' ) || ($articulos == null || $articulos == '' ) || ($cuotas == null || $cuotas == '' )) {
		$data['status'] = 'err';
        $data['result'] = '';
        $Respuesta = $data;
		echo json_encode($Respuesta);
	}else{
		$Respuesta = Articulo_Controller::facturacion_finalizar($total,$medios_pago,$articulos,$cuotas,$id_local);	

		//if (count($Respuesta) == 0) {
		if ($Respuesta['status'] == 'err') {
			//print_r($Respuesta);
			echo "<h2>Sin coincidencia.</h2>";
		}else{
			//print_r($Respuesta);
			//echo $Respuesta;
			/*$json="";

			foreach($datas as $this->rows)
			{
			    $json.=($json==""?"":",")."{nombredelvalor1: '".$datas[Identificador1]."',"; // Identificador1 indice o nombre de columna; esto para texto
			    $json.="nombredelvalor2: ".$datas[Identificador2].","; // Identificador2 indice o nombre de columna; esto para un numero o valor booleano (true o false)
			    // lo demas necesario
			    //$json.="nombredelvalorN: ".$datas[IdentificadorN]."}";
			 }

			$json = "{datos: [".$json."]}";

			echo json_encode($json); // finalmente lo retornas*/
			//print_r(json_encode($Respuesta));
			echo json_encode($Respuesta);	
			//echo ($Respuesta);
		}
	}