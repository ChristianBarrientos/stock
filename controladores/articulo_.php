<?php
class Articulo_Controller{

	public static function mostrar(){
                $tpl = new TemplatePower("template/seccion_admin_articulos.html");
                $tpl->prepare();
                if (Ingreso_Controller::admin_ok()) {
                        
                        /*if ($_SESSION['usuario']->obtener_locales($_SESSION['usuario'])) {
                            foreach ($_SESSION['locales_articulos'] as $key => $value) {
                                print_r($value);
                                echo "\n";
                                /*$tpl->newBlock("con_articulos");
                                $tpl->assign("nombre", $value->getNombre());
                                $tpl->assign("descripcion", $value->getDescripcion());
                                $tpl->assign("direccion", $value->getId_zona());
                                $tpl->assign("cantidad_empl", $value->getCantidad_empl());*/
                                
                            //}
                            //$tpl->newBlock("agregar_local");
                            
                                
                               
                        //}
                        //else{
                               $tpl->newBlock("sin_articulos");
                        //}
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
                        if (strcmp($cate, "Precio" ) == 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_precio_base");
                            
                        }
                        if (strcmp($cate, "Tarjeta" ) == 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_precio_tarjeta");
                            
                        }

                         if (strcmp($cate, "CreditoP" ) == 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_precio_credito_argentino");
                            
                        }

                        if (strcmp($cate, "Medida" ) == 0) {
                            $tpl->newBlock("cargar_articulo_grupo");
                            $tpl->assign("des_cat", $des);
                            $tpl->newBlock("art_medida");
                            
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
                        $tpl->newBlock("locales_alta");
                        //art_carga_local_fecha_{id_art_local} para rescatar el valor del input echa
                        //art_local_cantidad_{id_art_local}
                        $tpl->assign("id_art_local1", $value->getId_local());
                        $tpl->assign("id_art_local", $value->getId_local());
                        $tpl->assign("nombre_local", $value->getNombre());
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

        $art_proveedor = $_POST['art_prvd'];
        /*echo "General";
        echo $art_general;
        echo "\n";
        echo "Marca";
        echo $art_marca;
         echo "\n";
         echo "Tipo";
        echo $art_tipo;
        echo "\n";
        echo "Cb";
        echo $art_cb;
         echo "\n";
         echo "Medida";
        echo $art_medida;
         echo "\n";
         echo "Cantidad Total";
        echo $art_cantidad_total;
         echo "\n";
         echo "Precio Base";
        echo $art_precio_base;
         echo "\n";
         echo "precio Tarjeta";
        echo $art_precio_tarjeta;
         echo "\n";
         echo "Precio Credito Personal";
        echo $art_precio_cp;
        echo "\n";
         echo "Proveedor";
        echo $art_prvd;*/

        #extract ($_POST);
        #print_r($_POST);
        $total_locales = sizeof($_SESSION['locales']);

        $contador = 1;
        $salto = 0;
        /* inicializamos una variable vacia que contendra los datos */
        $lista_art_locales = array();
        /* Luego para cada campo y valor $_POST realizamos lo siguiente */
        foreach ($_POST as $campo => $valor){
            /* en la variable $concatenamos juntamos el campo y su valor 
            print_r($campo);
            echo "%%";
            print_r($valor);*/
            if ($contador <= $total_locales) {
                $name_local_cantidad = "art_local_cantidad_".$contador;
                $name_local_fecha = "art_carga_local_fecha_".$contador;
                 
                if (strcmp($campo, $name_local_cantidad ) == 0) {
                     
                    $lista_art_locales[]=["Id" => $contador,"Cantidad" => $_POST[$name_local_cantidad],"Fecha" => $_POST[$name_local_fecha]];
                    $salto = 2;
                
                }
                
                /*if (strcmp($campo, $name_local_fecha) == 0) {
                    $lista_art_locales[]= array($campo => $valor);
                    $salto = $salto + 1;
                    
                    }*/
                if ($salto == 2) {
                    $contador = $contador + 1;
                    $salto = 0;
                }
                
            }
           
            }
        //print_r($lista_art_locales); 

        //Agregar art_general
        if (is_numeric($art_general)) {
            $id_articulo = $art_general;
          }
        else{
            $id_articulo = articulo::alta_art_general($art_general);
        } 
        if (is_numeric($art_marca)) {
            $id_articulo = $art_marca;
          }
        else{
            $id_marca = art_marca::alta_art_marca($art_marca);
        } 
        if (is_numeric($art_tipo)) {
            $id_articulo = $art_tipo;
          }
        else{
            $id_tipo = art_tipo::alta_art_tipo($art_tipo);
        } 
        /*foreach ($_SESSION['locales'] as $key => $value) {
            
            $cantidad_local = $_POST['art_local_cantidad_'.$value->getId_local()];
            $fecha_carga = $_POST['art_carga_local_fecha_'.$value->getId_local()];

            //$id_local = str_replace('art_local_cantidad_','',$id_local_0);
            
            
        }
        
        //Cargar en tabla us_datos
        //ucwords(strtolower($_POST['empl_correo']))
        echo "FECHA ALTA:";
        echo $fecha_alta;
        echo "NOMBRE";
        echo $nombre;
        echo "APELLIDO";
        echo $apellido;
        echo "FECHA ANCIMIENTO";
        echo $fecha_nac;
        echo "GENERO";
        echo $genero;
        echo "DNI";
        echo $dni;
        echo "%%";
        echo $direccion;
        echo $correo;
        echo $telefono;
        
        $id_datos = us_datos::alta_datos($fecha_alta,$nombre,$apellido,$fecha_nac,$dni,2,$genero);
        $id_contacto = us_prvd_contacto::alta_contacto($direccion,$correo,$telefono);
          
        if ($id_datos && $id_contacto) {
            $id_usuario = usuario::alta_usuario($id_datos,$id_contacto,'OPER',$usuario,$pass);
            if ($id_usuario != 'null' OR $id_usuario != 0) {
                 
                foreach ($locales as $key) {
                     
                    $id_zona = mp_zona::obtener_zona($key);
                    us_local::agregar_us_a_local($id_usuario,$id_zona);
                }

                $tpl = new TemplatePower("template/exito.html");
                $tpl->prepare();
            }
            else{
                echo "malusuario";
                $tpl = new TemplatePower("template/error.html");
                $tpl->prepare();

            }
        }
        else{
            echo "maldatoscontacto";
            $tpl = new TemplatePower("template/error.html");
            $tpl->prepare();
        }
        return $tpl->getOutputContent();*/
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