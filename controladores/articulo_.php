<?php
class Articulo_Controller{

	public static function mostrar(){
     
        
        $tpl = new TemplatePower("template/seccion_admin_articulos.html");
        $tpl->prepare();
        if (Ingreso_Controller::admin_ok()) {
            
            $_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user());
            if (isset($_SESSION["lotes"])) {

//$_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user())

                
                $tpl->newBlock("con_articulos_lista");
                $tpl->newBlock("con_articulos_lista_cabeza");
                
                $tpl->newBlock("con_articulos_actualiza_lote_masivo");
                $tpl->newBlock("buscador_visible");
                $cantidad = 0;
                
                
                foreach ($_SESSION['lotes'] as $key => $value) {
                    $vueltas = 0;
                    $cantidad = $cantidad + 1;
                    

                    $art = $value->getId_art_conjunto()->getId_articulo()->getNombre();

                    
                    $marca = $value->getId_art_conjunto()->getId_marca()->getNombre();
                    $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
                    $nombre_ = $art.','.$marca.','.$tipo;
                    $nombre_ = str_replace(' ','',$nombre_);

                    $nombre_ = $marca.','.$tipo;
                    if (True) {
                        $tpl->newBlock("modal_galery_fotos");
                        
                        $tpl->assign("id_lote",'lode_id_'.$value->getId_lote());
                        
                        $fotos_array = $value->getId_art_fotos();
                        
                        foreach ($fotos_array as $fot => $foto) {
                            
                            $tpl->newBlock("modal_galery_fotos_path");
                            
                            $tpl->assign("path_foto",$foto->getPath_foto());
                            
                        }
                                    //Fin del modal galery

                        
                        $tpl->newBlock("con_articulos_lista_cuerpo");

                        

                        $tpl->assign("numero",$cantidad);
                        $tpl->assign("nombre",$nombre_);
                        $tpl->assign("prvd",$value->getId_proveedor());
                        $nom_local__ = array();
                        $id_local_ventas_art_ = array();
                        $cantidad_parcial_local__ = array();
                        $id_lote_local_venta__ = array();
                        $art_lote_local_actual_stock = array();
                                    //$locales_todos = usuario::obtener_locales($_SESSION['usuario']->getId_user());
                        

                        foreach ($_SESSION["lote_local"] as $key2 => $value2) {

                            foreach ($value2 as $key3 => $value3) {
                             
                                if ($value3->getId_lote()->getId_lote() == $value->getId_lote()) {
                                 
                                   
                                    if ($value3->getCantidad_parcial() > 0) {
                                        $art_lote_local_actual_stock = $value2;
                                        $id_lote_local_venta__[] = $value3->getId_lote_local();
                                        $id_local_ventas_art_[] = $value3->getId_local()->getId_local();
                                        $nom_local__[] = $value3->getId_local()->getNombre();

                                        $cantidad_parcial_local__ [] =  $value3->getCantidad_parcial();
                                    }
                                    
                                }
                            }
                        }

                        $cantodad_final_lote_local = $value->getCantidad().'(Total)';
                        $cantodad_final_lote_local .= '<br>';
                        $contadori = 0;
                        
                        foreach ($nom_local__ as $key4 => $value4) {
                            $cantodad_final_lote_local .= $cantidad_parcial_local__[$contadori].'('.$value4.')';
                            $cantodad_final_lote_local .= '<br>';
                            $contadori = $contadori + 1;
                            
                        }
                        
                        $tpl->assign("cantidad_total",$cantodad_final_lote_local);


                        $precio_base = $value->getPrecio_base();
                        $ganancia = $value->getImporte();

                                    //$porcentaje_ganancia = (int)'0'.'.'.$ganancia;
                        $moneda = $value->getId_moneda();
                        if ($moneda != null) {
                            $valor_moneda = $moneda->getValor();
                            $nombre_moneda = $moneda->getNombre();
                            
                            $prc_final = floatval($ganancia) * floatval($precio_base);
                            $precio_fff = floatval($valor_moneda) * floatval($prc_final);
                            $precio_final = round($precio_fff,2);

                        }else{
                            $prc_final = ($ganancia * (int)$precio_base)/100 ;
                            $precio_fff = (int)$precio_base + (int)$prc_final;
                            $precio_final = round($precio_fff,2);
                        }

                        
                                    //Obtener Proveedor
                        if ($value->getId_proveedor() != 'null') {
                                        # code...
                            $prvd = $value->getId_proveedor();
                            $prvd_nombre = $prvd->getid_datos_prvd()->getNombre();
                        }
                        else{
                            $prvd = null;
                        }
                        
                        
                        if ($prvd != null && $prvd != 0) {
                           
                            $tpl->assign("art_prvd",$prvd_nombre);
                            
                        }
                        else{
                           
                            $tpl->assign("art_prvd",'Sin Definir');
                        }

                        if ($precio_base != null) {
                            if ($moneda) {
                                $tpl->assign("precio_base",'$'.$precio_base.'('.$nombre_moneda.')');
                            }else{
                                $tpl->assign("precio_base",'$'.$precio_base);
                            }
                            
                        }
                        else{
                            $tpl->assign("precio_base",'Sin Definir');
                        }

                        if ($medida != null) {
                            $tpl->assign("art_medida",$medida);
                        }
                        else{
                            $tpl->assign("art_medida",'Sin Definir');
                        }

                        if ($precio_tarjeta != null) {
                            $tpl->assign("precio_tarjeta",'$'.$precio_tarjeta.'('.$por_ciento_t.'%)');
                        }
                        else{
                            $tpl->assign("precio_tarjeta",'Sin Definir');
                        }

                        if ($credito_personal != null) {
                            $tpl->assign("credito_personal",'$'.$credito_personal.'('.$por_ciento_p.'%)');
                        }
                        else{
                            $tpl->assign("credito_personal",'Sin Definir');
                        }

                        if ($art_color != null) {
                            $tpl->assign("art_color",$art_color);
                        }
                        else{
                            $tpl->assign("art_color",'Sin Definir');
                        }

                        if ($ganancia != null) {
                            $tpl->assign("ganancia",$ganancia.'%');
                        }
                        else{
                            $tpl->assign("ganancia",'0%');
                        }

                        if ($precio_final != null) {
                            $tpl->assign("precio_final",'$'.$precio_final);
                            
                        }
                        else{
                            $tpl->assign("precio_final",'Sin Definir');
                        }

                        if ($value->getId_gc() != null) {
                                        # code...
                            $gc = $value->getId_gc()->getId_categoria();
                            
                            $attrf = '';
                            foreach ($gc as $clave => $valor) {

                                $nombre_att = $valor->getNombre();
                                $valor_att = $valor->getValor();
                                if ($valor_att == 'unknow' || $valor_att == 'unknwon') {
                                    $valor_att = 'Sin Definir';
                                }

                                $attrf = $attrf.$valor_att.' ('.$nombre_att.')'.'<br>';
                            }

                            $tpl->assign("attr",$attrf);
                            
                        }
                        else{
                            $tpl->assign("attr",'Sin Atributos.');
                        }

                                    
                        
                        if ($value->getId_cb() != null) {
                                        
                            $codigo_barras = $value->getId_cb();
                        }
                        
                        if ($codigo_barras != null) {
                            $tpl->assign("codigo_barras",$codigo_barras);
                            
                        }
                        else{
                            $tpl->assign("codigo_barras",'Sin Definir');
                        }
                        $tpl->assign("id_lote",'lode_id_'.$value->getId_lote());

                                    //Modale ventana Venta
                        if ($cantodad_final_lote_local == 0) {
                            $tpl->assign("disabled_ok",'disabled');
                        }

                        $tpl->assign("selecionar_local_venta",'art_vender_selec_local'.$value->getId_lote());

                        $tpl->newBlock("modal_venta_art");
                        $tpl->assign("selecionar_local_venta",'art_vender_selec_local'.$value->getId_lote());
                                    ///Fin Modal Ventana Venta
                        $contadori = 0;
                        
                        foreach ($nom_local__ as $key5 => $value5) {
                            
                                        //Modal venta
                            $tpl->gotoBlock("_ROOT");
                            
                            $tpl->newBlock("locales_seleccion_venta_art");
                                        //$tpl->assign("id_localfor",$id_local_ventas_art_[$contadori]);
                            $tpl->assign("id_lote_local",$id_lote_local_venta__[$contadori]);
                                        //$tpl->assign("id_localid",$id_local_ventas_art_[$contadori]);
                            $tpl->assign("nombre_local",$value5);
                            $contadori = $contadori + 1;
                            
                            
                                        //Fin Modal Venta
                        }


                                    //Actualizar stock Modal
                                    //Todos los locales locales_todos
                        
                                    //$tpl->assign("id_art_lote_local_stock_actual",);
                                    //Locales en el que tiene el articulo nom_local__
                        
                                    /* ($art_lote_local_actual_stock as $key10 => $value10) {
                                      
                                    }*/
                                    $tpl->newBlock("actualiza_stock_boton");
                                    $tpl->assign("id_art_lote",$value->getId_lote());
                                    $tpl->newBlock("modal_actualiza_stock");
                                    $tpl->assign("id_art_lote",$value->getId_lote());
                                    $tpl->assign("id_art_lote_stock_actual",$value->getId_lote());
                                    $contadori = 0;

                                    
                                    $tpl->newBlock("actualiza_atrs_");
                                    $tpl->assign("id_lote",$value->getId_lote());
                                    
                                    //Mostrar con local
                                    
                                    foreach ($nom_local__ as $key6 => $value6) {
                                        
                                        //Modal venta
                                        $tpl->newBlock("actualiza_sin_stock_locales_cant");
                                        $tpl->assign("nombre_local",$value6);
                                        
                                        $tpl->assign("id_local",$id_local_ventas_art_[$contadori]);
                                        $tpl->assign("cantidad_local",$cantidad_parcial_local__[$contadori]);
                                        

                                        //$tpl->newBlock("actualiza_stock_fecha_art");
                                        //$tpl->assign("nombre_local_art_2",$id_lote_local_venta__[$contadori]);
                                        //$tpl->assign("nombre_local_art",$id_lote_local_venta__[$contadori]);
                                        $contadori = $contadori + 1;
                                        
                                        
                                        //Fin Modal Venta
                                    }

                                    $tpl->newBlock("ver_cb");
                                    $tpl->assign("id_lote",$value->getId_lote());

                                    $tpl->gotoBlock("_ROOT");
                                    
                                    $contadori = 0;
                                    $actualiza_stock_bandera = 0;
                                    
                                    $actualiza_stock_locales_sinart = array();

                                    foreach ($_SESSION["locales"] as $key7 => $value7) {
                                        
                                        //Modal venta
                                        $nombre_local_sin = $value7->getNombre();
                                        for ($i=0; $i < count($nom_local__) ; $i++) { 
                                            $nombre_local_con = $nom_local__[$i];
                                            if (!(strcmp($nombre_local_con, $nombre_local_sin ) == 0)) {
                                              
                                            }else{
                                                $actualiza_stock_bandera = $actualiza_stock_bandera + 1;
                                            }
                                            
                                        }
                                        
                                        if ($actualiza_stock_bandera == 0) {

                                            $actualiza_stock_locales_sinart = $value7;
                                            $tpl->newBlock("actualiza_sin_stock_locales_cant");
                                            $tpl->assign("nombre_local",$value7->getNombre());
                                            $tpl->assign("id_local",$value7->getId_local());
                                            $tpl->assign("cantidad_local",0);
                                            $contadori = $contadori + 1;
                                            $actualiza_stock_bandera = 0;
                                            # code...
                                        }else{
                                            $actualiza_stock_bandera = 0;
                                        }
                                    }
                                    $actualiza_stock_bandera = 0;
                                    $contadori = 0;
                                    //Actualizar Precio Modal

                                    $tpl->gotoBlock("_ROOT");
                                    $tpl->newBlock("modal_codigo_ver");
                                    $codigo_barras = $value->getId_cb();

                                    $tpl->assign("id_lote",$value->getId_lote());
                                    $tpl->assign("art_codigo",$codigo_barras);
                                    
                                    $tpl->newBlock("modal_actualizar_atrs");
                                    $tpl->assign("id_lote",$value->getId_lote());
                                    $tpl->assign("id_art_lote",$value->getId_lote());
                                    if ($value->getId_gc() != null) {
                                        # code...
                                        $gc = $value->getId_gc()->getId_categoria();
                                        
                                        $attrf = '';
                                        foreach ($gc as $clave => $valor) {

                                            $nombre_att = $valor->getNombre();
                                            $valor_att = $valor->getValor();
                                            $id_cg = $valor->getId_categoria();

                                            $tpl->newBlock("seccion_atr_act");
                                            $tpl->assign("nombre_atr",$nombre_att);
                                            if ($valor_att == 'unknow' || $valor_att == 'unknwon') {
                                                $tpl->assign("valor_atr",'Sin Definir');
                                            }else{
                                                $tpl->assign("valor_atr",$valor_att);
                                            }

                                            $tpl->assign("id_atr",$id_cg);
                                            
                                        }

                                        //$tpl->assign("attr",$attrf);
                                        
                                    }
                                    else{
                                        $tpl->newBlock("sin_atr");
                                        //$tpl->assign("sin_atr",'Sin Atributos.');
                                    }


                                    $tpl->newBlock("modal_actualizar_precio_masivo");
                                    $tpl->newBlock("modal_actualizar_precio");
                                    $tpl->assign("id_art_lote",$value->getId_lote());
                                    $tpl->newBlock("actualiza_precio_boton");
                                    $tpl->assign("id_art_lote",$value->getId_lote());
                                    $tpl->newBlock("actualiza_precio_base");
                                    $tpl->assign("precio_base_",$precio_base);

                                    $tpl->newBlock("actualiza_importe_");
                                    if ($ganancia == 0) {
                                        # code...
                                        $tpl->assign("importe_",'0');
                                    }else{
                                        $tpl->assign("importe_",$ganancia);
                                    }
                                    


                                    $tpl->newBlock("actualiza_precio_tarjeta");
                                    $tpl->assign("precio_tarjeta_",$por_ciento_t);

                                    $tpl->newBlock("actualiza_precio_credito");
                                    $tpl->assign("precio_credito_",$por_ciento_p);
                                }
                            }          
                        }
                        else{

                         $tpl->newBlock("sin_articulos");
                     }
                 }
                 else{
                    return Ingreso_Controller::salir();
                }

                return $tpl->getOutputContent();

            }

            public static function pre_mostrar_operador(){

                $id_empleado_venta_local_art = $_GET['id_local'];
                $_SESSION['usuario']->setLocal_Actual($id_empleado_venta_local_art);
                $id_usuario_oo = $_SESSION['usuario']->getId_user();

                $hoy = getdate();
                $ahora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
            //Tomar asistencia
                if ($_SESSION["permiso"] == 'OPER') {
                  
                    $id_acceso = us_acceso::insert_us_acceso($id_empleado_venta_local_art,$id_usuario_oo,$ahora);
                    $_SESSION['usuario']->setId_Acceso($id_acceso);

                    
                }else{
                    return Ingreso_Controller::salir();
                }
            //return Articulo_Controller::mostrar_operador();
            }

            public static function mostrar_operador($id_lote_lote = null){
                
                if ($_SESSION['usuario']->getId_Acceso() == null) {
                   
                    Articulo_Controller::pre_mostrar_operador();
                }
                $id_empleado_venta_local_art = $_GET['id_local'];

                if ($id_empleado_venta_local_art == null ) {
                # code...
                    
                    $id_empleado_venta_local_art = $id_lote_lote;
                }
                if (isset($id_empleado_venta_local_art) && $id_empleado_venta_local_art == null) {
                    
                    Ingreso_Controller::salir();
                    
                }
                

                $id_usuario_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
                
                $tpl = new TemplatePower("template/seccion_operador_articulos.html");
                $tpl->prepare();
                if (isset($_SESSION['usuario'])) {
                   
                         //$_SESSION['usuario']->obtener_lote_us($id_usuario_jefe)
                    
                    if ($_SESSION['usuario']->obtener_lote_us($id_usuario_jefe)) {
                        
                        $tpl->newBlock("con_articulos_lista");
                        $tpl->newBlock("con_articulos_lista_cabeza");
                        
                        $tpl->newBlock("buscador_visible");
                        $cantidad = 0;
                        
                        foreach ($_SESSION['lotes'] as $key => $value) {
                            $vueltas = 0;
                            $cantidad = $cantidad + 1;
                            $art = $value->getId_art_conjunto()->getId_articulo()->getNombre();
                            $marca = $value->getId_art_conjunto()->getId_marca()->getNombre();
                            $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
                            $nombre_ = $art.', '.$marca.', '.$tipo;
                            $nombre_ = $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
                            if (True) {
                                $tpl->newBlock("modal_galery_fotos");
                                $tpl->assign("id_lote",'lode_id_'.$value->getId_lote());
                                $fotos_array = $value->getId_art_fotos();
                                
                                foreach ($fotos_array as $fot => $foto) {
                                    $tpl->newBlock("modal_galery_fotos_path");
                                    $tpl->assign("path_foto",$foto->getPath_foto());
                                }
                                $tpl->newBlock("con_articulos_lista_cuerpo");
                                $tpl->assign("numero",$cantidad);
                                $tpl->assign("nombre",$nombre_);
                                $tpl->assign("prvd",$value->getId_proveedor());
                                $nom_local__ = array();
                                $id_local_ventas_art_ = array();
                                $cantidad_parcial_local__ = array();
                                $id_lote_local_venta__ = array();
                                $id_lote_local_venta_art_en_cero = array();
                                foreach ($_SESSION["lote_local"] as $key2 => $value2) {
                                    $cantidad_articulos_total_por_lot;
                                    foreach ($value2 as $key3 => $value3) {
                                     
                                        if ($value3->getId_lote()->getId_lote() == $value->getId_lote()) {
                                            
                                            if ($value3->getCantidad_parcial() > 0) {
                                                $id_lote_local_venta__[] = $value3->getId_lote_local();
                                                $id_local_ventas_art_[] = $value3->getId_local()->getId_local();
                                                $nom_local__[] = $value3->getId_local()->getNombre();
                                                $cantidad_parcial_local__ [] =  $value3->getCantidad_parcial();
                                            }
                                            else{
                                                $id_lote_local_venta_art_en_cero[]=$value3->getId_local();
                                            }
                                        }
                                    }
                                }

                                $cantodad_final_lote_local = $value->getCantidad().'  (Total)';
                                $cantodad_final_lote_local .= '<br>';
                                $contadori = 0;
                                
                                foreach ($nom_local__ as $key4 => $value4) {
                                    $cantodad_final_lote_local .= $cantidad_parcial_local__[$contadori].'  ('.$value4.')';
                                    $cantodad_final_lote_local .= '<br>';
                                    $contadori = $contadori + 1;
                                    
                                }
                                
                                $tpl->assign("cantidad_total",$cantodad_final_lote_local);

                                $gc = $value->getId_us_gcat()->getId_categoria();
                                foreach ($gc as $clave => $valor) {
                                    if (strcmp($valor->getNombre(), "Medida" ) == 0 ) {
                                        $medida = $valor->getValor();
                                    }

                                    if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                                        $precio_base = $valor->getValor();
                                        
                                    }

                                    if (strcmp($valor->getNombre(), "Tarjeta" ) == 0 ) {
                                        $por_ciento_t =  $valor->getValor();
                                        if ($por_ciento_t == 100) {
                                                # code...
                                            $por_ciento_t_2 = 1;
                                        }else{
                                            if ($por_ciento_t < 10) {
                                                    # code...
                                                $por_ciento_t_2 = '0.0'.$por_ciento_t;
                                            }else{
                                                $por_ciento_t_2 = '0.'.$por_ciento_t;
                                            }
                                            
                                        }
                                        
                                        
                                        $precio_tarjeta = $precio_base + ($precio_base * $por_ciento_t_2);
                                        

                                        
                                    }

                                    if (strcmp($valor->getNombre(), "Ganancia" ) == 0 ) {
                                        
                                        $ganancia = $valor->getValor();
                                        
                                        $precio_final = $precio_base + ($precio_base * '0'.'.'.$ganancia);

                                        
                                    }

                                    if (strcmp($valor->getNombre(), "CreditoP" ) == 0 ) {
                                        $por_ciento_p = $valor->getValor();
                                        if ($por_ciento_p == 100) {
                                                # code...
                                            $por_ciento_p_2 = 1;
                                        }else{
                                            if ($por_ciento_p < 10) {
                                                    # code...
                                                $por_ciento_p_2 = '0.0'.$por_ciento_p;
                                            }else{
                                                $por_ciento_p_2 = '0.'.$por_ciento_p;
                                            }
                                            
                                        }
                                        
                                        $credito_personal = $precio_base + ($precio_base * $por_ciento_p_2);
                                        
                                    }

                                    if (strcmp($valor->getNombre(), "Color" ) == 0 ) {
                                        $art_color = $valor->getValor();
                                        
                                    }
                                } 

                                     //Obtener Proveedor
                                if ($value->getId_proveedor() != 'null') {
                                        # code...
                                    $prvd = $value->getId_proveedor();
                                    $prvd_nombre = $prvd->getid_datos_prvd()->getNombre();
                                } 

                                    //Obtener moneda
                                $moneda = $value->getId_moneda();
                                if ($id_moneda != null) {
                                    $moneda = art_monoeda::generar($moneda);
                                    $nombre_moneda = $moneda->getNombre();
                                    $valor_moneda = $moneda->getValor();
                                    
                                } 
                                
                                if ($prvd != null) {
                                   
                                    $tpl->assign("art_prvd",$prvd_nombre);
                                    
                                }
                                else{
                                   
                                    $tpl->assign("art_prvd",'Sin Definir');
                                }

                                if ($precio_base != null) {
                                    $tpl->assign("precio_base",'$'.$precio_base.'('.$nombre_moneda.')');
                                }
                                else{
                                    $tpl->assign("precio_base",'Sin Definir');
                                }

                                if ($medida != null) {
                                    $tpl->assign("art_medida",$medida);
                                }
                                else{
                                    $tpl->assign("art_medida",'Sin Definir');
                                }

                                if ($precio_tarjeta != null) {
                                    $tpl->assign("precio_tarjeta",'$'.$precio_tarjeta.'('.$por_ciento_t.'%)');
                                }
                                else{
                                    $tpl->assign("precio_tarjeta",'Sin Definir');
                                }

                                if ($credito_personal != null) {
                                    $tpl->assign("credito_personal",'$'.$credito_personal.'('.$por_ciento_p.'%)');
                                }
                                else{
                                    $tpl->assign("credito_personal",'Sin Definir');
                                }

                                if ($art_color != null) {
                                    $tpl->assign("art_color",$art_color);
                                }
                                else{
                                    $tpl->assign("art_color",'Sin Definir');
                                }

                                if ($ganancia != null) {
                                    $tpl->assign("ganancia",$ganancia.'%');
                                }
                                else{
                                    $tpl->assign("ganancia",'0%');
                                }

                                if ($precio_final != null) {
                                    $tpl->assign("precio_final",$precio_final);
                                    
                                }
                                else{
                                    $tpl->assign("precio_final",'Sin Definir');
                                }

                                    //Obtener Codigo de barras
                                $codigo_barras = $value->getId_cb()->getcb();
                                if ($codigo_barras != null) {
                                    $tpl->assign("codigo_barras",$codigo_barras);
                                    
                                }
                                else{
                                    $tpl->assign("codigo_barras",'Sin Definir');
                                }

                                $tpl->assign("id_lote",'lode_id_'.$value->getId_lote());
                                
                                   ///Tocar por aca para no mostrar locales que nos sea en el que se encuentra el empelado
                                $no_puede_vender = false;
                                foreach ($id_lote_local_venta_art_en_cero as $key_fin => $value_fin) {
                                    $id_local_art_en0 = $value_fin->getId_local();
                                    if ($id_empleado_venta_local_art == $id_local_art_en0) {
                                        $no_puede_vender = true;
                                    }
                                }
                                    //agrega !
                                if ($no_puede_vender) {
                                   
                                    $tpl->newBlock("boton_sin_con_stock");
                                    $tpl->assign("selecionar_local_venta_sin_stock",$value->getId_lote());

                                    $tpl->newBlock("modal_venta_art");
                                    $tpl->assign("selecionar_local_venta",$value->getId_lote());
                                    $contadori = 0;
                                    foreach ($nom_local__ as $key5 => $value5) {
                                        $tpl->gotoBlock("_ROOT");
                                        $tpl->newBlock("locales_seleccion_venta_art");
                                        $tpl->assign("id_lote_local",$id_lote_local_venta__[$contadori]);
                                        $tpl->assign("nombre_local",$value5);
                                        $contadori = $contadori + 1;
                                        //Fin Modal Venta
                                    }
                                }
                                else{
                                    
                                    $tpl->newBlock("boton_con_stock");
                                    
                                    $id_lote_local_fin = art_lote_local::obtener_lote_local_oper($value->getId_lote(),$id_empleado_venta_local_art);
                                    
                                    $tpl->assign("id_lote_local",$id_lote_local_fin);
                                        //$tpl->assign("selecionar_local_venta_stock",$value->getId_lote());
                                }
                            }
                        }
                    }
                    else{

                     $tpl->newBlock("sin_articulos");
                 }
             }
             else{
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();

        }

        public static function cargar_deposito(){
            if (isset($_SESSION["usuario"])){
                if ($_SESSION["permiso"] == 'ADMIN') {
                    $nombres_si = False;
                    $tpl = new TemplatePower("template/cargar_articulo.html");
                    $tpl->prepare();

                    $tpl->newBlock("modal_codigo_generar");
                    $id_user = $_SESSION["usuario"]->getId_user();
                    $cliente = ot_cliente::obtener($id_user);
                    $nombre_cliente = $cliente->getNombre();
                //generar numero si no existe
                    $no_sumar = true;
                    $numero = art_us_codigos::obtener($id_user);

                    if ($numero == null) {
                    # code...
                        $numero = art_us_codigos::alta($id_user,1);
                        $numero_ = 1;
                        $no_sumar = false;
                    }

                    if ($no_sumar) {
                    # code...

                        $numero_ = $numero->getNumero();
                        $numero_ = $numero_ + 1; 
                    }

                    $codigo_generado = $nombre_cliente.$numero_;
                    $codigo_generado = str_replace(' ','',$codigo_generado);
                    
                    $tpl->assign("art_codigo_", $codigo_generado);
                    $tpl->assign("art_codigo", $codigo_generado);

                    $list_art_nombres = articulo::obtener_articulos();
                    if ($list_art_nombres) {
                       foreach ($list_art_nombres as $key => $value) {
                           
                        $tpl->newBlock("cargar_articulo_nombre");
                        $tpl->assign("valor_id_articulo", $value->getId_articulo());
                        $tpl->assign("nombre_articulo", $value->getNombre());
                    }
                }

                $list_art_marcas = art_marca::obtener_marcas();
                if ($list_art_marcas) {
                   foreach ($list_art_marcas as $key => $value) {
                       
                    $tpl->newBlock("cargar_articulo_marca");
                    $tpl->assign("valor_id_marca", $value->getId_marca());
                    $tpl->assign("marca_articulo", $value->getNombre());
                }
            }

            $list_art_tipo = art_tipo::obtener_tipos();
            if ($list_art_tipo) {

               foreach ($list_art_tipo as $key => $value) {
                
                $tpl->newBlock("cargar_articulo_tipo");
                $tpl->assign("valor_id_tipo", $value->getId());
                $tpl->assign("tipo_articulo", $value->getNombre());
            }
        }
                //Agregar monedas
        $us_monedas = us_moneda::obtener($_SESSION['usuario']->getId_user());

        
        if ($us_monedas) {
            $monedas = $us_monedas->getId_moneda();
            $tpl->newBlock("art_moneda_si");
            $cnt_moneda = count($monedas);
            $moneda_unica = false;
            foreach ($monedas as $key6 => $value6) {
                if ($cnt_moneda == 1) {
                    $moneda_unica = true;
                }
                $tpl->newBlock("cargar_moneda");
                $tpl->assign("valor_id_moneda", $value6->getId());
                $tpl->assign("nombre_moneda", $value6->getNombre().' ('.$value6->getValor().')');
                if ($moneda_unica) {
                    $tpl->assign("selected_moneda", 'selected');
                }else{
                   $tpl->assign("selected_ninguno", 'selected');
               }
           }

       }else{
        $tpl->newBlock("art_moneda_no");
    }
    
    foreach ($_SESSION['locales'] as $key => $value) {
        
        $tpl->newBlock("locales_empleado_alta");
        $cadena = $value->getId_zona();
        $direccion = after_last (',', $cadena);
                        //check_art_locales_
                        //$tpl->assign("id_local_", $value->getId_local());
        $tpl->assign("id_local_", $value->getId_local());
        $tpl->assign("nombre_local", $value->getNombre());
        
        
    }

    foreach ($_SESSION['locales'] as $key => $value) {

        $tpl->newBlock("locales_alta");
        $tpl->assign("id_local_", $value->getId_local());
        $tpl->assign("id_art_local1", $value->getId_local());
        $tpl->assign("id_art_local", $value->getId_local());
        $tpl->assign("nombre_local", $value->getNombre());
        $tpl->assign("nombre_local_art_2", str_replace(' ','',$value->getNombre()));
        $tpl->assign("nombre_local_art", str_replace(' ','',$value->getNombre()));
        
    }
    $us_gct = us_art_gcat::obtener($_SESSION['usuario']->getId_user()); 

    
    if ($us_gct != null) {
                    # code...
        $id_us_gcat = $us_gct->getId();
        $us_art_cat = $us_gct->getId_us_art_cat();
    }
    

    foreach ($us_art_cat as $key6 => $value6) {

        $estado = $value6->getHabilitado();
        if ($estado == true) {
            $gattr = $value6;
            break;
        }
        else{
            $gattr = false;
        }
    }

    if ($gattr) {

        $tpl->newBlock("cargar_articulo_grupo");
        
        
        $nom_grupo = $gattr->getNombre();
        

        $tpl->assign("nom_grupo",$nom_grupo);
        $gcts = $gattr->getId_gc();

        $cts = $gcts->getId_categoria();

        foreach ($cts as $key7 => $value7) {
                        # code...
           
            $tpl->newBlock("gattr_alta_art");
            
            $tpl->assign("nombre_attr", $value7->getNombre());
            $des = $value7->getDescripcion();
            $id = $value7->getId_categoria();
            if ($des) {
                            # code...
                $tpl->assign("descripcion",$des);
            }else{
                $tpl->assign("descripcion",'Ingrese un valor');
            }
            $tpl->assign("id_cat",$id);
            
            
            
        }
        
        $tpl->newBlock("id_us_gcat");
        $tpl->assign("id_us_gcat",$id_us_gcat);
        
    }else{
        $tpl->newBlock("sin_articulo_grupo");
    }
    
    $list_prvd_art = proveedor::obtener_prvd($_SESSION["usuario"]->getId_user());
    
    if ($list_prvd_art) {

       foreach ($list_prvd_art as $key => $value) {
        
        $tpl->newBlock("cargar_articulo_prvd");
        $tpl->assign("valor_id_prvd", $value->getId_proveedor());
        $tpl->assign("prvd_articulo", $value->getid_datos_prvd()->getNombre());
    }
}
if (count($_SESSION['locales']) == 0) {
    $tpl->newBlock("sin_locales_empl");
    $tpl->newBlock("sin_locales_empl_2");
}else{
    $tpl->newBlock("con_locales_empl");
}

}

}
else{
    return Ingreso_Controller::salir();
}

return $tpl->getOutputContent();
}


public static function alta_articulo(){

    $id_us_gcat = $_POST['id_us_gcat'];
    $valor_cat_attrs = $_POST['gattr'];
    $id_moneda = $_POST['art_moneda'];
    if ($id_moneda != 0) {
            # code...
        $moneda = art_moneda::generar($id_moneda);
        if ($moneda) {
            $moneda = $moneda->getId();
        }else{
            return Ingreso_Controller::salir();
        }
    }else{
        $moneda = 'null';
    }

        //Asinar valores a los atributos
    if (isset($id_us_gcat) && $id_us_gcat != null) {
            # code...
            //Asigna valor a art_categoria
            //generar us_gcat
        $us_art_gcat = us_art_gcat::generar($id_us_gcat);
        $id_cat = $us_art_gcat->getId_us_art_cat()->getId_gc()->getId_categoria();

        
            //Valores
        
        $una_vez = true;
        $counter = 0;

        foreach ($id_cat as $key => $value) {
            $id_ct = art_categoria::alta($value->getNombre(),$value->getDescripcion(),$valor_cat_attrs[$counter]);

            if ($una_vez) {  
                $id_gc = art_grupo_categoria::alta_($id_ct);
                
                
                $counter = $counter + 1;
                $una_vez = false;
                continue;
            }

            
            $ok_gct = art_grupo_categoria::alta_($id_ct,$id_gc);
            $counter = $counter + 1;
        }
        
    }else{
     
        $id_gc = 'null';
    }


    $datos_no_recibidos = false;
        //$art_cantidad_total = $_POST['art_cantidad_total'];
    $art_general = ucwords(strtolower($_POST['select_art_general']));
    $art_marca = ucwords(strtolower($_POST['art_marca']));
    $art_tipo = ucwords(strtolower($_POST['art_tipo']));
    

    $art_cb =$_POST['art_codigo_barras'];
    
    
    $art_prvd =$_POST['art_prvd'];

    $art_precio_base = $_POST['art_precio_base'];
    $art_precio_tarjeta = $_POST['art_precio_tarjeta'];
    $art_precio_cp= $_POST['art_precio_credito_argentino'];
    $art_medida = ucwords(strtolower($_POST['art_medida']));
    $art_ganancia = $_POST['art_ganancia'];
    $art_color =$_POST['art_color'];
    $art_proveedor = $_POST['art_prvd'];

    if (  $art_general == null || $art_marca == null || $art_tipo == null
       ||  $art_precio_base == null ) {
        $datos_no_recibidos = true;
        

        return Ingreso_Controller::salir();
        
    }
    
    $total_locales = art_local::obtener_locales_usuario_operador();
    if (!$total_locales) {
        return Ingreso_Controller::salir();
            # code...
    }
    $contador = 1;
    $salto = 0;
    /* inicializamos una variable vacia que contendra los datos */
    $lista_art_locales = array();
    /* Luego para cada campo y valor $_POST realizamos lo siguiente */
    $nook = true;
    $art_cantidad_total = 0;
    foreach ($_POST as $campo => $valor){
            /* en la variable $concatenamos juntamos el campo y su valor 
            ;*/
            for ($i=0; $i <=$total_locales ; $i++) { 
                //Revisar el contador nos va a dar falsos positivos!!!
                //Utilizar exoresie¡
                if ($total_locales[$i]['id_local'] != null) {
                    $id_local_oper = $total_locales[$i]['id_local'];
                    $name_local_cantidad = "art_local_cantidad_".$id_local_oper;
                    $name_local_fecha = "art_carga_local_fecha_".$id_local_oper;
                    
                    
                    //$dias_array = str_replace("7","0",$dias_array);
                    
                    
                    if (strcmp($campo, $name_local_cantidad ) == 0) {

                        //if ($_POST[$name_local_fecha] != null) {
                        $cantidad_stock = $_POST[$name_local_cantidad];
                        $stock_cantidad = explode (",", $cantidad_stock);
                        $final_stock_cant = $stock_cantidad[0];
                        $lista_art_locales[]=["Id" => $id_local_oper,"Cantidad" => $final_stock_cant,"Fecha" => $_POST[$name_local_fecha]];
                        $art_cantidad_total = $art_cantidad_total + $final_stock_cant;
                        
                        //}
                    }
                }
                else{
                    break;
                }

                
            }
            
            
        }
        
        

        //Agregar art_general
        if (is_numeric($art_general)) {
            $id_articulo = $art_general;
        }
        else{
            $id_articulo = articulo::alta_art_general($art_general);
        } 
        if (is_numeric($art_marca)) {
            $id_marca = $art_marca;
        }
        else{
            $id_marca = art_marca::alta_art_marca($art_marca);
        } 
        if (is_numeric($art_tipo)) {
            $id_tipo = $art_tipo;
        }
        else{
            $id_tipo = art_tipo::alta_art_tipo($art_tipo);
        } 

        //cargar en art_conjunto
        $id_conjunto = art_conjunto::alta_art_conjunto($id_articulo,$id_marca,$id_tipo);

        //obtener id de proveedor
        
        if ($art_prvd != null && $art_prvd != ' ' && $art_prvd != 0) {
            $id_proveedor = $art_prvd;
            
        }
        else{
            
            $id_proveedor = 'null';
        }
        //cargar codigo de barras

        $id_user = $_SESSION["usuario"]->getId_user();
        $cliente = ot_cliente::obtener($id_user);
        $nombre_cliente = $cliente->getNombre();



        $pos = strpos($art_cb, $nombre_cliente);

        
        

        if ($pos === false) {
            //No esta
            $codigo_barras = $art_cb;
            if ($art_cb == null) {

                $codigo_barras = 'null';
                
            }
        }else{
            //si esta
            //agregar +1 al numero en art_us_codigos
            $numero = art_us_codigos::obtener($id_user);
            $numero_ = $numero->getNumero();
            $numero_ = $numero_ + 1; 

            $ok_cb = art_us_codigos::update($numero->getId(),"numero",$numero_);
            

            if ($ok_cb) {
                # code...
                $codigo_barras = $nombre_cliente.$numero_;
                
            }else{
                echo "NO GENERADO CODIGO DE BARRAS";
                $codigo_barras = 'null';
            }

        }
        $codigo_barras = str_replace(' ','',$codigo_barras);

        $nombre_art_general_generado = articulo::generar_articulo($id_articulo);
        $nombre_art_marca_generado = art_marca::generar_marca($id_marca);
        $nombre_art_tipo_generado = art_tipo::generar_tipo($id_tipo);
        $hoy = getdate();
        
        $art_nombre = $nombre_art_general_generado->getNombre().'_'.$nombre_art_marca_generado->getNombre().'_'.$nombre_art_tipo_generado->getNombre().'_'.$hoy['year'].'_'.$hoy['mon'].'_'.$hoy['mday'].'_'.$hoy['hours'].'_'.$hoy['minutes'].'_'.$hoy['seconds'];
        //$files = array_filter($_FILES['upload']['name']); 
        $total = count($_FILES['fotos_art']['name']);
        $id_fotos = array();
        // Loop through each file
        $okok_salto = 0;
        //Alta art_fotos
        if ($total > 1) {
            # code...
            
            for($i=0; $i<$total; $i++) {
          //Get the temp file path
              
               
              $path=  archivo::cargar_datos ($_FILES["fotos_art"]["name"][$i], 
               $_FILES["fotos_art"]["size"][$i],
               $_FILES["fotos_art"]["type"][$i],
               $_FILES["fotos_art"]["tmp_name"][$i], 
               $art_nombre.'-'.$i);
              $id_foto = us_prvd_foto::alta_foto($path);
              if ($okok_salto == 0) {
                $id_art_fotos = art_fotos::agregar_foto_a_lote($id_foto);
                //$id_fotos[] = $id_art_fotos;
                $okok_salto = 1;
            }else
            {
                art_fotos::agregar_foto_a_lote_2($id_art_fotos ,$id_foto);
            }
            
            
        }
    }
    else{
        $id_art_fotos = 'null';

    }
        //agregar a Lote
    $id_lote = art_lote::alta_art_lote($id_conjunto, $art_cantidad_total, $codigo_barras,$id_art_fotos,$art_precio_base,$art_ganancia,$id_proveedor,$id_gc,$moneda);
    
    

        //cargar art_carga y art_lote_local
    
    foreach ($lista_art_locales as $key => $value) {
            /*$_fecha = trim($value['Fecha'],'AM');
            $_fecha = trim($_fecha,'PM');
            $_fecha = substr($_fecha, 0, -1);
            $_fecha = $_fecha.':'.'00';
            */
            
            $id_carga = art_carga::alta_art_carga($value['Fecha'], $_SESSION["usuario"]->getId_user());
            
            $id_lote_local = art_lote_local::alta_art_lote_local($id_lote,$value['Id'],$value['Cantidad'],$id_carga);
        }

        //cargar lote_us

        $ok2 = $_SESSION["usuario"]->alta_lote_us($id_lote);
        
        if ($ok2) {
            $tpl = new TemplatePower("template/exito.html");
            $tpl->prepare();
            $tpl->newBlock("alta_art_exito");
        }
        else
        {
         
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();

        }
        
        return $tpl->getOutputContent();
    }


    public static function venta_articulo(){
        if (!(isset($_SESSION["usuario"]))) {
            return Ingreso_Controller::salir();

        }
        $id_lote_local = $_POST['art_local_venta'];

        
        $id_art_lotte_loccal = $_GET['id_art_lote_locas'];
        if ($_SESSION["permiso"] == 'OPER') {
            $lote_local = art_lote_local::generar_lote_local_id_($id_art_lotte_loccal);
        }
        else{
            $lote_local = art_lote_local::generar_lote_local_id_($id_lote_local);
        }
        
        
        $tpl = new TemplatePower("template/venta_art.html");
        $tpl->prepare();
        $art_nombre = $lote_local->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
        $art_marca =  $lote_local->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
        $art_tipo = $lote_local->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
        $tpl->assign("art_nombre",(string)$art_nombre.' ,'.$art_marca.' ,'.$art_tipo );

        $precio_base_venta =  $lote_local->getId_lote()->getPrecio_base();
        $importe = $lote_local->getId_lote()->getImporte();
        $importe_ = (int)(($importe * $precio_base_venta) /100);

        $precio_base_venta_ = (int)$precio_base_venta + $importe_;


        $tpl->newBlock("forma_pago_venta");
        $porcentaje_contado = $precio_base_venta_;
        $tpl->assign("nombre_pago", 'Precio Base'.' (% 0)');
        
        $tpl->newBlock("forma_pago_venta_opciones");
        $tpl->assign("valor_pago", '$'.$precio_base_venta_);
        //$tpl->assign("id_cat_gc_art_vendido",$value->getId_categoria());

        if ($lote_local->getId_lote()->getId_gc() != null) {
            # code...
            
            $art_cb = $lote_local->getId_lote()->getId_gc()->getId_categoria();
            
            foreach ($art_cb as $key => $value) {
                
               if (strcmp($value->getNombre(), "Medida" ) == 0 ) {
                
                if ($value->getValor() == '') {
                    $tpl->assign("art_medida", 'Sin Definir');
                } 
                else{
                    $tpl->assign("art_medida", $value->getValor());
                }            
                
                
                
            }
        }

        foreach ($art_cb as $key => $value) {
           
            if (strcmp($value->getNombre(), "Precio" ) == 0 ) {
                $precio_base_venta =  $value->getValor();
                $tpl->newBlock("forma_pago_venta");
                $porcentaje_contado = $value->getValor();
                $tpl->assign("nombre_pago", 'Precio Base'.' (% 0)');
                
                $tpl->newBlock("forma_pago_venta_opciones");
                $tpl->assign("valor_pago", '$'.$precio_base_venta);
                $tpl->assign("id_cat_gc_art_vendido",$value->getId_categoria());
                
                
            }
            if (strcmp($value->getNombre(), "Tarjeta" ) == 0 ) {
                $tpl->newBlock("forma_pago_venta"); 
                $porcentaje_tarjeta = $value->getValor();
                $tpl->assign("nombre_pago", 'Tarjeta de Credito'.' (% '.$porcentaje_tarjeta.')' );
                
                $precio_tarjeta = $precio_base_venta + (($precio_base_venta * $value->getValor())/100);
                $tpl->newBlock("forma_pago_venta_opciones");
                $tpl->assign("valor_pago", 0 . ' x '.' $'.round($precio_tarjeta,2));
                for ($i=1; $i <= 12 ; $i++) { 
                    $tpl->newBlock("forma_pago_venta_opciones");
                    $tpl->assign("valor_pago", $i . ' x '.' $'.round($precio_tarjeta/$i,2));
                    $tpl->assign("id_cat_gc_art_vendido",$value->getId_categoria());
                }
            }
            if (strcmp($value->getNombre(), "CreditoP" ) == 0 ) {
                $tpl->newBlock("forma_pago_venta"); 
                $porcentaje_credito = $value->getValor();
                $tpl->assign("nombre_pago", 'Credito Personal'.' (% '.$porcentaje_credito.')' );
                
                $precio_tarjeta = $precio_base_venta + (($precio_base_venta * $value->getValor())/100);
                $tpl->newBlock("forma_pago_venta_opciones");
                $tpl->assign("valor_pago", 0 . ' x '.' $'.round($precio_tarjeta,2));
                for ($i=1; $i <= 12 ; $i++) { 
                    $tpl->newBlock("forma_pago_venta_opciones");
                    $tpl->assign("valor_pago", $i . ' x '.' $'.round($precio_tarjeta/$i,2));
                    $tpl->assign("id_cat_gc_art_vendido",$value->getId_categoria());
                }
            }
        }
    }
        //obtener_admin_si es necesario
    if (Ingreso_Controller::es_admin()) {
            # code...
        $id_usuario = $_SESSION["usuario"]->getId_user();
    }else{

       
       $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
   }

   
   $medio_pago = us_medio_pago::obtener($id_usuario);
   foreach ($medio_pago as $key6 => $value6) {
    $muestra = false;

    
    $date_php = getdate();
    $okok_fecha = false;
    if ($value6->getId_medio_fechas() != null) {
                # code...
        $fecha_desde = $value6->getId_medio_fechas()->getFecha_hora_inicio();
        $fecha_hasta = $value6->getId_medio_fechas()->getFecha_hora_fin();
        $dias_faltantes_desde = Articulo_Controller::compararFechas($fecha_desde,$hoy);
        $dias_faltantes_hasta = Articulo_Controller::compararFechas($fecha_hasta,$hoy);

    }
    else{
        $okok_fecha = true;
    }
    
    $dias = $value6->getId_medio_dias()->getDias();
            //$dia_hoy = $date_php['wday'];
    $hoy = $date_php['year'].'-'.$date_php['mon'].'-'.$date_php['mday'];
    
            //Verificar Fecha
    if ($okok_fecha) {
                # code...
        if ($dias_faltantes_desde <= 0 && $dias_faltantes_hasta >=0) {
            
            $muestra = true;
        }else{
            
            $muestra = false;
        }
    }
    else{
        $muestra = true;
    }
            //Verificar Dia
    $dia_hoy = $date_php['wday'];
    $dias_array = explode ("&", $dias);
    $dias_array = str_replace("7","0",$dias_array);

    foreach ($dias_array as $keyd => $valued) {
                # code...
        if ($valued == $dia_hoy) {
            $muestra = true;
            
            break;
        }else{
            
            $muestra = false;
        }
    }
    
    

    if ($muestra) {
        $tpl->newBlock("medio_pago_venta_opciones");
        $desimp = $value6->getDesImp()->getValor();
        $desimp_signo = $value6->getDesImp()->getSigno();
        $tpl->assign("id_medio_pago",$value6->getId());
                //$tpl->assign("id_medio_pago",$value6->getNombre().'(-%'.$descuento.')');
        
        if ($desimp != 0) {
            
            $tpl->assign("nombre_medio_pago",$value6->getNombre().'('.$desimp_signo.'%'.$desimp.')');
        }else{
            $tpl->assign("nombre_medio_pago",$value6->getNombre());
        }
        
    }
    $muestra = false;
    
    
}


$local = $lote_local->getId_local();
$lote_local->setCantidad_parcial($lote_local->getCantidad_parcial()- 1);
$lote_local->getId_lote()->setCantidad($lote_local->getId_lote()->getCantidad()-1);
$stock_actual = $lote_local->getCantidad_parcial();



$tpl->newBlock("stock_futuro_on");
$tpl->assign("local_nombre",$local->getNombre());
$tpl->assign("cantidad_art_local",$stock_actual);
$_SESSION["art_lote_local"] = $lote_local;
$tpl->newBlock("no_venta");
if ($id_lote_local != null) {
    $tpl->assign("id_lote_local",$id_lote_local);
}else{
    
    $tpl->assign("id_lote_local",$id_art_lotte_loccal);
}



return $tpl->getOutputContent();
}

public static function no_venta(){
    $id_lote_local = $_GET['id_lote_local'];
    $id_usuario = $_SESSION['usuario']->getId_user();
    $hoy = getdate();
    $fecha_no_venta = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds']; 
        //Insertar en ART_NO_VENTA
    $id_no_venta = art_no_venta::alta_art_no_venta($fecha_no_venta,$id_usuario,$id_lote_local);
    if ($id_no_venta) {
            # code...
        if (Ingreso_Controller::es_admin()) {
            # code...
            return Articulo_Controller::mostrar($id_lote_local);
        }else{
            $lote_local = art_lote_local::generar_lote_local_id_($id_lote_local);
            $id_local = $lote_local->getId_lote()->getId_lote();

            return Articulo_Controller::mostrar_operador($id_local);
        }
    }else{
        $tpl = new TemplatePower("template/error.html");
        $tpl->prepare();
        return $tpl->getOutputContent();
    }
    
    
    

}
public static function compararFechas($primera, $segunda){
    $valoresPrimera = explode ("-", $primera);   
    $valoresSegunda = explode ("-", $segunda); 

    $diaPrimera    = $valoresPrimera[2];  
    $mesPrimera  = $valoresPrimera[1];  
    $anyoPrimera   = $valoresPrimera[0]; 

    $diaSegunda   = $valoresSegunda[2];  
    $mesSegunda = $valoresSegunda[1];  
    $anyoSegunda  = $valoresSegunda[0];

    $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
    $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);     

    if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
    // "La fecha ".$primera." no es v&aacute;lida";
     return 0;
 }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
    // "La fecha ".$segunda." no es v&aacute;lida";
     return 0;
 }else{
  return  $diasPrimeraJuliano - $diasSegundaJuliano;
} 

}


public static function venta_finalizar(){
    $bandera = false;
    $cuotas = $_POST['cuotas_art_venta'];
    $medio = $_POST['medio_art_venta'];
    $total = $_POST['precio_final_art_venta'];
    $cuotas2 = str_replace('$','',$cuotas);
    
    if ($total == 'null' ||  $medio == null) {
        $bandera = true;
    }
    $tpl = new TemplatePower("template/exito_fracaso_venta.html");
    $tpl->prepare();

    $hoy = getdate();
    $fecha_venta = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];

    $id_usuario = $_SESSION["usuario"]->getId_user();

        //alta en art_venta  
    $id_venta = art_venta::alta($fecha_venta,$id_usuario,$medio,$total,$cuotas2);
        //alta en art unico
    $id_lote_local = $_SESSION["art_lote_local"]->getId_lote_local();
    $id_unico = art_unico::alta_art_unico($id_lote_local,$id_venta);

    if ($id_venta != false && $id_lote_local != false  && $id_unico != false && $bandera == false) {
            //Actualizar Stock
        $cantidad_total_art_lote = $_SESSION["art_lote_local"]->getId_lote()->getCantidad();
        $cantidad_parcial_art_lote_local =$_SESSION["art_lote_local"]->getCantidad_parcial();

        $okokok = art_lote_local::actualiza_($cantidad_total_art_lote,$cantidad_parcial_art_lote_local);

        if ($okokok) {
         
            $art_nombre = $_SESSION["art_lote_local"]->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
            $art_marca =  $_SESSION["art_lote_local"]->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
            $art_tipo = $_SESSION["art_lote_local"]->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
            $nombre_completo_art =(string)$art_nombre.' ,'.$art_marca.' ,'.$art_tipo;

            if (Ingreso_Controller::es_admin()) {
                $tpl->newBlock("exito");
                $tpl->assign("nombre_usuario",$_SESSION["usuario"]->getUsuario());
                $tpl->assign("nombre_completo_art",$nombre_completo_art);
                $tpl->assign("precio",$total);
                $tpl->assign("fecha_hora_venta",$fecha_venta);
                $tpl->assign("id_local",$_SESSION["usuario"]->getLocal_Actual());
            }
            else{
                $tpl->newBlock("exito_oper");
                $tpl->assign("nombre_usuario",$_SESSION["usuario"]->getUsuario());
                $tpl->assign("nombre_completo_art",$nombre_completo_art);
                $tpl->assign("precio",$total);
                $tpl->assign("fecha_hora_venta",$fecha_venta);
                $tpl->assign("id_local",$_SESSION["usuario"]->getLocal_Actual());
            }
            
        }
        else{
            $bandera = true;
        }
    }
    else{
        
        $bandera = true;
    }
    if ($bandera) {
        if (Ingreso_Controller::es_admin()) {
            $tpl->newBlock("fracaso");
        }
        else{
            $tpl->newBlock("fracaso_oper");
        }
        
    }

    return $tpl->getOutputContent();
}



    //}
public static function actualiza_stock($id_lote_lote = null){
    $id_local = $_POST['actualiza_nombre_local'];
    $cantidad_actualizar = $_POST['actualiza_cantidad_local'];
    $id_lote = $_GET['id_art_lote_stock_actual'];

    $cortar = false;
    $contador = 0;
    
            //Preguntar si existe un art_lote_local para id_lote y id_local
    foreach ($id_local as $key => $value) {
        $id_lote_local = art_lote_local::obtener_lote_local_oper($id_lote,$value);
        if ($id_lote_local) {
            $cantidad_parcial_cambio = art_lote_local::cantidad_parcial_modificada($id_lote_local,$cantidad_actualizar[$contador]);
                    //if ($cantidad_parcial_modificada) {
            if ($cantidad_parcial_cambio != $cantidad_actualizar[$contador]) {
                art_lote_local::update_cantidad_parcial($id_lote_local,$cantidad_actualizar[$contador]);
                    //update cantidad_parcial
                if ($cantidad_actualizar[$contador] < $cantidad_parcial_cambio) {
                    
                            //$cantidad_actualizar[$contador] = $cantidad_actualizar[$contador] * -1;
                }
                
                    //update cantidad total
                $cantidad_total_actual = art_lote::obtener_cantidad_total($id_lote);
                
                
                if ($cantidad_actualizar[$contador] < $cantidad_parcial_cambio) {
                            //Difetencia entre la cantitad total y la cantidad ingresada
                    $cantidad_diferencia = $cantidad_parcial_cambio - $cantidad_actualizar[$contador];
                    $cantidad_final_final =  $cantidad_total_actual - $cantidad_diferencia;
                }else{
                            //Difetencia entre la cantitad total y la cantidad ingresada
                    $cantidad_diferencia = $cantidad_actualizar[$contador] - $cantidad_parcial_cambio;

                    $cantidad_final_final = $cantidad_diferencia + $cantidad_total_actual;
                }
                
                art_lote::update_cantidad_total($id_lote,$cantidad_final_final);

                
            }
        }else{
                        //Crear regitro en art_carga
            $hoy = getdate();
            $fecha_venta = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
            $id_usuario = $_SESSION["usuario"]->getId_user();
            $id_carga = art_carga::alta_art_carga($fecha_venta,$id_usuario);
            
            
            if ($id_carga) {
                        //Crear registro en art_lote_local
                

                $id_lote_local = art_lote_local::alta_art_lote_local($id_lote,$value,$cantidad_actualizar[$contador],$id_carga);
                
                        //Update art_lote cantidad total
                $cantidad_total_actual = art_lote::obtener_cantidad_total($id_lote);
                $cantidad_final = $cantidad_total_actual + $cantidad_actualizar[$contador];
                art_lote::update_cantidad_total($id_lote,$cantidad_final);

            }else{
                
                $cortar = true;
            }
        }
        

        
        

        $contador = $contador + 1;
    }

    if (!$cortar) {
        $tpl = new TemplatePower("template/exito.html");
        $tpl->prepare();
    }else{
        $tpl = new TemplatePower("template/error.html");
        $tpl->prepare();


    }
    
    


    return $tpl->getOutputContent();

}

public static function actualiza_precio($id_lote = null,$precio_base = null, $precio_tarjeta = null, $precio_credito = null){
    $desde_adentro = false;
    if ($id_lote == null && $precio_base == null && $precio_tarjeta == null && $precio_credito == null) {
        $id_lote = $_GET['id_art_lote'];
        $precio_base = $_POST['art_precio_base'];
        $ganancia_importe = $_POST['art_importe_ganancia'];

        $precio_tarjeta = $_POST['art_precio_tarjeta'];
        $precio_credito = $_POST['art_precio_credito_argentino'];
                # code...
    }else{
                //Nada
        $desde_adentro = true;

    }
    

    $lote = art_lote::generar_lote($id_lote);
    
    
    $ok = art_lote::update($id_lote,'precio_base',$precio_base);
    $ok_1 = art_lote::update($id_lote,'importe',$ganancia_importe);

    if ($ok && $ok_1) {
        if ($desde_adentro) {
                    # code...
            return 0;
        }else{
            $tpl = new TemplatePower("template/exito.html");
            $tpl->prepare();
        }
        
    }else{
        if ($desde_adentro) {
                    # code...
            return 1;
        }else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
        
    }
    return $tpl->getOutputContent();
}


public static function modificar_venta(){
    if (Ingreso_Controller::es_admin()) {
                # code...
        
        $id_venta = $_GET['id_venta'];
        $id_lote_local = $_GET['id_lote_local'];
        $venta =  art_venta::generar_venta($id_venta);
        $lote_local_vendido_ = art_lote_local::generar_lote_local_id_($id_lote_local);
        $lote_local_vendido = $lote_local_vendido_;
        
        $art_nombre = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
        $art_marca =  $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
        $art_tipo = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
        $nombre_completo_art =(string)$art_nombre.','.$art_marca.','.$art_tipo;

        if ($lote_local_vendido->getId_us_gcat()) {
                # code...
          
            $nombre_local = $lote_local_vendido->getId_local()->getNombre();
            $gc = $lote_local_vendido->getId_lote()->getId_us_gcat()->getId_categoria();
            foreach ($gc as $clave => $valor) {
                if (strcmp($valor->getNombre(), "Medida" ) == 0 ) {
                    $medida = $valor->getValor();

                }

                if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                    $precio_base = $valor->getValor();
                    
                }
                if (strcmp($valor->getNombre(), "Tarjeta" ) == 0 ) {
                    $por_ciento_t =  $valor->getValor();
                    if ($por_ciento_t == 100) {
                                                # code...
                        $por_ciento_t_2 = 1;
                    }
                    else{
                        $por_ciento_t_2 = '0.'.$por_ciento_t;
                    }
                    $precio_tarjeta = $precio_base + ($precio_base * $por_ciento_t_2);
                }

                if (strcmp($valor->getNombre(), "CreditoP" ) == 0 ) {
                    $por_ciento_p = $valor->getValor();
                    if ($por_ciento_p == 100) {
                        # code...
                        $por_ciento_p_2 = 1;
                    }else{
                        $por_ciento_p_2 = '0.'.$por_ciento_p;
                    }
                    
                    $credito_personal = $precio_base + ($precio_base * $por_ciento_p_2);
                    
                }

                if (strcmp($valor->getNombre(), "Color" ) == 0 ) {
                    $art_color = $valor->getValor();
                    
                }
            }
        }
        $nombre_medio = $venta->getMedio()->getNombre();
        $descuento = $venta->getMedio()->getDescuento();
        $precio_vendido = $venta->getTotal();
        $fecha_venta = $venta->getFecha_hora();
        $usuario = $venta->getId_usuario()->getUsuario();
        $tpl = new TemplatePower("template/venta_art_modificar.html");
        $tpl->prepare();
        
        
        $tpl->assign("art_nombre",$nombre_completo_art);
        

        $tpl->assign("art_precio",'$'.$precio_vendido.' ('.$nombre_medio.' %'.$descuento.')');
        $tpl->assign("nombre_local",$nombre_local);
        $tpl->assign("usuario_vendedor",$usuario);
        $tpl->assign("fecha_venta",$fecha_venta);

            //cargar articulos 
        
        
            ///Genera Lotes Locales
        $_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user());
        
        $lotes_localess = $_SESSION["lote_local"];
        
        
        $tpl->assign("id_venta",$id_venta);
        $tpl->assign("id_lote_local",$id_lote_local);

        foreach ($lotes_localess as $key2 => $value2) {
            
            foreach ($value2 as $key => $value) {
                
                

                $cantidad_parcial = $value->getCantidad_parcial();
                if ($cantidad_parcial > 0 && $value->getId_lote_local() != $id_lote_local) {
                    $tpl->newBlock("cargar_articulo");
                    $tpl->assign("id_lote_local",$value->getId_lote_local());
                    $art_nombre_2 = $value->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
                    $art_marca_2 =  $value->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
                    $art_tipo_2 = $value->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
                    $nombre_completo_art_2 =(string)$art_nombre_2.','.$art_marca_2.','.$art_tipo_2;

                    
                    $nombre_local = $value->getId_local()->getNombre();
                    if ($value->getId_lote()->getId_us_gcat()) {
                       # code...
                     
                        $gc = $value->getId_lote()->getId_us_gcat()->getId_categoria();
                        foreach ($gc as $clave => $valor) {
                            

                            if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                                $precio_base_vender = $valor->getValor();
                                
                            }
                            if (strcmp($valor->getNombre(), "Medida" ) == 0 ) {
                                $medida = $valor->getValor();

                            }
                            
                        }
                    }
                    $tpl->assign("nombre_articulo",$nombre_completo_art_2.'('.$medida.')'.' ('.$nombre_local.' $'.$precio_base_vender.')');

                    $art_nombre_2 = '';
                    $art_marca_2 = '';
                    $art_tipo_2= '';
                    $nombre_completo_art_2 = '';
                }
                
            }
            
            
        }
    }
    else{
        echo "No eres admin";
        return Ingreso_Controller::salir();
    }


    
    return $tpl->getOutputContent();
    
}


public static function confirmar_venta(){
    $id_venta = $_GET['id_venta'];
    $id_lote_local_vendido = $_GET['id_lote_local'];
    $id_lote_local_cambio = $_POST['art_venta_cambio'];
    $venta =  art_venta::generar_venta($id_venta);
    
    $lote_local_cambiar = art_lote_local::generar_lote_local_id_($id_lote_local_cambio);

    $lote_local_vendido_ = art_lote_local::generar_lote_local_id_($id_lote_local_vendido);
    
    $lote_local_vendido = $lote_local_vendido_;
    
    $art_nombre = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
    $art_marca =  $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
    $art_tipo = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
    $nombre_completo_art =(string)$art_nombre.' ,'.$art_marca.' ,'.$art_tipo;
    if ($lote_local_vendido->getId_lote()->getId_us_gcat()) {
                # code...
        
        $gc = $lote_local_vendido->getId_lote()->getId_us_gcat()->getId_categoria();
        foreach ($gc as $clave => $valor) {
            if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                $precio_base_vender = $valor->getValor();
                
            }
            
        }
    }
    $nombre_local = $lote_local_vendido->getId_local()->getNombre();
    $nombre_medio = $venta->getMedio()->getNombre();
    $descuento = $venta->getMedio()->getDescuento();
    $precio_vendido = $venta->getTotal();
    $fecha_venta = $venta->getFecha_hora();
    $usuario = $venta->getId_usuario()->getUsuario();


    $lote_local_cambiar_ = $lote_local_cambiar ;
    $art_nombre2 = $lote_local_cambiar_->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
    $art_marca2 =  $lote_local_cambiar_->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
    $art_tipo2 = $lote_local_cambiar_->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
    $nombre_completo_art2 =(string)$art_nombre2.' ,'.$art_marca2.' ,'.$art_tipo2;
    if ($lote_local_cambiar_->getId_lote()->getId_us_gcat()) {
                # code...
        
        $gc2 = $lote_local_cambiar_->getId_lote()->getId_us_gcat()->getId_categoria();
        foreach ($gc2 as $clave2 => $valor2) {
            if (strcmp($valor2->getNombre(), "Precio" ) == 0 ) {
                $precio_base_vender2 = $valor2->getValor();
                
            }
            
        }

    }
    $tpl = new TemplatePower("template/venta_art_modificar_confirmacion.html");
    $tpl->prepare();
            //Articulo Viejo
    $tpl->assign("art_nombre",$nombre_completo_art);
    $tpl->assign("art_precio_base",'$'.$precio_base_vender);
    $tpl->assign("art_precio",'$'.$precio_vendido.' ('.$nombre_medio.' %'.$descuento.')');
    $tpl->assign("nombre_local",$nombre_local);
    $tpl->assign("usuario_vendedor",$usuario);
    $tpl->assign("fecha_venta",$fecha_venta);

            //Articulo Nuevo
    $tpl->assign("art_nombre_2",$nombre_completo_art2);
    $tpl->assign("art_precio_2",'$'.$precio_base_vender2);
                //Calcular Saldo a Favor

    
    $saldo_favor = $precio_base_vender2 - $precio_base_vender;

    $tpl->assign("saldo_favor",'$'.$saldo_favor);
    
    $tpl->newBlock("datos_pasar");
    $tpl->assign("id_venta",$id_venta);
    $tpl->assign("id_lote_local_cambio",$id_lote_local_cambio);
    $tpl->assign("id_lote_local_viejo",$id_lote_local_vendido);

    $tpl->assign("saldo_favor_2",$saldo_favor);
    
    

    $tpl->newBlock("cancelar_boton");
    $tpl->assign("id_venta_cancelar",$id_venta);
    $tpl->assign("id_lote_local_cancelar",$id_lote_local_vendido);

    if ($saldo_favor >= 0) {
                # code...
        $tpl->newBlock("cambio_ok");

        $medio_pago = art_venta_medio::obtener_medios($_SESSION['usuario']->getId_user());
        
        foreach ($medio_pago as $key6 => $value6) {
            $muestra = false;
            $date_php = getdate();
            $fecha_desde = $value6->getId_fechas_medio()->getFecha_hora_inicio();
            $fecha_hasta = $value6->getId_fechas_medio()->getFecha_hora_fin();
            $dias = $value6->getId_dias_medio()->getDias();
            //$dia_hoy = $date_php['wday'];
            $hoy = $date_php['year'].'-'.$date_php['mon'].'-'.$date_php['mday'];
            $dias_faltantes_desde = Articulo_Controller::compararFechas($fecha_desde,$hoy);
            $dias_faltantes_hasta = Articulo_Controller::compararFechas($fecha_hasta,$hoy);
            //Verificar Fecha
            if ($dias_faltantes_desde <= 0 && $dias_faltantes_hasta >=0) {
                
                $muestra = true;
            }else{
              
                $muestra = false;
            }
            //Verificar Dia
            $dia_hoy = $date_php['wday'];
            $dias_array = explode ("&", $dias);
            foreach ($dias_array as $keyd => $valued) {
                # code...
                if ($valued == $dia_hoy) {
                    $muestra = true;
                    
                    break;
                }else{
                    
                    $muestra = false;
                }
            }
            
            

            if ($muestra) {
                $tpl->newBlock("medio_pago_venta_opciones");
                $descuento = $value6->getDescuento();
                $tpl->assign("id_medio_pago",$value6->getId_medio());
                //$tpl->assign("id_medio_pago",$value6->getNombre().'(-%'.$descuento.')');
                if ($descuento != 0) {
                    
                    $tpl->assign("nombre_medio_pago",$value6->getNombre().'(-%'.$descuento.')');
                }
                else
                {
                    $tpl->assign("nombre_medio_pago",$value6->getNombre());
                }
                
            }
            $muestra = false;
            
            
        }

    }else{
        $tpl->newBlock("cambio_no_ok");
    }






    return $tpl->getOutputContent();

    
}



public static function re_confirmar_venta(){
    $error = false;

    $id_venta_vieja = $_POST['id_venta'];
    $venta_vieja = art_venta::generar_venta($id_venta_vieja);
    $id_loca_local_id_viejo = $_POST['id_lote_local_viejo'];
    
    $id_lote_local_viejo = art_lote_local::generar_lote_local_id_($id_loca_local_id_viejo);
            //

    $id_lote_local_nuevo = $_POST['id_lote_local_cambio'];
    $lote_local_nuevo_ = art_lote_local::generar_lote_local_id_($id_lote_local_nuevo);
    $saldo_favor = $_POST['saldo_favor'];
    $id_medio_nuevo  = $_POST['id_medio_cambio'];
            //Generar venta Nueva
    $hoy = getdate();
    $fecha_hora = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
    $id_usuario = $_SESSION['usuario']->getId_user();
    
    $id_venta_nueva_alta = art_venta::alta_art_venta($fecha_hora,$id_usuario,$id_medio_nuevo,$saldo_favor);

    if ($id_venta_nueva_alta) {
                # code...
        $id_unico_cambio = art_unico::alta_art_unico($id_lote_local_nuevo,$id_venta_nueva_alta);
        if ($id_unico_cambio) {
                    # code...
            
            $upp_cambio = art_venta::update_id_cambio($id_venta_vieja,$id_venta_nueva_alta);
            
        }else{
            $error = true;
        }
                //Update a venta vieja con el id_venta nueva

        if ($upp_cambio == 1 || $error == true) {
                    //Agregar +1 en Stock dr lote viejo
         
            $id_lote_viejo = $id_lote_local_viejo->getId_lote();
            $cantidad_total_lote_viejo = $id_lote_viejo->getCantidad();
            $cantidad_total_lote_nuevo = $cantidad_total_lote_viejo + 1;
            $id_id_lote_viejo = $id_lote_viejo->getId_lote();
            $ok_up_total_cant = art_lote::update_cantidad_total($id_id_lote_viejo,$cantidad_total_lote_nuevo);
                    //Agregar +1 en Stock lote_local viejo
            $id_id_lote_local = $id_lote_local_viejo->getId_lote_local();

            
            $cantidad_parcial_vieja = $id_lote_local_viejo->getCantidad_parcial();
            $cantidad_parcial_nueva = $cantidad_parcial_vieja + 1;
            $ok_up_cant_parcial = art_lote_local::update_cantidad_parcial($id_id_lote_local,$cantidad_parcial_nueva);
                    //Restamos -1 en stock lote_local nuevo
            $cantidad_parcial_vieja_nueva = $lote_local_nuevo_->getCantidad_parcial();
            $cantidad_nueva_parcial = $cantidad_parcial_vieja_nueva - 1;
            $up_cantidad_parcial_nueva = art_lote_local::update_cantidad_parcial($lote_local_nuevo_->getId_lote_local(),$cantidad_nueva_parcial);
                    //Restamos -1 en stock lote nuevo
            $cantidad_total_lote_nuevo = $lote_local_nuevo_->getId_lote()->getCantidad();
            $cantidad_total_nueva = $cantidad_total_lote_nuevo - 1 ;

            $up_cantidad_total_nueva = art_lote::update_cantidad_total($lote_local_nuevo_->getId_lote()->getId_lote(),$cantidad_total_nueva);

            if ($ok_up_total_cant || $ok_up_cant_parcial || $up_cantidad_total_nueva || $up_cantidad_parcial_nueva) {
                        # code...
            }else{
               
                $error = true;
            }
        }else{
           
            $error = true;
        }

    }else{
       
        $error = true;
    }
    
            //
    if ($error) {
                # code...
        $tpl = new TemplatePower("template/exito_fracaso_venta.html");
        $tpl->prepare();
        $tpl->newBlock("fracaso");
    }else{
       $tpl = new TemplatePower("template/exito_fracaso_venta.html");
       $tpl->prepare();
       $tpl->newBlock("exito_cambio");
       $art_nombre = $lote_local_nuevo_->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
       $art_marca =  $lote_local_nuevo_->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
       $art_tipo = $lote_local_nuevo_->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
       $nombre_completo_art =(string)$art_nombre.' ,'.$art_marca.' ,'.$art_tipo;
       $tpl->assign("nombre_completo_art",$nombre_completo_art);
       $tpl->assign("saldo_favor",$saldo_favor);
       $tpl->assign("fecha_hora_venta",$fecha_hora);

   }

   return $tpl->getOutputContent();


}

public static function actualizar_precio_lote(){
    if (Ingreso_Controller::es_admin()) {
       
        $nombre_articulo = $_POST['articulo_actualiza_precio_masivamente_2'];
        $precio_base_nuevo = $_POST['art_precio_base_masivo'];
        $precio_tarjeta_nuevo = $_POST['art_precio_tarjeta_masivo'];
        $precio_credito_personal = $_POST['art_precio_credito_argentino_masivo'];

        $nombre_articulo2 = str_replace(' ','',$nombre_articulo);
        $nombre_articulo2 = str_replace(',','',$nombre_articulo2);

        $articulo_por_conjunto = explode (",", $nombre_articulo);
                //$articulo_por_conjunto[0] ->nombre general
                //$articulo_por_conjunto[1] ->nombre marca
                //$articulo_por_conjunto[2] ->nombre modelo
        $nom_com0 = $nombre_articulo2;
        
        $lotes_actualizar = array();
        foreach ($_SESSION["lotes"] as $key => $value) {
                    # code...
            $art = $value->getId_art_conjunto()->getId_articulo()->getNombre();
            $marca = $value->getId_art_conjunto()->getId_marca()->getNombre();
            $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
            $nom_com = $art.$marca.$tipo;
            $nom_com = str_replace(' ','',$nom_com);
                    /*if (strcmp($art, $articulo_por_conjunto[0] ) == 0  && strcmp($marca, $articulo_por_conjunto[1] ) == 0 && strcmp($tipo, $articulo_por_conjunto[2] ) ) {
                        # code...
                        echo "Yes";
                        $lotes_actualizar[] = $value->getId_lote();

                    }*/
                    if (strcmp($nom_com0, $nom_com) == 0 ) {
                        # code...
                        $lotes_actualizar[] = $value->getId_lote();
                    }
                    
                    
                }
                
                if (count($lotes_actualizar) == 0) {
                    # Error no se enciotraron articulos
                   
                    $error = true;
                }
                else{
                    //Actualizar los articulos
                    $error = false;
                    foreach ($lotes_actualizar as $key2 => $value2) {
                        # code...
                        $ok = Articulo_Controller::actualiza_precio($value2,$precio_base_nuevo, $precio_tarjeta_nuevo, $precio_credito_personal);

                        if ($ok == 1) {
                            # code...
                            $error = true;
                            break;
                        }
                    }

                }
                //print_r(count($lotes_actualizar));
                if ($error) {
                        # code...
                    $tpl = new TemplatePower("template/exito_fracaso_venta.html");
                    $tpl->prepare();
                    $tpl->newBlock("fracaso_actualizacion_precio_masiva");
                }else{
                    $tpl = new TemplatePower("template/exito_fracaso_venta.html");
                    $tpl->prepare();
                    $tpl->newBlock("exito_actualizacion_precio_masiva");
                    $tpl->assign("nombre_completo_art",$nombre_articulo);
                    $tpl->assign("cantidad_total",count($lotes_actualizar));

                }

            }
            else{
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }

        

        public static function mostrar_gct(){
            if (Ingreso_Controller::es_admin()) {
                $tpl = new TemplatePower("template/seccion_admin_gct.html");
                $tpl->prepare();

                //Obtener Grupo de Atributos
                
                $us_gct = us_art_gcat::obtener($_SESSION['usuario']->getId_user());
                
                if ($us_gct) {
                    # code...
                    $tpl->newBlock("buscador_visible");
                    $tpl->newBlock("con_gct");
                    $counter = 1;
                    $gct = $us_gct->getId_us_art_cat();

                    foreach ($gct as $key => $value) {
                        # code...
                        
                       
                        $tpl->newBlock("con_gct_lista_cuerpo");
                        $id_gct = $value->getId();
                        

                        $nombre_grupo = $value->getNombre();
                        $des_grupo = $value->getDescripcion();
                        $gcategorias = $value->getId_gc();
                        $estado = $value->getHabilitado();

                        $nombre_ = '';
                        $categorias = $gcategorias->getId_categoria();
                        foreach ($categorias as $key2 => $value2) {
                            # code...
                            //$ct = $value2->getId_categoria();
                            $nombres = $value2->getNombre();

                            $nombre_ = $nombre_.$nombres.'<br>';
                        }

                        $tpl->assign("numero",$counter);
                        $tpl->assign("nombre",$nombre_grupo);
                        $tpl->assign("des",$des_grupo);

                        $tpl->assign("attr",$nombre_);

                        if ($estado == true) {
                            # code...
                            $tpl->assign("habilitado",'Habilitado');
                        }else
                        {
                            $tpl->assign("habilitado",'Desabilitado');
                        }
                        
                        $tpl->assign("id_gc_",$id_gct);
                        
                        $counter = $counter + 1 ;
                        
                        $tpl->newBlock("modal_cambiar_estado");
                        $tpl->assign("id_gct",$id_gct);
                        $tpl->assign("id_us_art_cat",$id_gct);
                    }
                }
                else{
                   
                    $tpl->newBlock("sin_gct");
                }

                
                
                

            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }

        
        public static function mostrar_monedas(){
            if (Ingreso_Controller::es_admin()) {
                $tpl = new TemplatePower("template/seccion_admin_moneda.html");
                $tpl->prepare();

                //Obtener Monedas Us
                $us_monedas = us_moneda::obtener($_SESSION['usuario']->getId_user());
                
                
                if ($us_monedas) {
                    
                    $monedas = $us_monedas->getId_moneda();
                    $tpl->newBlock("buscador_visible");
                    $tpl->newBlock("con_moneda");
                    $counter = 1;

                    foreach ($monedas as $key => $value) {

                        $tpl->newBlock("con_moneda_lista_cuerpo");
                        $id_moneda = $value->getId();
                        $nombre = $value->getNombre();
                        $valor = $value->getValor();


                        $tpl->assign("numero",$counter);
                        $tpl->assign("nombre",$nombre);
                        $tpl->assign("valor",$valor);
                        $tpl->assign("id_moneda",$id_moneda);
                        $counter = $counter + 1 ;
                        
                        $tpl->newBlock("modal_modificar_valor");
                        $tpl->assign("id_moneda",$id_moneda);
                        $tpl->assign("id_moneda_act",$id_moneda);
                        $tpl->assign("nombre_modal",$nombre);
                        $tpl->assign("valor_modal",$valor);
                        
                    }
                }
                else{
                   
                    $tpl->newBlock("sin_monedas");
                }

            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }
        
        public static function actualiza_moneda(){
            if (Ingreso_Controller::es_admin()) {
                //Verificar si la moneda pertenece al usuario
                $id_moneda = $_GET['id_moneda'];
                $valor = $_POST['valor_nuevo_moneda'];
                $us_monedas = us_moneda::obtener($_SESSION['usuario']->getId_user());
                $monedas = $us_monedas->getId_moneda();
                $bandera = false;
                foreach ($monedas as $key => $value) {
                    $id_moneda_us = $value->getId();
                    if ($id_moneda_us == $id_moneda) {
                        $bandera = true;
                    }
                }
                if ($bandera) {
                    $ok = art_moneda::update($id_moneda,'valor',$valor);
                }else{
                    return Ingreso_Controller::salir();
                }
                
                if ($ok) {
                    $tpl = new TemplatePower("template/exito.html");
                    $tpl->prepare();
                }else{
                    $tpl = new TemplatePower("template/error.html");
                    $tpl->prepare();
                }
                

            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }


        public static function form_alta_moneda(){
            if (Ingreso_Controller::es_admin()) {
                $tpl = new TemplatePower("template/cargar_moneda.html");
                $tpl->prepare();

            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }
        

        public static function alta_moneda(){
            if (Ingreso_Controller::es_admin()) {
              
                $nombre = $_POST['nombre_moneda'];
                $valor = $_POST['valor_moneda'];
                $id_user = $_SESSION['usuario']->getId_user();
                //Alta en art_moneda
                $id_moneda = art_moneda::alta($nombre,$valor);
                //Alta en us_moneda
                if ($id_moneda) {
                    $id_us_moneda = us_moneda::alta($id_moneda,$id_user);
                }
                if ($id_us_moneda) {
                    $tpl = new TemplatePower("template/exito.html");
                    $tpl->prepare();
                    $tpl->newBlock("alta_moneda_exito");
                    
                }else{
                    $tpl = new TemplatePower("template/error.html");
                    $tpl->prepare();
                }
            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }


        public static function cambiar_gs_estado(){
         

            if (Ingreso_Controller::es_admin()) {
                $id_us_art_cat = $_GET['id_us_art_cat'];
                $estado = $_POST['estado'];
                //Obtener gcat
                $us_gct = us_art_gcat::obtener($_SESSION['usuario']->getId_user());
                $us_art_cat = $us_gct->getId_us_art_cat();
                foreach ($us_art_cat as $key => $value) {
                    # code...
                    //Desabilitar Todos los us_art_cat
                    $id = $value->getId();
                    $okok = us_art_cat::update($id,'habilitado',0);

                }

                //Updtade al Seleccionado
                if ($estado == 1) {
                    # code...
                    $okok2 = us_art_cat::update($id_us_art_cat,'habilitado',true);
                }
                else
                {
                    $okok2 = us_art_cat::update($id_us_art_cat,'habilitado',false);
                }
                

                if ($okok2) {
                    # code...
                    $tpl = new TemplatePower("template/exito.html");
                    $tpl->prepare();
                    
                }
                else{
                    $tpl = new TemplatePower("template/error.html");
                    $tpl->prepare();

                }
                

                
                

            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }

        public static function form_alta_grupo_atributos(){
            if (Ingreso_Controller::es_admin()) {
                $tpl = new TemplatePower("template/cargar_grupo_atributos_art.html");
                $tpl->prepare();

            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }

        public static function alta_grupo_atributos(){
            if (Ingreso_Controller::es_admin()) {
              
                $nombre_grupo = $_POST['art_gcat_nombre'];
                $des_gupo = $_POST['art_gcat_des'];

                $gct_nombre = $_POST['gct_nombre'];
                $gct_des = $_POST['gct_des'];


                //Generara las Categorias
                $id_ct_todas = array();
                $counter = 0;
                $una_vez = true;
                
                foreach ($gct_nombre as $key => $value) {
                    # code...
                    
                   
                    $id_ct_todas[] = art_categoria::alta($value,$gct_des[$counter]);

                    if ($una_vez) {
                       
                        $id_gc = art_grupo_categoria::alta_($id_ct_todas[0]);
                        
                        $counter = $counter + 1;
                        $una_vez = false;
                        
                        continue;
                    }
                    
                    $ok_gct = art_grupo_categoria::alta_($id_ct_todas[$counter],$id_gc);
                    

                    $counter = $counter + 1;
                }

                
                //Alta en us_art_cat
                
                $id_us_art_cat = us_art_cat::alta($nombre_grupo,$des_gupo,$id_gc);
                //Alta em us_art_gcat
                
                $id_us_gcat = us_art_gcat::alta($id_us_art_cat,$_SESSION['usuario']->getId_user());

                if ($id_us_gcat) {
                    # code...
                    $tpl = new TemplatePower("template/exito.html");
                    $tpl->prepare();
                    $tpl->newBlock("alta_gcat_exito");
                    
                }else{
                    $tpl = new TemplatePower("template/error.html");
                    $tpl->prepare();
                }
            }
            else{
             
                return Ingreso_Controller::salir();
            }

            return $tpl->getOutputContent();
        }
        

        public static function vender(){
            $es_admin = false;
            if (isset($_GET['id_local'])) {
                $id_local_oper = $_GET['id_local'];
            }else{
                $id_local_oper = false;
            }
            

            if (Ingreso_Controller::es_admin()) {
                $es_admin = true;
                $id_usuario =  $_SESSION["usuario"]->getId_user();
            }
            else{ 
                $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
                $es_admin = false;
                //return Ingreso_Controller::salir();
            }
            $tpl = new TemplatePower("template/vender.html");
            $tpl->prepare();
            usuario::obtener_lote_us($id_usuario);
            if (isset($_SESSION["lotes"])) {
                $tpl->newBlock("buscador_visible");
                $tpl->newBlock("con_articulos_lista");
                
                $medio_pago = us_medio_pago::obtener($id_usuario);
                if (count($medio_pago) != 0) {
                    $tpl->newBlock("con_medios_pagos");
                    foreach ($medio_pago as $key6 => $value6) {
                        $muestra = false;
                        $date_php = getdate();
                        $okok_fecha = false;
                        if ($value6->getId_medio_fechas() != null) {
                            $fecha_desde = $value6->getId_medio_fechas()->getFecha_hora_inicio();
                            $fecha_hasta = $value6->getId_medio_fechas()->getFecha_hora_fin();
                            $dias_faltantes_desde = Articulo_Controller::compararFechas($fecha_desde,$hoy);
                            $dias_faltantes_hasta = Articulo_Controller::compararFechas($fecha_hasta,$hoy);
                        }
                        else{
                            $dias_faltantes_desde = false;
                            $okok_fecha = true;
                        }
                        $dias = $value6->getId_medio_dias()->getDias();
                            //$dia_hoy = $date_php['wday'];
                        $hoy = $date_php['year'].'-'.$date_php['mon'].'-'.$date_php['mday'];
                        if ($okok_fecha) {
                            if ($dias_faltantes_desde) {
                                if ($dias_faltantes_desde <= 0 && $dias_faltantes_hasta >=0) {
                                    $muestra = true;
                                }else{
                                    $muestra = false;
                                }
                            }
                        }
                        else{
                            $muestra = true;
                        }
                        $dia_hoy = $date_php['wday'];
                        $dias_array = explode ("&", $dias);
                        $dias_array = str_replace("7","0",$dias_array);
                        foreach ($dias_array as $keyd => $valued) {
                            if ($valued == $dia_hoy) {
                                $muestra = true;
                                break;
                            }else{
                                $muestra = false;
                            }
                        }
                        if ($muestra) {
                            $tpl->newBlock("medio_pago_venta_opciones");
                            $desimp = $value6->getDesImp()->getValor();
                            $desimp_signo = $value6->getDesImp()->getSigno();
                            $tpl->assign("id_medio_pago",$value6->getId());
                            if ($desimp != 0) {
                                $tpl->assign("nombre_medio_pago",$value6->getNombre().'('.$desimp_signo.'%'.$desimp.')');
                            }else{
                                $tpl->assign("nombre_medio_pago",$value6->getNombre());
                            } 

                            
                            $desimp = $value6->getDesImp()->getValor();
                            if ($desimp == 0) {
                                $tpl->newBlock("medio_pago_venta_opciones2");
                                $tpl->assign("id_medio_pago2",$value6->getId());
                                $tpl->assign("nombre_medio_pago",$value6->getNombre());
                            }
                        }
                        $muestra = false;   
                    }
                }else{
                    $tpl->newBlock("sin_medios_pagos");
                }

                if ($_SESSION['usuario']->obtener_locales($_SESSION['usuario'])) {
                    $tpl->newBlock("con_locales");
                    foreach ($_SESSION['locales'] as $key => $value) {
                        if ($id_local_oper) {
                            $id_local_ = $value->getId_local();
                            if ($id_local_ == $id_local_oper) {
                                $tpl->newBlock("locales_");
                                $tpl->assign("id_local", $value->getId_local());
                                $tpl->assign("nombre_local", htmlentities($value->getNombre(), ENT_QUOTES));
                                $tpl->assign("selected", 'selected');
                            }
                        }else{
                            $tpl->newBlock("locales_");
                            $tpl->assign("id_local", $value->getId_local());
                                //if (count($_SESSION['locales']) == 1) {
                                //    $tpl->assign("selected", 'selected');
                                //}

                            $tpl->assign("nombre_local", htmlentities($value->getNombre(), ENT_QUOTES));
                        }
                        
                    }     
                }
                else{
                    $tpl->newBlock("sin_locales");
                }
            }else{

                $tpl->newBlock("sin_articulos_lista");   
            }
            
            return $tpl->getOutputContent();
        }

        public static function cargar_art_venta($DatosAjax){
            if (Ingreso_Controller::es_admin()) {
                $es_admin = true;
                $id_usuario =  $_SESSION["usuario"]->getId_user();
            }
            else{ 
                $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
                $es_admin = false;
                //return Ingreso_Controller::salir();
            }

            $Datos = $DatosAjax;
                //require_once 'controladores/articulo_.php';
            
            $Respuesta = articulo::busqueda_ajax($Datos);

            if ($Respuesta) {
                    # code...
             return $Respuesta;
         }else{
                    //echo "Mal";
         }

         

            //return $tpl->getOutputContent();
     }
     
     public static function facturacion_input($id_lote){
        if (Ingreso_Controller::es_admin()) {
            $es_admin = true;
            $id_usuario =  $_SESSION["usuario"]->getId_user();
        }
        else{ 
            $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
            $es_admin = false;
                //return Ingreso_Controller::salir();
        }

        
                //require_once 'controladores/articulo_.php';
        
        $Respuesta = art_lote::facturacion_ajax($id_lote);
                //echo "Aca";
                //print_r($Respuesta);
                //echo "FinAca";
        if ($Respuesta) {
                    # code...
         return $Respuesta;
     }else{
                    //echo "Mal";
     }
     

            //return $tpl->getOutputContent();
 }

 public static function facturacion_finalizar($total,$medios_pago,$articulos,$cuotas,$id_local){
    if (Ingreso_Controller::es_admin()) {
        $es_admin = true;
        $id_usuario =  $_SESSION["usuario"]->getId_user();
    }
    else{ 
        $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
        $es_admin = false;
                //return Ingreso_Controller::salir();
    }
    $lote_local_array = array();
    
    foreach ($articulos as $key => $value) {
        $id = $value['id_lote'];
        $cantidad = $value['cantidad'];

        $id_lote_local_aux = art_lote_local::obtener_lote_local_oper($id,$id_local);
        $lote_local_aux = art_lote_local::generar_lote_local_id_($id_lote_local_aux);
        
        $id_lote_local = $lote_local_aux->getId_lote_local();

        $marca = $lote_local_aux->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
        $articulo = $lote_local_aux->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
        $tipo = $lote_local_aux->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
        
        $lote_aux = $lote_local_aux->getId_lote();
        $costo = $lote_aux->getPrecio_base();
        $moneda = $lote_aux->getId_moneda()->getValor();
        $importe = $lote_aux->getImporte();

        $precio_final = floatval($costo) * floatval($moneda);
        $importe_aux = ((floatval($importe) * floatval($precio_final))/100);
                    //$precio_finali_finali = round(floatval($importe_aux) + floatval($precio_final), 2);
        $precio_finali_finali = round(floatval($importe) * floatval($precio_final), 2);

        $nombre_art = $articulo.','.$marca.','.$tipo.','.'($'.$precio_finali_finali.')';

        $lote_local['id_lote_local'] = $id_lote_local;
        $lote_local['cantidad'] = $cantidad;
        $lote_local['rg_detalle'] = $nombre_art;
        
        $lote_local_array[] = $lote_local;
    }
                //Alta en art_gunico
    $medio_pago = array();
    foreach ($medios_pago as $key2 => $value2) {
        $id_mp = $value2['id_medio_pago'];
        $mp_aux = art_venta_medio_pago::generar($id_mp);

        $nombre_mp = $mp_aux->getNombre();
        $des_imp = $mp_aux->getDesImp()->getValor().'('.$mp_aux->getDesImp()->getSigno().')';

        $mp['id'] = $id_mp;
                    //$mp['subtotal'] = $value2['subtotal'];
        $mp['rg_detalle'] = $nombre_mp.','.$des_imp.','.$value2['subtotal'];
        $medio_pago[] = $mp;
    }
    $error_mp = false;
    $id_gmedio_next = art_gmedio_pago::ultimo_id();
    foreach ($medio_pago as $key4 => $value4) {
        $ok_gmp = art_gmedio_pago::alta($id_gmedio_next,$value4['id'],$value4['rg_detalle']);
        if ($ok_gmp) {
            $error_mp = false;
        }else{
            $error_mp = true;
            break;
        }
    }
    $error_gunico = false;
    $id_gunico_next = art_gunico::ultimo_id();
    foreach ($lote_local_array as $key3 => $value3) {
        $ok = art_gunico::alta($id_gunico_next,$value3['id_lote_local'],$value3['rg_detalle'],$value3['cantidad']);
        if ($ok) {
            $error_gunico = false;
        }else{
            $error_gunico = true;
                        //echo "acaERRORR";
            break;
        }
    }
    
    if ($error_gunico || $error_mp) {
        if ($error_gunico && $error_mp) {
            $data['status'] = 'err2';
            $data['result'] = 'Error al cargar gmedio_pago y art_gunico';
        }else{
            if ($error_gunico){
                $data['status'] = 'err2';
                $data['result'] = 'Error al cargar art_gunico';
            }else{
                $data['status'] = 'err2';
                $data['result'] = 'Error al cargar gmedio_pago';
            }
        }
        
        $Respuesta = $data;
        return $Respuesta;
                    //echo json_encode($Respuesta);
        
    }else{
                    //print_r($id_gunico_next);
        $facutacion_estado = true;
        $hoy = getdate();
        $fecha_venta = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
        $id_usuario = $_SESSION["usuario"]->getId_user();
        
        $nro_comp_rg = art_venta::ultimo_id();
        $nro_art_rg = art_unico::ultimo_id_unico();
        $total_rg = $total;
        $id_local_rg  = $id_local;
        $id_usuario_rg = $id_usuario;

        $rg_detalle = $nro_comp_rg.','.$nro_art_rg.','.$total_rg.','.$id_local_rg.','.$id_usuario_rg;
         
        $id_venta = art_venta::alta($fecha_venta,$id_usuario,$id_gmedio_next,$total,$cuotas,'null','null',$rg_detalle);
        if ($id_venta) {
                        //Alta art_unico
            $id_unico = art_unico::alta_art_unico($id_gunico_next,$id_venta);

            if ($id_unico) {
                $facutacion_estado = true;
            }else{
                $facutacion_estado = false;
            }
        }else{
            $facutacion_estado = false;
        }
        if ($facutacion_estado) {
            $Respuesta = art_venta::facturacion_($lote_local_array);

        }else{
            $data['status'] = 'err';
            $data['result'] = '';
            $Respuesta = $data;
        }
        return $Respuesta;
    }
    
            //return $tpl->getOutputContent();
}


public static function cargar_art_general(){
    if (isset($nombre)) {
       
        $nombre = ucwords(strtolower($_POST['art_general']));
        
        
        $res = articulo::alta_art($nombre);
        
        if ($res) {
            
        }

    }
}

public static function cargar_art_marca(){

    $nombre = ucwords(strtolower($_POST['art_marca']));
    

}

public static function actualiza_atrs(){

    if (Ingreso_Controller::es_admin()) {
        $atr = $_POST['atr_act'];
        $id_atr = $_POST['id_atr_act'];

        $counter = 0;
        $error = false;
        foreach ($atr as $key => $value) {
            
            $okup = art_categoria::update_valores($id_atr[$counter],$value);

            if ($okup) {
                $error = false;
            }else{
                $error = true;
            }

            $counter = $counter + 1 ;
        }
        
        if (!$error) {
            $tpl = new TemplatePower("template/exito.html");
            $tpl->prepare();
            $tpl->newBlock("modifica_attr");
            
        }else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
    }else{  
        return Ingreso_Controller::salir();
    }
    
    return $tpl->getOutputContent();
}

}

?>