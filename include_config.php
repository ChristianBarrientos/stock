<?php
/*Incluir Configuracions Base de Datos */
include('inc.configuration.php');

/*Controladores */
include('controladores/ingreso_.php');
include('controladores/local_.php');
include('controladores/empleado_.php');
include('controladores/articulo_.php');
include('controladores/proveedor_.php');
include('controladores/venta_.php');
include('controladores/gasto_.php');

/*Modelos*/
include('model/usuario.model.php');
include('model/us_datos.usuario.model.php');
include('model/us_prvd_contacto.prvd.usuario.model.php');
include('model/mp_zona.model.php');
include('model/art_local.model.php');
include('model/us_local.model.php');
include('model/archivos.model.php');
include('model/art_carga.model.php');
include('model/art_categoria.model.php');
include('model/art_grupo_sub_categoria.model.php');
include('model/art_gupo_categoria.model.php');
include('model/art_marca.model.php');
include('model/art_sub_categoria.model.php');
include('model/art_unico.model.php');
include('model/art_venta.model.php');
include('model/art_conjunto.model.php');
include('model/articulo.model.php');
include('model/proveedor.model.php');
include('model/prvd_datos.model.php');
include('model/art_tipo.model.php');
include('model/us_prvd.model.php');
include('model/art_codigo_barra.model.php');
include('model/art_lote.model.php');
include('model/art_lote_local.model.php');
include('model/lote_us.model.php');
include('model/art_fotos.model.php');
include('model/us_prvd_foto.model.php');
include('model/us_acceso.model.php');
include('model/reporte.model.php');
include('model/art_venta_medio_pago.model.php');
include('model/art_venta_medio_promo_dias.model.php');
include('model/art_venta_medio_promo_fechas.model.php');
include('model/art_no_venta.model.php');
include('model/art_venta_cambio.model.php');
include('model/art_venta_medio_descripcion.model.php');
include('model/gs_descripcion.model.php');
include('model/gs_gasto_unico.model.php');
include('model/gs_gastos.model.php');
include('model/gs_grupo.model.php');
include('model/gs_gsub_gasto.model.php');
include('model/gs_subgasto.model.php');
include('model/us_gastos.model.php');
include('model/us_ggs.model.php');
include('model/ot_cliente.model.php'); 
include('model/art_us_codigos.model.php');
include('model/art_venta_medio_tipo.model.php');
include('model/art_venta_des_imp.model.php');
include('model/us_medio_pago.model.php');
include('model/us_art_cat.model.php');
include('model/us_art_gcat.model.php');
include('model/us_sueldos.model.php');
include('model/us_gmv.model.php');
include('model/art_moneda.model.php');
include('model/us_moneda.model.php');


//include('template/venta_/ajax_venta.php');



/*Templates-Vistas*/
include('php_recurso/class.TemplatePower.inc.php');
include('php_recurso/filtrar/class.inputfilter.php');
include('php_recurso/cadenas.php');
include('php_recurso/fpdf/fpdf.php');
//include('php_recurso/cb/barcode.php');


session_start();

global $config;
if ($config["dbEngine"]=="MYSQL"){
	$baseDatos = new mysqli($config["dbhost"],$config["dbuser"],$config["dbpass"],$config["db"]);
	
	
}else{
	echo "Error BD";
}

?>