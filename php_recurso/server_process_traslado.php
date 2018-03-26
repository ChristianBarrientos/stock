<?php
	/*
		* Script:    Tablas de multiples datos del lado del servidor para PHP y MySQL
		* Copyright: 2016 - Marko Robles
		* License:   GPL v2 or BSD (3-point)
	*/
	
	require_once '../include_config.php';
	//include('../model/usuario.model.php');
	//usuario::genera_temp_art();
    //$temp = usuario::obtener_temp_art();

    //print_r($tem);
	/* Nombre de La Tabla */
	if (!isset($_SESSION["usuario"])) {
		exit;
	}
	usuario::genera_temp_art_traslado();
	global $baseDatos;
 
	$sTabla = "temp_art_traslado";

	/* Array que contiene los nombres de las columnas de la tabla*/
	$aColumnas = array( 'id', 'nombre','local','cantidad');
	
	/* columna indexada */
	$sIndexColumn = "id";
	
	// Paginacion
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
	}
	
	
	//Ordenacion
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumnas[ intval( $_GET['iSortCol_'.$i] ) ]."
				".$_GET['sSortDir_'.$i] .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	//Filtracion
	//$sql.=" WHERE id LIKE '".$requestData['search']['value']."%' ";
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		//for ( $i=0 ; $i<count($aColumnas) ; $i++ )
		//{
			$sWhere .= 'id'." LIKE '%".$_GET['sSearch']."%' OR ";
			$sWhere .= 'nombre'." LIKE '%".$_GET['sSearch']."%' OR ";
			$sWhere .= 'local'." LIKE '%".$_GET['sSearch']."%' OR";
		//}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	// Filtrado de columna individual 
	for ( $i=0 ; $i<count($aColumnas) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumnas[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
		}
	}
	
	
	//Obtener datos para mostrar SQL queries
	$sQuery = "
	SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnas))."
	FROM   $sTabla
	$sWhere
	$sOrder
	$sLimit
	";
	$rResult = $baseDatos->query($sQuery);
	//$rResult = $mysqli->query($sQuery);
	
	/* Data set length after filtering */
	$sQuery = "
	SELECT FOUND_ROWS()
	";
	//$rResultFilterTotal = $mysqli->query($sQuery);
	$rResultFilterTotal = $baseDatos->query($sQuery);
	$aResultFilterTotal = $rResultFilterTotal->fetch_array();
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
	SELECT COUNT(".$sIndexColumn.")
	FROM   $sTabla
	";
	 
	$rResultTotal = $baseDatos->query($sQuery);
	//$rResultTotal = $mysqli->query($sQuery);
	$aResultTotal = $rResultTotal->fetch_array();
	$iTotal = $aResultTotal[0];

	//$iTotal = count($rResultTotal);
	
	/*
		* Output
	*/
	$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $iTotal,
	"iTotalDisplayRecords" => $iFilteredTotal,
	"aaData" => array()
	);
	
	$costo = 0;
	$importe = 0;
	$moneda = 0;
	$precio_final = 0;
	$es_Admin = Ingreso_Controller::admin_ok();
	while ( $aRow = $rResult->fetch_array())
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumnas) ; $i++ )
		{
			if ( $aColumnas[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumnas[$i] ]=="0") ? '-' : $aRow[ $aColumnas[$i] ];
			}
			else if ( $aColumnas[$i] != ' ' )
			{
				/* General output */
				/*if ($es_Admin) {
					if ($i == 2) {
						 
						if ($aRow[$aColumnas[$i]] == 'null') {
							$aRow[$aColumnas[$i]] = "<td><span  id='".$aRow['id']."' onclick='cargar_codigo_art(this)' class='glyphicon  glyphicon-barcode para_clic'></span></td>";
						}else{
							
							$aRow[$aColumnas[$i]] = $aRow[$aColumnas[$i]].'&nbsp&nbsp'."<td><span  id='".$aRow['id']."' onclick='cargar_codigo_art(this)' class='glyphicon glyphicon-barcode para_clic'></span></td>";
						}
					}
					
				}*/
					
				$row[] = $aRow[ $aColumnas[$i] ];
			}
		}
		if ($precio_final != 0) {
			$row[] = ceil($precio_final) ;
		}

		if ($es_Admin) {
			$row[] = "<td><span  id='".$aRow['id']."' onclick='cargar_stock_art(this)' class='glyphicon glyphicon glyphicon-arrow-up para_clic'></span></td>";
	
		}

		//$row[] = "<td><a href='#' data-href='eliminar.php?id=".$aRow['id']."' data-toggle='modal' data-target='#confirm-delete'><span ></span></a></td>";
		
		$output['aaData'][] = $row;
	}
	 
	echo json_encode($output);

	 
?>