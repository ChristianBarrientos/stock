<?php
class Articulo_Controller{

	public static function mostrar(){
               
                
                $tpl = new TemplatePower("template/seccion_admin_articulos.html");
                $tpl->prepare();
                if (Ingreso_Controller::admin_ok()) {
                         
                        if ($_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user())) {
                            $tpl->newBlock("con_articulos_lista");
                            $tpl->newBlock("con_articulos_lista_cabeza");
                            
                            $tpl->newBlock("buscador_visible");
                            $cantidad = 0;
                             

                            foreach ($_SESSION['lotes'] as $key => $value) {
                            $vueltas = 0;
                            //foreach ($_SESSION["lote_local"] as $key => $value) {
                             

                                //foreach ($value as $key2 => $value2) {
                                $cantidad = $cantidad + 1;
                                

                                $art = $value->getId_art_conjunto()->getId_articulo()->getNombre();

                               
                                $marca = $value->getId_art_conjunto()->getId_marca()->getNombre();
                                $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
                                $nombre_ = $art.', '.$marca.', '.$tipo;
                                /*$si_arra = $value->getId_gc()->getId_categoria();
                               
                                
                                */
                                
                                if (True) {
                                    # code...
                                
                                    
                                    //Para el modal galery
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

                                    $cantodad_final_lote_local = $value->getCantidad().'  (Total)';
                                    $cantodad_final_lote_local .= '<br>';
                                    $contadori = 0;
                                    
                                    foreach ($nom_local__ as $key4 => $value4) {
                                        $cantodad_final_lote_local .= $cantidad_parcial_local__[$contadori].'  ('.$value4.')';
                                        $cantodad_final_lote_local .= '<br>';
                                        $contadori = $contadori + 1;
                                       
                                    }
                                    
                                    $tpl->assign("cantidad_total",$cantodad_final_lote_local);

                                    $gc = $value->getId_gc()->getId_categoria();
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

                                    if ($precio_base != null) {
                                        $tpl->assign("precio_base",'$'.$precio_base);
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
                                    $tpl->newBlock("modal_actualizar_precio");
                                    $tpl->assign("id_art_lote",$value->getId_lote());
                                    $tpl->newBlock("actualiza_precio_boton");
                                    $tpl->assign("id_art_lote",$value->getId_lote());
                                    
                                    
                                    $tpl->newBlock("actualiza_precio_base");
                                    $tpl->assign("precio_base_",$precio_base);

                                    $tpl->newBlock("actualiza_precio_tarjeta");
                                    $tpl->assign("precio_tarjeta_",$por_ciento_t);

                                    $tpl->newBlock("actualiza_precio_credito");
                                    $tpl->assign("precio_credito_",$por_ciento_p);


                                   
                                  
                                
                                }
                               
                            }
                            //Lote de Articulos por Local
                            //$_SESSION["lote_local"] 
                            //Lote de Articulos en general
                            //$_SESSION["lotes"]  
                            
                            
                            
                                
                               
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
            /*
        [seconds] => 40
        [minutes] => 58
        [hours]   => 21
        [mday]    => 17
        [wday]    => 2
        [mon]     => 6
        [year]    => 2003
        [yday]    => 167
        [weekday] => Tuesday
        [month]   => June
        [0]       => 1055901520
        $hoy = getdate();
        */
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

                                    $gc = $value->getId_gc()->getId_categoria();
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

                                    if ($precio_base != null) {
                                        $tpl->assign("precio_base",'$'.$precio_base);
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

                $list_art_grupo_att = art_grupo_categoria::obtener_categoriast();
                
                if ($list_art_grupo_att) {

                     foreach ($list_art_grupo_att as $key => $value) {
                        
                        $cate =  $value->getNombre();
                        $des =  $value->getDescripcion();
                        
                        if (strcmp($cate, "Precio" ) == 0 && strcmp($des, 'null' ) != 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_precio_base");
                            
                        }
                        if (strcmp($cate, "Tarjeta" ) == 0 && strcmp($des, 'null' ) != 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_precio_tarjeta");
                            
                        }

                         if (strcmp($cate, "CreditoP" ) == 0 && strcmp($des, 'null' ) != 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_precio_credito_argentino");
                            
                        }

                        if (strcmp($cate, "Medida" ) == 0 && strcmp($des, "null" ) != 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_medida");
                            
                        }

                        if (strcmp($cate, "Color" ) == 0 && strcmp($des, 'null' ) != 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_color");
                            break;
                            
                        }
                       
                       /* $tpl->assign("des_cat", $value->getDescripcion());
                        $tpl->assign("cat_name_input", $value->getNombre());
                        if ($value->getNombre() == 'Precio') {
                            $tpl->assign("tipo_art_input", 'number');
                        }

                        if ($value->getNombre() == 'Medida') {
                            $tpl->assign("tipo_art_input", 'text');
                        }*/
                        
                        
                        /*$tpl->assign("tipo_articulo", $value->getNombre());*/
                     }
                }
                else{
                    $tpl->newBlock("sin_articulo_grupo");
                    
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

                        
                        //art_carga_local_fecha_{id_art_local} para rescatar el valor del input echa
                        //art_local_cantidad_{id_art_local}
                        $tpl->newBlock("locales_alta");
                        $tpl->assign("id_local_", $value->getId_local());
                        //$tpl->assign("id_local_", $value->getId_local());
                        $tpl->assign("id_art_local1", $value->getId_local());
                        $tpl->assign("id_art_local", $value->getId_local());
                        $tpl->assign("nombre_local", $value->getNombre());
                        
                        $tpl->assign("nombre_local_art_2", str_replace(' ','',$value->getNombre()));
                        $tpl->assign("nombre_local_art", str_replace(' ','',$value->getNombre()));
                                
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

        $art_color =$_POST['art_color'];
        $art_proveedor = $_POST['art_prvd'];

        if (    $art_general == null || $art_marca == null || $art_tipo == null
                 ||  $art_precio_base == null || $art_precio_tarjeta == null || $art_precio_cp == null ) {
            $datos_no_recibidos = true;
           
            
            

            return Ingreso_Controller::salir();
            
        }
        
        $total_locales = art_local::obtener_locales_usuario_operador();

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
                if ($total_locales[$i]['id_zona'] != null) {
                    $id_local_oper = art_local::obtener_id_local($total_locales[$i]['id_zona']);
                    $name_local_cantidad = "art_local_cantidad_".$id_local_oper;
                    $name_local_fecha = "art_carga_local_fecha_".$id_local_oper;
                    
                    if (strcmp($campo, $name_local_cantidad ) == 0) {

                        //if ($_POST[$name_local_fecha] != null) {
                            $lista_art_locales[]=["Id" => $id_local_oper,"Cantidad" => $_POST[$name_local_cantidad],"Fecha" => $_POST[$name_local_fecha]];
                            $art_cantidad_total = $art_cantidad_total + $_POST[$name_local_cantidad];
                         
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
        if ($art_prvd != null || $art_prvd != ' ') {
            $id_proveedor = $art_prvd;
        }
        else{
            $id_proveedor = null;
        }
        //cargar codigo de barras
        if ($art_cb == null) {
            $id_cb = '000000000001';
            
           
        }else{
            
        }
        $id_cb = art_codigo_barra::alta_art_cb($art_cb);

        //cargar art_categorias y art_grupo_categoria
        
        $list_art_grupo_att = art_grupo_categoria::obtener_categoriast();

            foreach ($list_art_grupo_att as $key => $value) {
                        
                $nombre =  $value->getNombre();
                
                if (strcmp($nombre, "Precio" ) == 0) {
                 
                    $id_categoria_precio = art_categoria::alta_art_categoria($nombre,$art_precio_base,"En moneda local");
                     
                    //Como precio entra primero, lo usamos como bandera para el id del grupo de categorias
                    $id_gc = art_grupo_categoria::alta_art_gc($id_categoria_precio);

                }


                if (strcmp($nombre, "Medida" ) ==  0) {
                 
                    $id_categoria_medida = art_categoria::alta_art_categoria($nombre,$art_medida);
                    

                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_medida);
                    if ($ok) {
                        $nook = false;
                    }
                    
                }

                if (strcmp($nombre, "Tarjeta" ) ==  0) {
                     
                    $id_categoria_tarjeta = art_categoria::alta_art_categoria($nombre,trim($art_precio_tarjeta,'%'),"En porcentaje");
                    

                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_tarjeta);
                    if ($ok) {
                        $nook = false;
                    }
                }

                if (strcmp($nombre, "CreditoP" ) ==  0) {
                 
                     $id_categoria_creditop = art_categoria::alta_art_categoria($nombre,trim($art_precio_cp,'%'),"En porcentaje");

                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_creditop);
                    if ($ok) {
                        $nook = false;
                    }
                }

                if (strcmp($nombre, "Color" ) ==  0 ) {
                 
                
                    $id_categoria_color = art_categoria::alta_art_categoria($nombre,$art_color,'Color escrito');
                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_color);
                    if ($ok) {
                        $nook = false;
                    }
                    break;
                }

            

            }

       
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
        //agregar a Lote
     
        if ($id_proveedor != null) {
            $id_lote = art_lote::alta_art_lote($id_conjunto, $art_cantidad_total, $id_cb, $id_gc,$id_art_fotos, $id_proveedor);
        }else{
            $id_lote = art_lote::alta_art_lote($id_conjunto, $art_cantidad_total, $id_cb, $id_gc,$id_art_fotos);
        }   
        

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
        
        if ($ok && $ok2 || $datos_no_recibidos = false) {
            $tpl = new TemplatePower("template/exito.html");
            $tpl->prepare();
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
                for ($i=1; $i <= 12 ; $i++) { 
                    $tpl->newBlock("forma_pago_venta_opciones");
                    $tpl->assign("valor_pago", $i . ' x '.' $'.round($precio_tarjeta/$i,2));
                    $tpl->assign("id_cat_gc_art_vendido",$value->getId_categoria());
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
        $medio_pago = art_venta_medio::obtener_medios($id_usuario);
       
        foreach ($medio_pago as $key6 => $value6) {
            $muestra = false;
            
            /*$dias_fin = '';
            $dias1 = str_replace('1','Lunes',$dias);
                    
            $dias2 = str_replace('2','Martes',$dias1);
                     
            $dias3 = str_replace('3','Miercoles',$dias2);
                     
            $dias4 = str_replace('4','Jueves',$dias3);
                     
            $dias5 = str_replace('5','Viernes',$dias4);
                    
            $dias6 = str_replace('6','Sabado',$dias5);
                    
            $dias7 = str_replace('7','Domingo',$dias6);

                    

            $dias_fin = $dias1.','.$dias2.','.$dias3.','.$dias4.','.$dias5.','.$dias6.','.$dias7;
            $dias_fin2 = str_replace('&',' ',$dias7);*7
                
                /*
        [seconds] => 40
        [minutes] => 58
        [hours]   => 21
        [mday]    => 17
        [wday]    => 2
        [mon]     => 6
        [year]    => 2003
        [yday]    => 167
        [weekday] => Tuesday
        [month]   => June
        [0]       => 1055901520
        
        */
        
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

       

        if ($total == 'null' ||  $medio == null) {
            $bandera = true;
        }
        $tpl = new TemplatePower("template/exito_fracaso_venta.html");
        $tpl->prepare();

        $hoy = getdate();
        $fecha_venta = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];

        $id_usuario = $_SESSION["usuario"]->getId_user();
        
        //alta en art_venta

        $id_venta = art_venta::alta_art_venta($fecha_venta,$id_usuario,$medio,$total,$cuotas);
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

        public static function actualiza_precio(){
            $id_lote = $_GET['id_art_lote'];
            $precio_base = $_POST['art_precio_base'];
            $precio_tarjeta = $_POST['art_precio_tarjeta'];
            $precio_credito = $_POST['art_precio_credito_argentino'];

            $lote = art_lote::generar_lote($id_lote);
            $id_gc = $lote->getId_gc();
            foreach ($id_gc->getId_categoria() as $key => $valor) {
                
                if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                    $id_precio = $valor->getId_categoria();
                }
                if (strcmp($valor->getNombre(), "Tarjeta" ) == 0 ) {
                    $id_tarjeta = $valor->getId_categoria();
                }
                if (strcmp($valor->getNombre(), "CreditoP" ) == 0 ) {
                    $id_creditop = $valor->getId_categoria();
                }
            }
            
            $ok_precio_base = art_categoria::update_valores($id_precio,$precio_base);
            $ok_precio_tarjeta = art_categoria::update_valores($id_tarjeta,$precio_tarjeta);
            $ok_precio_credito = art_categoria::update_valores($id_creditop,$precio_credito);

            if ($ok_precio_base && $ok_precio_tarjeta && $ok_precio_credito) {
                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
            }else{
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
            }
            return $tpl->getOutputContent();
        }


        public static function modificar_venta(){
            $id_venta = $_GET['id_venta'];
            $id_lote_local = $_GET['id_lote_local'];
            $venta =  art_venta::generar_venta($id_venta);
            $lote_local_vendido_ = art_lote_local::generar_lote_local($id_lote_local);
            $lote_local_vendido = $lote_local_vendido_[0];
             
            $art_nombre = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
            $art_marca =  $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
            $art_tipo = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
            $nombre_completo_art =(string)$art_nombre.' ,'.$art_marca.' ,'.$art_tipo;

            $nombre_local = $lote_local_vendido->getId_local()->getNombre();
            $gc = $lote_local_vendido->getId_lote()->getId_gc()->getId_categoria();
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
                        $nombre_completo_art_2 =(string)$art_nombre_2.' ,'.$art_marca_2.' ,'.$art_tipo_2;

                
                        $nombre_local = $value->getId_local()->getNombre();
                   
                        $gc = $value->getId_lote()->getId_gc()->getId_categoria();
                        foreach ($gc as $clave => $valor) {
                

                            if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                                $precio_base_vender = $valor->getValor();
                                            
                                }
                
                        }

                        $tpl->assign("nombre_articulo",$nombre_completo_art_2.' ('.$nombre_local.' $'.$precio_base_vender.')');

                        $art_nombre_2 = '';
                        $art_marca_2 = '';
                        $art_tipo_2= '';
                        $nombre_completo_art_2 = '';
                        }
                    
                    }
                
                  
            }


                            
            return $tpl->getOutputContent();
            
        }


        public static function confirmar_venta(){
            $id_venta = $_GET['id_venta'];
            $id_lote_local_vendido = $_GET['id_lote_local'];
            $id_lote_local_cambio = $_POST['art_venta_cambio'];
            $venta =  art_venta::generar_venta($id_venta);

            $lote_local_cambiar = art_lote_local::generar_lote_local($id_lote_local_cambio);

            $lote_local_vendido_ = art_lote_local::generar_lote_local($id_lote_local_vendido);
            $lote_local_vendido = $lote_local_vendido_[0];
             
            $art_nombre = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
            $art_marca =  $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
            $art_tipo = $lote_local_vendido->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
            $nombre_completo_art =(string)$art_nombre.' ,'.$art_marca.' ,'.$art_tipo;
            $gc = $lote_local_vendido->getId_lote()->getId_gc()->getId_categoria();
                foreach ($gc as $clave => $valor) {
                if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                    $precio_base_vender = $valor->getValor();
                                            
                    }
                
            }
          
            $nombre_local = $lote_local_vendido->getId_local()->getNombre();
            $nombre_medio = $venta->getMedio()->getNombre();
            $descuento = $venta->getMedio()->getDescuento();
            $precio_vendido = $venta->getTotal();
            $fecha_venta = $venta->getFecha_hora();
            $usuario = $venta->getId_usuario()->getUsuario();


            $lote_local_cambiar_ = $lote_local_cambiar[0];
            $art_nombre2 = $lote_local_cambiar_->getId_lote()->getId_art_conjunto()->getId_articulo()->getNombre();
            $art_marca2 =  $lote_local_cambiar_->getId_lote()->getId_art_conjunto()->getId_marca()->getNombre();
            $art_tipo2 = $lote_local_cambiar_->getId_lote()->getId_art_conjunto()->getId_tipo()->getNombre();
            $nombre_completo_art2 =(string)$art_nombre2.' ,'.$art_marca2.' ,'.$art_tipo2;
            $gc2 = $lote_local_cambiar_->getId_lote()->getId_gc()->getId_categoria();
                foreach ($gc2 as $clave2 => $valor2) {
                if (strcmp($valor2->getNombre(), "Precio" ) == 0 ) {
                    $precio_base_vender2 = $valor2->getValor();
                                            
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

        public static function cargar_art_tipo(){

        $nombre = ucwords(strtolower($_POST['art_tipo']));
       

        }

}

?>