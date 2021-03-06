<?php
class Venta_Controller{

	public static function mostrar_parametros_medio(){
     
        
        $tpl = new TemplatePower("template/seccion_admin_parametros_ventas_medio.html");
        $tpl->prepare();

        if (Ingreso_Controller::admin_ok()) {
        	//code
           
         $medios = us_medio_pago::obtener($_SESSION["usuario"]->getId_user());

         if ($medios && count($medios)) {
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
                $tpl->assign("tipo", $value->getId_medio_tipo()->getNombre());
                $tpl->assign("desimp",'('.$value->getDesImp()->getSigno().')'.'%'.$value->getDesImp()->getValor());
                if ($value->getId_medio_fechas() != null) {
                        # code...
                    $fecha_ini = $value->getId_medio_fechas()->getFecha_hora_inicio();
                    $fecha_fini = $value->getId_medio_fechas()->getFecha_hora_fin();
                    $fecha_conjunta = $fecha_ini.'<->'.$fecha_fini;
                }
                else{
                    $fecha_conjunta = 'Siempre';
                }
                
                if ($fecha_fini == '0000-00-00' || $fecha_ini == 'fecha_ini') {
                        # code...
                    $tpl->assign("fechas",'Sin Definir');
                }else{
                    
                    $tpl->assign("fechas",$fecha_conjunta);
                }
                
                
                $dias =$value->getId_medio_dias()->getDias();
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
                $tpl->assign("id_medio",$value->getId());

                
                
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
     $medios = us_medio_pago::obtener($_SESSION["usuario"]->getId_user());
     $medios_pago_tipos = art_venta_medio_tipo::obtener();

     if (count($medios_pago_tipos)) {
                # code...
        
        foreach ($medios_pago_tipos as $key => $value) {
                # code...
            
            $tpl->newBlock("cargar_descr_medio");
            $tpl->assign("id_medio_descripcion",$value->getId());
            $tpl->assign("nombre_descripcion",$value->getNombre());
        }
    }

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
       $nombre = str_replace(",", "-", $nombre);
       $medio_tipo = $_POST['venta_medio_parametro_descripcion'];

       $valor_desimp = $_POST['venta_medio_parametro_desimp'];
       $signo_desimp = $_POST['venta_tipo_medio_desimp_signo'];

       $fecha_desde = $_POST['venta_medio_parametro_fecha_inicio'];
       $fecha_hasta = $_POST['venta_medio_parametro_fecha_fin'];
            //1->Lunes 2->Martes 3->Miercoles...
       $dias = $_POST['venta_medio_parametro_dias_'];


       

       if (is_numeric($medio_tipo)) {
        $id_medio_tipo = $medio_tipo;
    }else{
                //alta descripcion
        $id_medio_tipo = art_venta_medio_tipo::alta($medio_tipo);
    }

    if ($fecha_hasta == null || $fecha_desde == null) {
                 # code...
        $id_fechas_medio = null;
    }
    else{
                //Alta en art_venta_medio_fecha
        $id_fechas_medio = art_venta_medio_promo_fechas::alta($fecha_desde,$fecha_hasta);
    }
    
            //Alta en art_venta_medio_dias
    $dias_final = '';

            //Alta desimp
    $id_des_imp = art_venta_des_imp::alta($valor_desimp,$signo_desimp);

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

    $id_dias_medio = art_venta_medio_promo_dias::alta($dias_final);
    
                # code...
    $id_usuario = $_SESSION["usuario"]->getId_user();

    if ($id_fechas_medio == null) {
                # code...

        
                $id_medio_pago = art_venta_medio_pago::alta($nombre,$id_medio_tipo,$id_des_imp,$id_dias_medio); //,$id_gart_aplica = null
            }
            else{
             
                $id_medio_pago = art_venta_medio_pago::alta($nombre,$id_medio_tipo,$id_des_imp,$id_dias_medio,$id_fechas_medio); //,$id_gart_aplica = null
            }

            if ($id_medio_pago) {
                # code...
                //Alta en us_medio_pago
                $id_us_medio_pago = us_medio_pago::alta($id_usuario,$id_medio_pago);
            }

            if ($id_us_medio_pago) {
                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
                $tpl->newBlock("alta_medio_pago_exito");
                
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
        $id_venta_medio = $_GET['id_medio'];
        $medios = us_medio_pago::obtener($_SESSION["usuario"]->getId_user());
        $si_modifiacr = false;
        foreach ($medios as $key2 => $value2) {
                # code...
            if ($value2->getId() == $id_venta_medio) {
                    # code...
                $si_modifiacr = true;
                break;
            }

        }
        if (Ingreso_Controller::admin_ok() && $si_modifiacr) {
           
            

            $medio_pago = art_venta_medio_pago::generar($id_venta_medio);
            $tpl = new TemplatePower("template/modificar_venta_parametro_medio.html");
            $tpl->prepare();
            $tpl->assign("id_venta_medio", $id_venta_medio);

            $tpl->assign("nombre", $medio_pago->getNombre());
            $medio_pago_tipos = art_venta_medio_tipo::obtener();
            
            foreach ($medio_pago_tipos as $key => $value) {
                # code...
                
                $tpl->newBlock("cargar_descr_medio");
                $tpl->assign("id_medio_descripcion",$value->getId());
                $tpl->assign("nombre_descripcion",$value->getNombre());
            }

            $tpl->newBlock("modificar_dias_parametros_medio");
            //$tpl->assign("descripcion", $venta->getDescripcion());
            $tpl->assign("desimp", $medio_pago->getDesImp()->getValor());
            

            if ($medio_pago->getId_medio_fechas() != null) {
                # code...
                $tpl->assign("fecha_desde", $medio_pago->getId_medio_fechas()->getFecha_hora_inicio());
                $tpl->assign("fecha_hasta", $medio_pago->getId_medio_fechas()->getFecha_hora_fin());
            }
            
            
            $dias_ = $medio_pago->getId_medio_dias()->getDias();
            
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
            $art_venta_medio = art_venta_medio_pago::generar($id_venta_medio);
            $error = false;
            $nombre = $_POST['venta_medio_parametro_nombre'];
            $descripcion = $_POST['venta_medio_parametro_descripcion'];
            $descuento = $_POST['venta_medio_parametro_descuento'];
            $fecha_desde = $_POST['venta_medio_parametro_fecha_inicio'];
            $fecha_hasta = $_POST['venta_medio_parametro_fecha_fin'];
            //1->Lunes 2->Martes 3->Miercoles...
            $dias = $_POST['venta_medio_parametro_dias_'];
            if (is_numeric($descripcion)) {
                $id_descripcion = $descripcion;
            }else{
                //alta descripcion
                $id_descripcion = art_venta_medio_descripcion::alta_art_venta_medio_descripcion($descripcion);
            }
            //Update Nombre Descripcion y Descuento
            $update_nombre = art_venta_medio::update_nombre($id_venta_medio,$nombre);
            $update_descuento = art_venta_medio::update_descuento($id_venta_medio,$descuento);
            $update_descripcion = art_venta_medio::update_descripcion($id_venta_medio,$id_descripcion);
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

    public static function cargar_ventas_antiguas(){
        if (Ingreso_Controller::admin_ok()) {
            $tpl = new TemplatePower("template/cargar_art_ventas_antiguas.html");
            $tpl->prepare();
            $_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user());
            if (isset($_SESSION["locales"])) {
               
                foreach ($_SESSION["locales"] as $key => $value) {
                    $tpl->newBlock("lista_locales");
                    $tpl->assign("id_local",$value->getId_local());
                    $tpl->assign("nombre_local",$value->getNombre());
                }
                $medios_pago = art_venta_medio_pago::obtener($_SESSION['usuario']->getId_user());
                foreach ($medios_pago as $key3 => $value3) {
                    $tpl->newBlock("art_venta_medio_pago");
                    $tpl->assign("id_medio_pago",$value3->getId());
                    $tpl->assign("nombre_medio_pago",$value3->getNombre() );
                }   
            }
            else
            {
                $tpl->newBlock("sin_art_lote_local");
            }
        }
        else{
            return Ingreso_Controller::salir();
        }
        
        return $tpl->getOutputContent();
    }


    public static function alta_ventas_antiguas(){
        if (Ingreso_Controller::es_admin()) {
            $bandera = false;

            $fecha_venta_antigua = $_POST['fecha_venta_antigua'];
            $medio_pago_venta_antigua = $_POST['medio_pago_venta_antigua'];
            $local_venta_antigua = $_POST['local_venta_antigua'];

            $art_comprobante = $_POST['art_comprobante'];
            //$art_comprobante = 99999;
            $art_articulo = $_POST['art_articulo'];
            $art_total = $_POST['art_total'];
            $rg_detalle = array();
            
            //parse_str($art_comprobante[0], $output_comprobante2);
            //print_r(key($output_comprobante2));
            $id_usuario = $_SESSION['usuario']->getId_user();
            
            for ($i=0; $i < count($art_articulo); $i++) { 
                //
                if (!( $art_comprobante[$i] == 0 OR $art_articulo[$i] == 0 OR $art_total[$i] == 0)) {
                    
                    $art_comprobante_val = "$art_comprobante[$i]";
                    //$art_comprobante_val = "99999";
                    $art_articulo_val = "$art_articulo[$i]";
                    $art_total_val = "$art_total[$i]";
                    $local_venta_antigua_val = "$local_venta_antigua";
                    
                    $rg_detalle[] = $art_comprobante_val.','.$art_articulo_val.','.$art_total_val.','.$local_venta_antigua_val.','.$id_usuario;
                }
                
            }
            

            $medio_pago = art_venta_medio_pago::generar($medio_pago_venta_antigua);
            $nombre = $medio_pago->getNombre();
            $des_imp_valor = $medio_pago->getDesImp()->getValor();
            $des_imp_signo = $medio_pago->getDesImp()->getSigno();
            $rg_detalle_mp = $nombre.','.'('.$des_imp_signo.' '.$des_imp_valor.')';
            

            $id_gmedio_pago = art_gmedio_pago::alta_2($medio_pago_venta_antigua,$rg_detalle_mp);

            if ($id_gmedio_pago) {
               


                for ($i=0; $i < count($rg_detalle) ; $i++) { 
                    $valores = explode (",", $rg_detalle[$i]); 
                    $total = $valores[2];

                    $cuotas = '1 x '.$total;
                    $okok = art_venta::alta($fecha_venta_antigua,$id_usuario,$id_gmedio_pago,$total,$cuotas,'null','null',$rg_detalle[$i]);
                    
                }
                
            }else{
                $okok = false;
            }

            if ($okok) {
                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
                $tpl->newBlock("alta_ventas_antiguas");
                
                
            }
            else{

                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();
            }
            
        }
        else{
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
        

        return $tpl->getOutputContent();
    }
}

?>
