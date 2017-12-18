<?php
class Gasto_Controller{


	public static function menu (){
        
       $tpl = new TemplatePower("template/seccion_admin_gasto.html");
                $tpl->prepare();
                if (Ingreso_Controller::admin_ok()) {
                        
                    

                        # code...
                        $id_user_admin = $_SESSION['usuario']->getId_user();
                        
                        $gastos = us_gastos::obtener($id_user_admin);
                        $i = 1;
                        
                        if ($gastos) {
                        # code...
                            
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
                        echo "No Posee Permisos";
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
                 
                    if ($gs_des) {
                    # code...
                        
                        $tpl->newBlock("gasto_descripcion");
                        $tpl->assign("valor_id_gasto_des", $gs_des['id_gs_des']);
                        $tpl->assign("nombre_gasto_des", $gs_des['nombre']);

                    }
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

                foreach ($detalles as $key => $value) {
                    # code...
                    $gasto_unico = $value;
                    $fecha_gasto = strtotime($gasto_unico->getFecha_hora());
                    if ($fecha_desde_ < $fecha_gasto && $fecha_hasta_ > $fecha_gasto) {
                        # code...
                    
                        $tpl->newBlock("con_detalles_gasto_lista_cuerpo");
                        $tpl->assign("numero", $i);
                        $tpl->assign("nombre", $gasto_unico->getNombre());
                        $tpl->assign("valor", '$'.$gasto_unico->getValor());
                        $tpl->assign("fecha_hora", $gasto_unico->getFecha_hora());

                        if ($gasto_unico->getId_gsub_gasto() == null) {
                        # code...
                            $tpl->assign("sub_gasto", 'No Posee');
                        }
                        else{
                            $tpl->assign("sub_gasto", 'Si posee hay q listar');
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

                   

                        $i = $i + 1;
                    }
                }
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

}
?>