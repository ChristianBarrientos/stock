<?php
/*Incluir Configuracions Base de Datos */
include('inc.configuration.php');
/*Controladores */
include('controladores/ingreso_.php');
include('controladores/local_.php');
include('controladores/empleado_.php');
include('controladores/articulo_.php');
include('controladores/proveedor_.php');

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
/*Templates-Vistas*/
include('php_recurso/class.TemplatePower.inc.php');
include('php_recurso/filtrar/class.inputfilter.php');
include('php_recurso/cadenas.php');


session_start();

?>