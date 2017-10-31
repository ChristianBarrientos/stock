<?php
class Local_Controller{

        function mostrar(){
                $tpl = new TemplatePower("template/locales.html");
                $tpl->prepare();
                if (Ingreso_Controller::admin_ok()) {
                        
                        if ($_SESSION['usuario']->obtener_locales($_SESSION['usuario'])) {
                            foreach ($_SESSION['locales'] as $key => $value) {
                                $tpl->newBlock("con_locales");

                                $tpl->assign("nombre", htmlentities($value->getNombre(), ENT_QUOTES));
                                $tpl->assign("descripcion", htmlentities($value->getDescripcion(), ENT_QUOTES));
                                $tpl->assign("direccion", $value->getId_zona());
                                $tpl->assign("cantidad_empl", $value->getCantidad_empl() -1 );
                                $tpl->assign("id_local", $value->getId_local());
                                
                            }
                            //$tpl->newBlock("agregar_local");
                            
                                
                               
                        }
                        else{
                               $tpl->newBlock("sin_locales");
                        }
                }
                else{
                        return Ingreso_Controller::salir();
                }

                return $tpl->getOutputContent();

        }


	public static function cargar_local($error = 1){

        if (Ingreso_Controller::admin_ok()) {
                $paises = mp_zona::obtener_paises();
                $provincias = mp_zona::obtener_provincias();
                $localidades = mp_zona::obtener_localidades();
                
                if ($paises) {
                     $tpl = new TemplatePower("template/cargar_local.html");
                     $tpl->prepare();
                     if($error == 0){

                        $tpl->newBlock("error_zona");

                     }
                     
                     foreach ($paises as $key => $valor) {
                             $tpl->newBlock("cargar_pais");
                             $tpl->assign("valor_pais", $valor['valor']);
                             $tpl->assign("valor_id_pais", $valor['id_pais']);
                             $tpl->newBlock("cargar_provincia");
                             $tpl->assign("valor_provincia", $provincias[$key]['valor']);
                             $tpl->assign("valor_id_provincia", $provincias[$key]['id_provincia']);
                             $tpl->newBlock("cargar_localidad");
                             $tpl->assign("valor_localidad", $localidades[$key]['valor']);
                             $tpl->assign("valor_id_localidad", $localidades[$key]['id_localidad']);
                     }
                    

                }
                else{
                        $tpl = new TemplatePower("template/error.html");
                        $tpl->prepare();
                }
                
                return $tpl->getOutputContent();
        }
	 
        else{
        	return Ingreso_Controller::salir();
        	
			
        }
       
	}

        function alta_local(){

                $nombre = $_POST['scrs_nombre'];
                $descripcion = $_POST['scrs_descripcion'];
                $pais = $_POST['scrs_pais'];
                $provincia = $_POST['scrs_provincia'];
                $localidad = $_POST['scrs_localidad'];
                $direccion = $_POST['scrs_direccion'];

                //Verificar que no exista ya un local con ID_ZONA igual.
                if (!(mp_zona::verificar_zona($pais,$provincia,$localidad,$direccion))) {
                       return Local_Controller::cargar_local(0);
                }
                //codigocodigocodigodeverificaciondeidzona
                //Obtener datos de zona 
                $okok = 1;
                
                if (mp_zona::alta_zona($pais,$provincia,$localidad,$direccion)) {
                        $id_zona = mp_zona::obtener_zona($direccion);
                        if ( us_local::generar_tabla_us_local($_SESSION['usuario']->getId_user(),$id_zona)) {
                                if (art_local::alta_local($nombre,$descripcion,$id_zona)) {
                                        
                                }
                                else{
                                        
                                        $okok = 0;
                                }
                        }
                        else{
                               
                                $okok = 0;
                        }
                }
                else{
                        
                        $okok = 0;
                }
                
                if ($okok == 1) {
                     $tpl = new TemplatePower("template/exito.html");
                     $tpl->prepare();
                        
                }else{
                     $tpl = new TemplatePower("template/error.html");
                     $tpl->prepare();

                }

                return $tpl->getOutputContent();
                
              

        }


    function modificar (){
        $id_local = $_GET['id_local'];
        /*$tpl = new TemplatePower("template/modificar_local.html");
        $tpl->prepare();*/
        $tpl = new TemplatePower("template/modificar_local.html");
        $tpl->prepare();
        $tpl->assign("id_local", $id_local);
        foreach ($_SESSION['locales'] as $key => $value) {
                
            if ($id_local == $value->getId_local()) {
                //$tpl->newBlock("error_zona");

                $nombre_ = $value->getNombre();
                $descripcion_ = $value->getDescripcion();
                $direccion_ = preg_split("/[,]+/", $value->getId_zona());
                 
                 
                $tpl->assign("nombre_", $nombre_);
                $tpl->assign("descripcion_", $descripcion_);
                $tpl->assign("direccion_", $direccion_[3]);
                                    
                }                    
                                
        }

        $paises = mp_zona::obtener_paises();
        $provincias = mp_zona::obtener_provincias();
        $localidades = mp_zona::obtener_localidades();
                
       
                     
        foreach ($paises as $key => $valor) {
                    $tpl->newBlock("cargar_pais");
                    $tpl->assign("valor_pais", $valor['valor']);
                    $tpl->assign("valor_id_pais", $valor['id_pais']);
                    $tpl->newBlock("cargar_provincia");
                    $tpl->assign("valor_provincia", $provincias[$key]['valor']);
                    $tpl->assign("valor_id_provincia", $provincias[$key]['id_provincia']);
                    $tpl->newBlock("cargar_localidad");
                    $tpl->assign("valor_localidad", $localidades[$key]['valor']);
                    $tpl->assign("valor_id_localidad", $localidades[$key]['id_localidad']);
            }

   
       
        return $tpl->getOutputContent();
    }

    function alta_modificacion(){
            $id_local = $_GET['id_local'];
            $nombre = $_POST['scrs_nombre'];
            $descripcion = $_POST['scrs_descripcion'];
            $pais = $_POST['scrs_pais'];
            $provincia = $_POST['scrs_provincia'];
            $localidad = $_POST['scrs_localidad'];
            $direccion = $_POST['scrs_direccion'];

            $local_generado = art_local::generar_local_3($id_local);
  
            //modificar nombre
            $ok_up = art_local::update_($id_local, $nombre,$descripcion);
            //Modifica Direccion
            $id_zona = $local_generado->getId_zona()->getId_zona();
            print_r($local_generado);
            echo "pais";
            echo $pais;
            echo "provincia";
            echo $provincia;
            echo "localidades";
            echo $localidad;
            $ok_up_2 = art_local::update_zona($id_zona, $pais,$provincia,$localidad,$direccion);

            if ($ok_up && $ok_up_2) {
                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
            }
            else{
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();

            }
            //modificar descripcion
            

            
            return $tpl->getOutputContent();
        }

}

?>