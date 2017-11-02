<?php
class Venta_Controller{

	public static function mostrar_parametros_medio(){
               
                
        $tpl = new TemplatePower("template/seccion_admin_parametros_ventas_medio.html");
        $tpl->prepare();

        if (Ingreso_Controller::admin_ok()) {
        	//code
           $medios = art_venta_medio::obtener_medios();

           if ($medios) {
               # code...

                $numero = 1;
                $tpl->newBlock("con_venta_medios");
                $tpl->newBlock("buscador_visible");
                foreach ($medios as $key => $value) {
                    # code...
                     
                    $tpl->newBlock("con_venta_medios_lista_cuerpo");
                    $tpl->assign("numero", $numero);
                    $numero = $numero + 1;
                    $tpl->assign("nombre", $value->getNombre());
                    $tpl->assign("descripcion", $value->getDescripcion());
                    $tpl->assign("descuento", '%'.$value->getDescuento());
                    $fecha_ini = $value->getId_fechas_medio()->getFecha_hora_inicio();
                    $fecha_fini = $value->getId_fechas_medio()->getFecha_hora_fin();
                    $fecha_conjunta = $fecha_ini.'<->'.$fecha_fini;
                    $tpl->assign("fechas",$fecha_conjunta);
                   
                    $dias =$value->getId_dias_medio()->getDias();
                    $dias_fin = '';
                    $dias1 = str_replace('1','Lunes',$dias);
                    
                    $dias2 = str_replace('2','Martes',$dias1);
                     
                    $dias3 = str_replace('3','Miercoles',$dias2);
                     
                    $dias4 = str_replace('4','Jueves',$dias3);
                     
                    $dias5 = str_replace('5','Viernes',$dias4);
                    
                    $dias6 = str_replace('6','Sabado',$dias5);
                    
                    $dias7 = str_replace('7','Domingo',$dias6);

                    

                    $dias_fin = $dias1.','.$dias2.','.$dias3.','.$dias4.','.$dias5.','.$dias6.','.$dias7;
                    $dias_fin2 = str_replace('&',' ',$dias7);
                    
                   
                    $tpl->assign("dias",$dias_fin2);

                    $dias_fin = ' ';
                    $dias1 = ' ';
                    $dias2 = ' ';
                    $dias3 = ' ';
                    $dias4 = ' ';
                    $dias5 = ' ';
                    $dias6 = ' ';
                    $dias7 = ' ';
                    $dias_fin2 = ' ';
                    $tpl->assign("id_medio",$value->getId_medio());

                    
                    
                }
                

           }else{
                $tpl->newBlock("sin_venta_medios");
           }

        }
        else{
            return Ingreso_Controller::salir();
        }
        return $tpl->getOutputContent();
    }

 
	public static function alta_parametros_medio(){
		if (Ingreso_Controller::admin_ok()) {

			$nombre = $_POST['actualiza_nombre_local'];
        	$tpl = new TemplatePower("template/cargar_parametros_ventas_medio.html");
        	$tpl->prepare();

        }
        else{
            return Ingreso_Controller::salir();
        }
               
                
       
        return $tpl->getOutputContent();
    }

    public static function confirmar_alta_parametros_medio(){
        if (Ingreso_Controller::admin_ok()) {
        	//code
        	$error = false;
            $nombre = $_POST['venta_medio_parametro_nombre'];
            $descripcion = $_POST['venta_medio_parametro_descripcion'];
            $descuento = $_POST['venta_medio_parametro_descuento'];
            $fecha_desde = $_POST['venta_medio_parametro_fecha_inicio'];
            $fecha_hasta = $_POST['venta_medio_parametro_fecha_fin'];
            //1->Lunes 2->Martes 3->Miercoles...
            $dias = $_POST['venta_medio_parametro_dias_'];

            //Alta en art_venta_medio_fecha
            $id_fechas_medio = art_venta_medio_fechas::alta_art_venta_medio_fechas($fecha_desde,$fecha_hasta);
            //Alta en art_venta_medio_dias
            $dias_final = '';
            $una_vez = true;
            foreach ($dias as $key => $value) {
                # code...
                if ($una_vez) {
                    $dias_final = $value;
                    $una_vez = false;
                    continue;
                }
                
                $dias_final = $dias_final.'&'.$value;

            }
            $id_dias_medio = art_venta_medio_dias::alta_art_venta_medio_dias($dias_final);
            if ($id_dias_medio && $id_fechas_medio) {
                # code...
                $id_usuario = $_SESSION["usuario"]->getId_user();
                $id_art_venta_medio = art_venta_medio::alta_art_venta_medio($nombre,$descripcion,$descuento,$id_fechas_medio,$id_dias_medio,$id_usuario);
                if ($id_art_venta_medio) {
                    $tpl = new TemplatePower("template/exito.html");
                    $tpl->prepare();
                }else{
                   
                    $error = true;
                }
                
            }else{
                $error = true;
            }
            if ($error) {
                # code...
              
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
            }

        }
        else{
            return Ingreso_Controller::salir();
        }
                
        return $tpl->getOutputContent();
    }

    public static function modificar_parametros(){
        $parametro = $_GET['parametro'];
        switch ($parametro) {
            case 1:
                # code...

                $tpl = Venta_Controller::modificar_parametros_medio();
                return $tpl->getOutputContent();
                break;
            
            default:
                # code...
                break;
        }
    }

    public static function modificar_parametros_medio(){
        if (Ingreso_Controller::admin_ok()) {
             
            $id_venta_medio = $_GET['id_medio'];
            $venta = art_venta_medio::generar_venta_medio($id_venta_medio);
            $tpl = new TemplatePower("template/modificar_venta_parametro_medio.html");
            $tpl->prepare();
            $tpl->assign("id_venta_medio", $id_venta_medio);

            $tpl->assign("nombre", $venta->getNombre());
            $tpl->assign("descripcion", $venta->getDescripcion());
            $tpl->assign("descuento", $venta->getDescuento());
            $tpl->assign("fecha_desde", $venta->getId_fechas_medio()->getFecha_hora_inicio());
            $tpl->assign("fecha_hasta", $venta->getId_fechas_medio()->getFecha_hora_fin());
            $dias_ = $venta->getId_dias_medio()->getDias();
            if (strpos($dias_, '1') !== false) {

                $tpl->assign("check_lunes",'checked' );
            }
            if (strpos($dias_, '2') !== false) {
                $tpl->assign("check_martes",'checked' );
            }
            if (strpos($dias_, '3') !== false) {
                $tpl->assign("check_miercoles",'checked' );
            }
            if (strpos($dias_, '4') !== false) {
                $tpl->assign("check_jueves",'checked' );
            }
            if (strpos($dias_, '5') !== false) {
                $tpl->assign("check_viernes",'checked' );
            }
            if (strpos($dias_, '6') !== false) {
                $tpl->assign("check_sabado",'checked' );
            }
            if (strpos($dias_, '7') !== false) {
                $tpl->assign("check_domingo",'checked' );
            }

            
        }
        else{
            return Ingreso_Controller::salir();
        }
               
                
       
        return $tpl->getOutputContent();
    }

    public static function confirmar_modificacion_parametros_medio(){
        if (Ingreso_Controller::admin_ok()) {
            //code
            $id_venta_medio = $_GET['id_venta_medio'];
            $art_venta_medio = art_venta_medio::generar_venta_medio($id_venta_medio);
            $error = false;
            $nombre = $_POST['venta_medio_parametro_nombre'];
            $descripcion = $_POST['venta_medio_parametro_descripcion'];
            $descuento = $_POST['venta_medio_parametro_descuento'];
            $fecha_desde = $_POST['venta_medio_parametro_fecha_inicio'];
            $fecha_hasta = $_POST['venta_medio_parametro_fecha_fin'];
            //1->Lunes 2->Martes 3->Miercoles...
            $dias = $_POST['venta_medio_parametro_dias_'];

            //Update Nombre Descripcion y Descuento
            $update_nombre = art_venta_medio::update_nombre($id_venta_medio,$nombre);
            $update_descuento = art_venta_medio::update_descuento($id_venta_medio,$descuento);
            $update_descripcion = art_venta_medio::update_descripcion($id_venta_medio,$descripcion);
            //Update en art_venta_medio_fecha
            $id_fecha_medio = $art_venta_medio->getId_fechas_medio()->getId_fechas_medio();
            $up_fechas_medio = art_venta_medio_fechas::update_fecha_desde($id_fecha_medio,$fecha_desde,$fecha_hasta);
            //Alta en art_venta_medio_dias
            $dias_final = '';
            $una_vez = true;
            foreach ($dias as $key => $value) {
                # code...
                if ($una_vez) {
                    $dias_final = $value;
                    $una_vez = false;
                    continue;
                }
                
                $dias_final = $dias_final.'&'.$value;

            }
            $id_dias_medio = $art_venta_medio->getId_dias_medio()->getId_dias_medio();
            $up_dias_medio = art_venta_medio_dias::update_dias($id_dias_medio,$dias_final);

            if ($up_dias_medio && $up_fechas_medio && $update_nombre && $update_descuento && $update_descripcion) {
                # code...
               
                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
                $tpl->newBlock("lista_parametros_venta");

                
                
            }else{
                $error = true;
            }
            if ($error) {
                # code...
              
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
                $tpl->newBlock("lista_parametros_venta");

            }

        }
        else{
            return Ingreso_Controller::salir();
        }
                
        return $tpl->getOutputContent();
    }
}

?>
                                