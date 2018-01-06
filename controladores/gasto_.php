<?php
class Gasto_Controller{


	public static function menu (){
        
       $tpl = new TemplatePower("template/seccion_admin_gasto.html");
                $tpl->prepare();
                if (Ingreso_Controller::admin_ok()) {
                        
                        $id_user_admin = $_SESSION['usuario']->getId_user();
                        
                        $gastos = us_gastos::obtener($id_user_admin);
                        $i = 1;
                        
                        if ($gastos) {
                            $tpl->newBlock("buscador_visible");
                            $tpl->newBlock("con_gastos_lista");
                            foreach ($gastos->getId_us_ggs() as $key => $value) {
                                # code...
                            $gasto = $value->getId_gasto();
                            //rint_r($gasto);
                            $tpl->newBlock("con_gastos_lista_cuerpo");
                            
                            $tpl->assign("numero", $i);
                            $tpl->assign("nombre", $gasto->getNombre());
                            $tpl->assign("tipo", $gasto->getId_gs_des()->getNombre());

                            if ($gasto->getHabilitado() == 1) {
                                # code...
                                $estado = "Habilitado";
                            }else{
                                $estado = "Deshabilitado";
                            }
                            $tpl->assign("estado", $estado);

                            
                            $tpl->newBlock("btn_agregar_detalle");
                            $tpl->assign("id_gasto", $gasto->getId_gasto());

                            $tpl->newBlock("btn_cambiar_estado");
                            $tpl->assign("id_gasto", $gasto->getId_gasto());

                            if ($gasto->getId_ggs() != null) {
                                # code...

                                $tpl->newBlock("btn_ver_detalle");
                                $tpl->assign("id_gasto", $gasto->getId_gasto());
                                
                            }
                            $nombre_gasto = $gasto->getNombre();
                            if (!(strcmp($nombre_gasto, 'Sueldos' ) == 0)) {
                                $tpl->newBlock("btn_borrar_gasto");
                                $tpl->assign("id_gasto", $gasto->getId_gasto());
                            }
                           
                            //Declara Modals
                            $tpl->newBlock("modal_ver_detalles");
                            $tpl->assign("id_gasto", $gasto->getId_gasto());
                            $tpl->assign("id_gasto_", $gasto->getId_gasto());
                            $tpl->newBlock("fechas_unico_valor_detalle_desde");
                            $tpl->assign("valor_unico", $i);
                            $tpl->assign("valor_unico_", $i);
                            $tpl->newBlock("fechas_unico_valor_detalle_hasta");
                            $tpl->assign("valor_unico", $i);
                            $tpl->assign("valor_unico_", $i);

                            $tpl->newBlock("modal_agrega_detalles");
                            $tpl->assign("id_gasto", $gasto->getId_gasto());
                            $tpl->assign("id_gasto_", $gasto->getId_gasto());

                            $tpl->newBlock("modal_cambiar_estado");
                            $tpl->assign("id_gasto", $gasto->getId_gasto());
                            $tpl->assign("id_gasto_", $gasto->getId_gasto());
                            
                            $i = $i + 1;
                            }


                        }else{

                            $tpl->newBlock("sin_gastos_lista");
                        }
                       

                     
                }
                else{
                         
                        return Ingreso_Controller::salir();
                }

                return $tpl->getOutputContent();
		

	}

    public static function cargar(){
        if (isset($_SESSION["usuario"])){
            if ($_SESSION["permiso"] == 'ADMIN') {
                $tpl = new TemplatePower("template/cargar_gasto.html");
                $tpl->prepare();
                    
                 if (count($_SESSION['locales']) != 0) {
                    $tpl->newBlock("con_locales_empl");
                    $gs_des = gs_descripcion::obtener();
                 
                    if (count($gs_des) >= 1) {
                    # code...
                      
                        foreach ($gs_des as $key => $value) {
                            # code...
                        
                            $tpl->newBlock("gasto_descripcion");
                            $tpl->assign("valor_id_gasto_des", $value->getId_gs_des());
                            $tpl->assign("nombre_gasto_des", $value->getNombre());
                        }

                    }/*else{
                        $tpl->newBlock("gasto_descripcion");
                        $tpl->assign("valor_id_gasto_des", $gs_des->getId_gs_des());
                        $tpl->assign("nombre_gasto_des", $gs_des->getNombre());
                    }*/
                }
                else{
               
                    $tpl->newBlock("sin_locales_empl_2");
                    $tpl->assign("mensaje_alerta", "No puede cargar gastos sin tener locales definidos.");
                }
                //Obtener gs_descripcion
                
               
                
            }
            
        }
        else{
            return Ingreso_Controller::salir();
        }

        return $tpl->getOutputContent();

    }

    public static function alta(){
        $gs_nombre = $_POST['gs_nombre'];
        $gs_descripcion = $_POST['gs_descripcion'];
        $gs_descripcion_des = $_POST['gs_des_'];
        $gs_unico_nombre = $_POST['gs_unico_nombre'];
        $gs_unico_valor = $_POST['gs_unico_valor'];
        $gs_unico_fechahora = $_POST['gs_unico_fechahora'];
        $gs_unico_des = $_POST['gs_unico_des'];
   
        if (Ingreso_Controller::admin_ok()) {

            $i = 0;
            
            //generar Gastos
            $array_gs = array();
           
            foreach ($gs_unico_nombre as $key => $value) {
                    $gs_unico_nombre_ = $gs_unico_nombre[$i];
                    $gs_unico_valor_ = $gs_unico_valor[$i];
                    $gs_unico_fechahora_ = $gs_unico_fechahora[$i];
                    $gs_unico_des_ = $gs_unico_des[$i];

                    $id_gasto_unico = gs_gasto_unico::alta($gs_unico_nombre_,$gs_unico_valor_,true,$gs_unico_fechahora_,$gs_unico_des_);
                  
                    $array_gs[] = $id_gasto_unico;
                    $i = $i +1;
            
                }

            $unica_vez = true;
            
            if (count($array_gs) >= 1) {
                # code...
            
            foreach ($array_gs as $key => $value) {
                # code...
                if ($value != null) {
                    # code...
                    
                    if ($unica_vez) {
                    # code...
                        $id_ggs = gs_grupo::alta($value);
                        $unica_vez = false;
                    }
                    else{
                        $okok = gs_grupo::agrega($id_ggs,$value);
                    }
                }

                /*if (!$okok) {
                    # code...
                    echo "ErrorCarga";
                    break;
                }*/
               
            }

            }
                # code...
                
                

            //Generar GS GASTO
            if (is_numeric($gs_descripcion)) {
                # code...
                $id_gasto = gs_gastos::alta($gs_nombre,$gs_descripcion,$id_ggs);
            }
            else{
                //Agregar nuevo Tipo
                $id_gs_des = gs_descripcion::alta($gs_descripcion,$gs_descripcion_des);
                $id_gasto = gs_gastos::alta($gs_nombre,$id_gs_des,$id_ggs);

            }
            

            $id_user_admin = $_SESSION['usuario']->getId_user();

            $id_us_ggs = us_gastos::obtener($id_user_admin);
            if ($id_us_ggs != null) {
           
                $ok_carga = us_ggs::agrega($id_us_ggs->getId_us_ggs()[0]->getId_us_ggs(),$id_gasto);

            }else{
              

                $id_us_ggs = us_ggs::alta($id_gasto,$id_user_admin);        
                    //Agregar a us_gasto
                $ok_carga = us_gastos::alta($id_user_admin,$id_us_ggs);
            }
            
            if ($ok_carga) {
                    # code...
                    $tpl = new TemplatePower("template/exito2.html");
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

    public static function alta_gs_unico(){

        $id_gasto = $_GET['id_gasto'];
        $id_ggs = $_POST['id_ggs'];

        $gs_unico_nombre = $_POST['gs_unico_nombre'];
        $gs_unico_valor = $_POST['gs_unico_valor'];
        $gs_unico_fechahora = $_POST['gs_unico_fechahora'];

        $gs_unico_des = $_POST['gs_unico_des'];
   
        if (Ingreso_Controller::admin_ok()) {

            $gasto = gs_gastos::generar_gasto($id_gasto);
            if (!(isset($id_ggs))) {
                # code...
                $id_ggs = $gasto->getId_ggs()->getId_ggs();
            }

            $i = 0;
            $array_gs = array();
            foreach ($gs_unico_nombre as $key => $value) {
                    $gs_unico_nombre_ = $gs_unico_nombre[$i];
                    $gs_unico_valor_ = $gs_unico_valor[$i];
                    $gs_unico_fechahora_ = $gs_unico_fechahora[$i];
                    $gs_unico_des_ = $gs_unico_des[$i];
                    

                    $id_gasto_unico = gs_gasto_unico::alta($gs_unico_nombre_,$gs_unico_valor_,true,$gs_unico_fechahora_,$gs_unico_des_);
                  
                    $array_gs[] = $id_gasto_unico;
                    $i = $i +1;
            
                }

                
                
            foreach ($array_gs as $key => $value) {
                # code...
                if ($value != null) {
                   
                    $okok = gs_grupo::agrega($id_ggs,$value);
                   
                }

            }


            if ($okok) {
                    # code...
                    $tpl = new TemplatePower("template/exito2.html");
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

    

    public static function cambiar_gs_estado(){

        $id_gasto = $_GET['id_gasto'];
        $estado = $_POST['gs_estado'];
        
   
        if (Ingreso_Controller::admin_ok()) {
            
            $okok = gs_gastos::update($id_gasto,'habilitado',$estado);
            if ($okok) {
                $tpl = new TemplatePower("template/exito2.html");
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

    
    public static function gs_mostrar_detalle(){

        $id_gasto = $_GET['id_gasto'];
        
       
   
        if (Ingreso_Controller::admin_ok()) {
            $gasto = gs_gastos::generar_gasto($id_gasto);

          
            //print_r($gasto);

            $fecha_desde = $_POST['fecha_desde'];
            $fecha_hasta = $_POST['fecha_hasta'];


            $fecha2 = explode(" ",$fecha_desde);
            $fecha3 = $fecha2[0];
            $fecha_desde_ = strtotime("$fecha3");

            $fecha2 = explode(" ",$fecha_hasta);
            $fecha3 = $fecha2[0];
            $fecha_hasta_ = strtotime("$fecha3");

            $tpl = new TemplatePower("template/seccion_admin_gasto_detalles.html");
            $tpl->prepare();
            $tpl->newBlock("modal_agrega_detalles");

            $tpl->assign("id_gasto_", $gasto->getId_gasto());
            $tpl->assign("id_ggs", $gasto->getId_ggs()->getId_ggs());

            $tpl->newBlock("btn_agregar_detalle");
            $tpl->newBlock("buscador_visible");

            $tpl->newBlock("datos_gastos");
            $tpl->assign("nombre_gasto", $gasto->getNombre());
            $tpl->assign("tipo_gasto", $gasto->getId_gs_des()->getNombre());
            $tpl->assign("fecha_desde", $fecha_desde);
            $tpl->assign("fecha_hasta", $fecha_hasta);

            //Recuperar detalles
            $detalles = $gasto->getId_ggs()->getId_gasto_unico();
          

            if ($detalles != null) {
                # code...

                $i = 1;
                $tpl->newBlock("con_detalles_gasto_lista");
                $total = 0;
                $total_ = 0;
                foreach ($detalles as $key => $value) {
                    # code...
                    $gasto_unico = $value;
                    $fecha_gasto = strtotime($gasto_unico->getFecha_hora());
                    if ($fecha_desde_ < $fecha_gasto && $fecha_hasta_ > $fecha_gasto) {
                        # code...
                    
                        $tpl->newBlock("con_detalles_gasto_lista_cuerpo");
                        $tpl->assign("numero", $i);

                        $tpl->assign("nombre", $gasto_unico->getNombre());
                        $sumar = 0;
                        $restar = 0;
                        if ($gasto_unico->getId_gsub_gasto() != null) {
                            # code...
                            $gsubgs = false;
                            $subgastos = $gasto_unico->getId_gsub_gasto()->getId_sub_gasto();

                            

                            foreach ($subgastos as $key => $value) {
                                # code...
                                $valor = $value->getValor();
                                $condicion = $value->getCondicion();
                                if (strcmp($condicion, "+") == 0) {
                                    # code...

                                    $sumar = $sumar + $valor;
                                }
                                if (strcmp($condicion, "-") == 0) {
                                    # code...

                                    $restar = $restar + $valor;
                                }
                            }

                        }else{
                            $gsubgs = true;

                        }
                        
                        $valor_finali = $gasto_unico->getValor() + $sumar - $restar;
                        $total_ = $total_ + $valor_finali;
                        $tpl->assign("valor", '$'.$valor_finali );

                        $tpl->assign("fecha_hora", $gasto_unico->getFecha_hora());

                        if ($gsubgs) {
                        # code...
                            $tpl->assign("sub_gasto", 'No Posee');
                        }
                        else{
                            $id_gs_unico = $gasto_unico->getId_gasto_unico();
                            $btn_subgastos = '<button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#ver'.$id_gs_unico.'">
                                Ver Subgasto
                                </button>';
                            $tpl->assign("sub_gasto", $btn_subgastos);
                        }
                    

                        if ($gasto_unico->getHabilitado() == 1) {
                        # code...
                            $tpl->assign("estado", 'Habilitado');
                        }
                        else{
                            $tpl->assign("estado", 'Desabilitado');
                        }

                        if ($gasto_unico->getDescripcion() != null) {
                        # code...
                            $tpl->assign("observaciones", $gasto_unico->getDescripcion());
                        }
                        else{
                            $tpl->assign("observaciones", 'Sin Comentarios');
                        }

                        $tpl->assign("id_gsunico_subgs", $gasto_unico->getId_gasto_unico());
                        
                        $tpl->newBlock("modal_agrega_subgasto");
                        $tpl->assign("id_gsunico_subgs", $gasto_unico->getId_gasto_unico());
                        $tpl->assign("id_gasto_", $gasto_unico->getId_gasto_unico());

                        $tpl->newBlock("modal_fecha");
                        $tpl->assign("id_gasto_unico_", $gasto_unico->getId_gasto_unico());
                        $tpl->assign("id_gasto_unico__", $gasto_unico->getId_gasto_unico());

                        if (!$gsubgs) {
                            //Modal de detalles de subgastos
                            $tpl->newBlock("modal_ver_subgasto");
                            $tpl->assign("id_gsunico_subgs", $gasto_unico->getId_gasto_unico());
                            $numero = 1;
                            
                            foreach ($subgastos as $key => $value) {
                                # code...
                                $valor = $value->getValor();
                                $condicion = $value->getCondicion();
                                $nombre = $value->getNombre();
                                $fecha_hora = $value->getFecha_hora();

                                $tpl->newBlock("movimiento_subgasto");
                                $tpl->assign("numero", $numero);
                                $tpl->assign("nombre", $nombre);
                                $tpl->assign("fecha_hora", $fecha_hora);
                                $tpl->assign("valor", $valor);

                                if (strcmp($condicion, "+") == 0) {
                                    $tpl->assign("condicion", 'Suma');
                                    $total = $total + $valor;

                                }
                                if (strcmp($condicion, "-") == 0) {
                                    $tpl->assign("condicion", 'Resta');
                                    $total = $total - $valor;
                                }
                                $numero = $numero + 1;

                            }
                            

                        }
                        $i = $i + 1;
                    }
                    
                }

                $tpl->newBlock("total_gastos");
                $tpl->assign("total", $total_);
            }
            else{
                $tpl->newBlock("sin_detalles_gasto_lista");
            }
           
           

           
           
            
        }
        else{
            return Ingreso_Controller::salir();
        }

        return $tpl->getOutputContent();       
    }

    public static function alta_gsunico_subgasto(){

        $id_gsunico = $_GET['id_gsunico'];
        $gssub_nombre = $_POST['gsunico_subgasto_nombre'];
        $gssub_valor = $_POST['gsunico_subgasto_valor'];
        $gssub_fechahora = $_POST['gsunico_subgasto_fechahora'];
        $gssub_condicion = $_POST['gsunico_subgasto_condicion'];
        $gssub_descripcion = $_POST['gsunico_subgasto_descripcion'];
   
        if (Ingreso_Controller::admin_ok()) {

            $gasto = gs_gasto_unico::generar($id_gsunico);
            
            $sub_gasto = gs_subgasto::alta($gssub_nombre,$gssub_valor,$gssub_descripcion,$gssub_fechahora,$gssub_condicion);

           

            if ($sub_gasto) {
                
                //Preguntar si existe un grupo de subgastos para $gasto
                
                $id_gsub_gasto = $gasto->getId_gsub_gasto();


                if ($id_gsub_gasto == null) {
                    //Generar gs_gsubgasto
                    
                    $id_gsub_gasto = gs_gsub_gasto::alta($sub_gasto);
                    //agregar al gasto unico
                    $okok = $gasto->update($id_gsunico,'id_gsub_gasto',$id_gsub_gasto);


                }else{
                    //Agregar al grupo existente
                    $id_gsub_gasto = $gasto->getId_gsub_gasto()->getId_gsub_gasto();
                    $okok = gs_gsub_gasto::agregar($id_gsub_gasto,$sub_gasto);

                }
                
                

            }

            if ($okok) {
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


public static function antes_baja_gasto(){

        if (Ingreso_Controller::admin_ok()) {

            $id_gasto = $_GET['id_gasto'];
            //Preguntar si este gasto pertenece al usuario
            $bandera = false;
            $gastos_user = us_gastos::obtener($_SESSION['usuario']->getId_user());
            $id_us_ggs = $gastos_user->getId_us_ggs()[0]->getId_us_ggs();
            $us_ggs = us_ggs::obtener($id_us_ggs);
           
            foreach ($us_ggs as $key1 => $value1) {
                
                $id_gasto_us = $value1->getId_gasto()->getId_gasto();
                
                if ($id_gasto_us == $id_gasto) {
                    $bandera = true;
                    break;
                }
            }
            
            if ($bandera) {
   
                $gasto = gs_gastos::generar_gasto($id_gasto);

                $tpl = new TemplatePower("template/baja_gasto.html");
                $tpl->prepare(); 
                $tpl->assign("nombre", $gasto->getNombre());
                $gs_tipo = $gasto->getId_gs_des()->getNombre();
                $tpl->assign("gs_des", $gs_tipo);
                if ($gasto->getHabilitado() == 1) {
                    $tpl->assign("estado",'Habilitado' );
                }else{
                    if ($gasto->getHabilitado() == 0) {
                        $tpl->assign("estado", 'Desabilitado');
                    }else
                    {
                        $tpl->assign("estado", 'Opps! Contacte por favor al administrador.');
                    }
                
                }
                $gs_unicos = $gasto->getId_ggs()->getId_gasto_unico();
                $tpl->assign("cant_detalles", count($gs_unicos));
                $tpl->assign("id_gasto", $id_gasto);
            }else{
            
                return Ingreso_Controller::salir();
            }
        }
        else{

            return Ingreso_Controller::salir();
        }
        
        return $tpl->getOutputContent();
    }

    public static function baja_gasto(){
        if (Ingreso_Controller::admin_ok()) {
            $id_gasto = $_POST['id_gasto'];
            $gasto = gs_gastos::generar_gasto($id_gasto);

            $nombre_gasto = $gasto->getNombre();
             
            if (!(strcmp($nombre_gasto, 'Sueldos' ) == 0)) {
                $id_us_ggs = us_ggs::obtener_usggs_idgs($id_gasto);
                $id_ggs = $gasto->getId_ggs()->getId_ggs();
                $gs_unicos = $gasto->getId_ggs()->getId_gasto_unico();

                $baja_us_ggs = us_ggs::baja($id_gasto);
                $baja_gs_gasto = gs_gastos::baja($id_gasto);
                $baja_gs_grupo = gs_grupo::baja($id_ggs);


                if ($baja_us_ggs && $baja_gs_gasto && $baja_gs_grupo) {
                    # code...
                
                    foreach ($gs_unicos as $key => $value) {
                        $id_gasto_unico = $value->getId_gasto_unico();

                        $bajaok = Gasto_Controller::baja_gasto_unico($id_gasto_unico);

                        if ($bajaok) {
                           // echo "OKok";
                        }else{
                            //echo "NoNo";
                        }
                    }
                }else{
                    //echo "acaError0";
                    $tpl = new TemplatePower("template/error.html");
                    $tpl->prepare();
                }

            }else{
                //echo "acaError";
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
            }

            if ($bajaok) {
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

    public static function baja_gasto_unico($id_gasto_unico){
        if (isset($_POST['id_gasto_unico'])) {
            $id_gasto_unico = $_POST['id_gasto_unico'];
        }
        $id_ggs = gs_grupo::obtener_ggs_idgsunico($id_gasto_unico);
        $gasto = gs_gasto_unico::generar($id_gasto_unico);
        $posee_sbgasto = false;
        if ($gasto->getId_gsub_gasto() != null) {
            $gastos_gsubgasto = $gasto->getId_gsub_gasto()->getId_sub_gasto();
            $posee_sbgasto = true;


        }
        
        $nombre_gasto = $gasto->getNombre();

        if (!(strcmp($nombre_gasto, 'Sueldos' ) == 0)) {

            if ( $posee_sbgasto) {
                if (count($gastos_gsubgasto) != 0 ) {
                    # code...
                    foreach ($gastos_gsubgasto as $key => $value) {
                        $id_gs_subgasto = $value->getId_sub_gasto();
                        $bajaok = Gasto_Controller::baja_gasto_unico_subgasto($id_gs_subgasto);
                        if ($bajaok) {
                        //echo "Borrao subgasto";
                        }else{
                            //echo "ERROR BORRADO SUBGASTO";
                        }
                    }
                }
            }else{
            //Borrar gs_gasto_unico
                 
                $bajaok = gs_gasto_unico::baja($id_gasto_unico);
                 
            }

            /*$us_gastos = us_gastos::obtener($_SESSION['usuario']->getId_user());
            $gastos = $us_gastos->getId_us_ggs()->getId_gasto();
            $gastos_gsgrupo = $gasto->getId_us_ggs()->getId_gasto_unico();
            if (count($gastos_gsgrupo) == 0) {
                //Si es necesario, borrar en tabla gs_gasto_unico
                $bajaok = gs_grupo::baja($gasto->getId_us_ggs());
                if ($bajaok) {
                    $bajaokok = gs_gastos::update($gastos->getId_gasto(),'id_ggs','null');
                }
            }*/

            }
        else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
       
        if ($bajaok) {
            $tpl = new TemplatePower("template/exito.html");
            $tpl->prepare();
        }else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
        return $tpl->getOutputContent();
    }

    public static function baja_gasto_unico_subgasto($id_gs_subgasto){
        if (isset($_POST['id_gs_subgasto'])) {
            $id_gs_subgasto = $_POST['id_gs_subgasto'];
        }

        
        //Borrar en tabla gs_subgasto
        $id_gsub_gasto = gs_gsub_gasto::obtener_gsubgasto_idsubgasto($id_gs_subgasto);

        $gs_dubgasto = gs_subgasto::generar($id_gs_subgasto);

        $valor_subgasto = $gs_dubgasto->getValor();
        $condicion_subgasto = $gs_dubgasto->getCondicion();

        $us_gastos = us_gastos::obtener($_SESSION['usuario']->getId_user());
        $gastos = $us_gastos->getId_us_ggs()->getId_gasto()->getId_us_ggs()->getId_gasto_unico();
        $nombre_gasto = $gasto->getNombre();

        if (!(strcmp($nombre_gasto, 'Sueldos' ) == 0)) {
            $bajaok = gs_subgastp::baja($id_gs_subgasto);
            if ($bajaok) {
            //Borrar en tabla gs_gsubgasto
                $bajaok = gs_gsub_gasto::baja($id_gsub_gasto);
            } 
            $gastos_gsubgasto = $gasto->getId_gsub_gasto()->getId_sub_gasto();
            if (count($gastos_gsubgasto) == 0) {
            //Si es necesario, borrar en tabla gs_gasto_unico
                $bajaok = gs_gasto_unico::update($gastos->getId_gasto_unico(),'id_sub_gasto','null');
            }
            
        }else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }

        if($bajaok) {
            //Actualizar valores en gasto unico

            $valor_gasto = $gastos->getValor();
            if ((strcmp($condicion_subgasto, '+' ) == 0)) {
                # code...
                $valor_actualizado = $valor_gasto - (int)$valor_subgasto;
            }else{
                if ((strcmp($condicion_subgasto, '-' ) == 0)) {
                    # code...
                    $valor_actualizado = $valor_gasto + (int)$valor_subgasto;
                }
            }
            $id_gs_unico = $gasto->getId_gasto_unico();
            $bajaok = gs_gasto::update($id_gs_unico,'valor',$valor_actualizado);
           
        }
        if ($bajaok) {
           
            $tpl = new TemplatePower("template/exito.html");
            $tpl->prepare();
        }else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
        return $tpl->getOutputContent();
    }

}
?>