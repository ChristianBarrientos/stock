<?php
class Articulo_Controller{

	public static function mostrar(){
                $tpl = new TemplatePower("template/seccion_admin_articulos.html");
                $tpl->prepare();
                if (Ingreso_Controller::admin_ok()) {
                        
                        if ($_SESSION['usuario']->obtener_lote_us($_SESSION['usuario']->getId_user())) {
                            foreach ($_SESSION['lotes'] as $key => $value) {
                                 
                                $art = $value->getId_art_conjunto()->getId_articulo()->getNombre();
                                $marca = $value->getId_art_conjunto()->getId_marca()->getNombre();
                                $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
                                $nombre_ = $art.' '.$marca.' '.$tipo;
                                $tpl->newBlock("con_articulos");
                                $tpl->assign("nombre",$nombre_);
                                $tpl->assign("prvd",$value->getId_proveedor());
                                $tpl->assign("cantidad_total",$value->getCantidad());
                                $tpl->assign("cb",$value->getId_cb()->getcb());

                                $gc = $value->getId_gc()->getId_categoria();
                                print_r($gc);
                                foreach ($gc as $clave => $valor) {
                                    if (strcmp($valor->getNombre(), "Medida" ) == 0 ) {
                                        $medida = $valor->getValor();
                                        
                                    }

                                    if (strcmp($valor->getNombre(), "Precio" ) == 0 ) {
                                        $precio_base = $valor->getValor();
                                        
                                    }

                                    if (strcmp($valor->getNombre(), "Tarjeta" ) == 0 ) {
                                        $por_ciento_t = $valor->getValor();
                                        $precio_tarjeta = $precio_base * ((int)$precio_base * (int)$por_ciento_t);
                                         

                                        
                                    }

                                    if (strcmp($valor->getNombre(), "CreditoP" ) == 0 ) {
                                        $por_ciento_p = $valor->getValor();
                                        $credito_personal = $precio_base * ((int)$precio_base * (int)$por_ciento_p);
                                        
                                    }

                                    if (strcmp($valor->getNombre(), "Color" ) == 0 ) {
                                        $art_color = $valor->getValor();
                                        
                                    }
                                }
                                if ($precio_base != null) {
                                    $tpl->assign("precio_base",$precio_base);
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
                                    $tpl->assign("precio_tarjeta",$precio_tarjeta.'(%'.$por_ciento_t.')');
                                }
                                else{
                                    $tpl->assign("precio_tarjeta",'Sin Definir');
                                }

                                if ($credito_personal != null) {
                                    $tpl->assign("credito_personal",$credito_personal.'(%'.$por_ciento_p.')');
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
                                /*$tpl->assign("descripcion", $value->getDescripcion());
                                $tpl->assign("direccion", $value->getId_zona());
                                $tpl->assign("cantidad_empl", $value->getCantidad_empl());*/
                                
                            }
                            //Lote de Articulos por Local
                            //$_SESSION["lote_local"] 
                            //Lote de Articulos en general
                            //$_SESSION["lotes"]  
                            //print_r($_SESSION["lote_local"]);
                            //print_r($_SESSION["lotes"]);
                            $tpl->newBlock("con_articulos");
                            
                                
                               
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
                                
                /*foreach ($lista_articulos_nombre as $key => $value) {
                    print_r($value);
                }*/
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

        $art_general = ucwords(strtolower($_POST['select_art_general']));
        $art_marca = ucwords(strtolower($_POST['art_marca']));
        $art_tipo = ucwords(strtolower($_POST['art_tipo']));
        $art_cantidad_total = $_POST['art_cantidad_total'];
        $art_cb =$_POST['art_codigo_barras'];
        
       
        $art_prvd =$_POST['art_prvd'];

        $art_precio_base = $_POST['art_precio_base'];
        $art_precio_tarjeta = $_POST['art_precio_tarjeta'];
        $art_precio_cp= $_POST['art_precio_credito_argentino'];
        $art_medida = ucwords(strtolower($_POST['art_medida']));
        $art_color =$_POST['art_color'];


        $art_proveedor = $_POST['art_prvd'];
        
        $total_locales = sizeof($_SESSION['locales']);

        $contador = 1;
        $salto = 0;
        /* inicializamos una variable vacia que contendra los datos */
        $lista_art_locales = array();
        /* Luego para cada campo y valor $_POST realizamos lo siguiente */
        $nook = true;
        foreach ($_POST as $campo => $valor){
            /* en la variable $concatenamos juntamos el campo y su valor 
            print_r($campo);
            echo "%%";
            print_r($valor);*/
            for ($i=1; $i <=$total_locales ; $i++) { 
                if ($i <= $total_locales) {
                //Revisar el contador nos va a dar falsos positivos!!!
                //Utilizar exoresie¡
                $name_local_cantidad = "art_local_cantidad_".$i;
                $name_local_fecha = "art_carga_local_fecha_".$i;
                 
                if (strcmp($campo, $name_local_cantidad ) == 0) {

                    if ($_POST[$name_local_fecha] != null) {
                        $lista_art_locales[]=["Id" => $i,"Cantidad" => $_POST[$name_local_cantidad],"Fecha" => $_POST[$name_local_fecha]];
                         
                        }
                    }
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
        $id_cb = art_codigo_barra::alta_art_cb($art_cb);

        //cargar art_categorias y art_grupo_categoria
        
        $list_art_grupo_att = art_grupo_categoria::obtener_categoriast();

            foreach ($list_art_grupo_att as $key => $value) {
                        
                $nombre =  $value->getNombre();
                
                if (strcmp($nombre, "Precio" ) == 0) {
                 
                    $id_categoria_precio = art_categoria::alta_art_categoria($nombre,$art_precio_base,"En moneda local");
                    #echo "PRECIO:";
                    #echo $nombre .' '.$valor;
                    //Como precio entra primero, lo usamos como bandera para el id del grupo de categorias
                    $id_gc = art_grupo_categoria::alta_art_gc($id_categoria_precio);

                }


                if (strcmp($nombre, "Medida" ) ==  0) {
                 
                    $id_categoria_medida = art_categoria::alta_art_categoria($nombre,$art_medida);
                    #echo "MEDIDA:";
                    #echo $nombre .' '.$valor;
                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_medida);
                    if ($ok) {
                        $nook = false;
                    }
                    
                }

                if (strcmp($nombre, "Tarjeta" ) ==  0) {
                     
                    $id_categoria_tarjeta = art_categoria::alta_art_categoria($nombre,trim($art_precio_tarjeta,'%'),"En porcentaje");
                    #echo "TARJETA:";
                    #echo $nombre .' '.$valor;
                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_tarjeta);
                    if ($ok) {
                        $nook = false;
                    }
                }

                if (strcmp($nombre, "CreditoP" ) ==  0) {
                 
                    $id_categoria_creditop = art_categoria::alta_art_categoria($nombre,trim($art_precio_cp,'%'),"En porcentaje");
                    #echo "CREDITOP:";
                    #echo $nombre .' '.$valor;
                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_creditop);
                    if ($ok) {
                        $nook = false;
                    }
                }

                if (strcmp($nombre, "Color" ) ==  0) {
                 
                    #echo "COLOR:";
                    #echo $nombre .' '.$valor;
                    $id_categoria_color = art_categoria::alta_art_categoria($nombre,$art_color,'Color escrito');
                    $ok = art_grupo_categoria::alta_art_gc_2($id_categoria_color);
                    if ($ok) {
                        $nook = false;
                    }
                    break;
                }

            

            }

        //cargar art_lote
        if ($id_proveedor != null) {
            $id_lote = art_lote::alta_art_lote($id_conjunto, $art_cantidad_total, $id_cb, $id_gc, $id_proveedor);
        }else{
            $id_lote = art_lote::alta_art_lote($id_conjunto, $art_cantidad_total, $id_cb, $id_gc);
        }   
        

        //cargar art_carga y art_lote_local
         
        foreach ($lista_art_locales as $key => $value) {
            /*$_fecha = trim($value['Fecha'],'AM');
            $_fecha = trim($_fecha,'PM');
            $_fecha = substr($_fecha, 0, -1);
            $_fecha = $_fecha.':'.'00';
            echo$_fecha;*/
             
            $id_carga = art_carga::alta_art_carga($value['Fecha'], $_SESSION["usuario"]->getId_user());
            $id_lote_local = art_lote_local::alta_art_lote_local($id_lote,$value['Id'],$value['Cantidad'],$id_carga);
        }

        //cargar lote_us
        $ok2 = $_SESSION["usuario"]->alta_lote_us($id_lote);
        
        if ($ok && $ok2) {
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




        public static function cargar_art_general(){
            if (isset($nombre)) {
                 
                $nombre = ucwords(strtolower($_POST['art_general']));
                #echo $nombre;
                
                $res = articulo::alta_art($nombre);
                
                if ($res) {
                    echo "okok";
                }

            }
        }

        public static function cargar_art_marca(){

        $nombre = ucwords(strtolower($_POST['art_marca']));
        echo $nombre;

        }

        public static function cargar_art_tipo(){

        $nombre = ucwords(strtolower($_POST['art_tipo']));
        echo $nombre;

        }

}

?>