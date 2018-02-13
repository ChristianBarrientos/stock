<?php
class Proveedor_Controller{


	public static function menu (){
        
     $tpl = new TemplatePower("template/seccion_admin_proveedores.html");
     $tpl->prepare();
     if (Ingreso_Controller::admin_ok()) {
        
                        //if ($_SESSION['usuario']->obtener_locales($_SESSION['usuario'])){
        $_SESSION['usuario']->obtener_locales($_SESSION['usuario']);
        if (isset($_SESSION["proveedores"] )) {
            
            if ($_SESSION["proveedores"] == false) {
                
                $tpl->newBlock("sin_proveedores");
            }else{
                
             
             
                foreach ($_SESSION["proveedores"] as $key => $value) {
                    
                    $tpl->newBlock("con_proveedores");
                    $tpl->assign("prvd_nombre", $value->getid_datos_prvd()->getNombre());
                    $des = $value->getDescripcion();
                    if ($des == null) {
                        $tpl->assign("descripcion",'Sin definir.' );
                    }else{
                        $tpl->assign("descripcion", $des);
                    }
                    
                    $tpl->assign("prvd_foto", $value->getid_datos_prvd()->getId_foto());
                    $fecha_a = $value->getid_datos_prvd()->getFecha_alta();
                    if ($fecha_a == 0000-00-0) {
                        $tpl->assign("prvd_fecha_alta", 'Sin Definir' );
                    }
                    else{
                        $tpl->assign("prvd_fecha_alta", $fecha_a );
                    }
                    $cuit = $value->getid_datos_prvd()->getCuit();
                    if ($cuit == null) {
                        $tpl->assign("prvd_cuit",'Sin Definir.');
                    }else{
                        $tpl->assign("prvd_cuit",$cuit);
                    }
                    $direccion = $value->getId_contacto()->getDireccion();
                    if ($direccion == 'NULL') {
                        $tpl->assign("prvd_direccion",'Sin Definir.');
                    }else{
                        $tpl->assign("prvd_direccion",$direccion);
                    }
                    $correo = $value->getId_contacto()->getCorreo();
                    if ($correo == 'NULL') {
                        $tpl->assign("prvd_correo",'Sin Definir.');
                    }else{
                        $tpl->assign("prvd_correo",$correo);
                    }
                    $telefono = $value->getId_contacto()->getNro_telefono();
                    if ($telefono == null) {
                        $tpl->assign("prvd_telefono",'Sin Definir.');
                    }
                    else{
                        $tpl->assign("prvd_telefono",$telefono);

                        
                    } 

                    $tpl->assign("id_prvd",$value->getId_proveedor());
                    $tpl->assign("id_prvd_",$value->getId_proveedor());

                    $tpl->newBlock("modal_articulos_prvd");
                    $tpl->assign("id_prvd_",$value->getId_proveedor());

                                    //obtener articulos por prdv

                    $art_prvd = proveedor::obtener_art_prvd($value->getId_proveedor());
                    $numero = 1;
                    foreach ($art_prvd as $key2 => $value2) {
                                        # code...
                        $tipo = $value2->getId_art_conjunto()->getId_tipo()->getNombre();
                        $nombre = $value2->getId_art_conjunto()->getId_tipo()->getNombre().','.$tipo;
                        $cantidad_total = $value2->getCantidad();
                        $precio_base = $value2->getPrecio_base();
                        $ganancia = $value2->getImporte();
                        $porcentaje_ganancia = (int)'0'.'.'.$ganancia;
                        $precio_final = (int)$precio_base + ((int)$precio_base * $porcentaje_ganancia);

                        $tpl->newBlock("lista_art_prvd");
                        $tpl->assign("numero",$numero);
                        $tpl->assign("nombre",$nombre);
                        $tpl->assign("cantidad_total",$cantidad_total);
                        $tpl->assign("precio_base",'$'.$precio_base);
                        $tpl->assign("precio_final",'$'.$precio_final.'  (%'.$ganancia.')');

                        $numero = $numero+ 1;

                        
                    }
                }
            }
        }
        
                        //}
        
    }
    else{
        return Ingreso_Controller::salir();
    }

    return $tpl->getOutputContent();
    

}

public static function cargar_proveedor(){
    if (isset($_SESSION["usuario"])){
        if ($_SESSION["permiso"] == 'ADMIN') {
            $tpl = new TemplatePower("template/cargar_proveedor.html");
            $tpl->prepare();
            
            
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

public static function alta_proveedor(){
    $nombre = $_POST['prvd_nombre'];
    $cuit = $_POST['prvd_cuit'];
    $fecha_alta = $_POST['prvd_fecha_alta'];
    $direccion = $_POST['prvd_direccion'];
    $correo = $_POST['prvd_correo'];
    $telefono = $_POST['prvd_telefono'];
    $descripcion = $_POST['prvd_descripcion'];

    if ($cuit == null) {
        $cuit = 'NULL';
    }

    if ($fecha_alta == null) {
        $fecha_alta = 'NULL';
    }

    if ($direccion == null) {
        $direccion = 'NULL';
    }

    if ($correo == null) {
        $correo = 'NULL';
    }

    if ($telefono == null) {
        $telefono = 'NULL';
    }

    if ($descripcion == null) {
        $descripcion = 'NULL';
    }

    $id_contacto = us_prvd_contacto::alta_contacto($direccion,$correo,$telefono);
    $id_datos = prvd_datos::alta_datos($fecha_alta,$nombre,$cuit,1);

    $id_prvd = proveedor::alta_prvd($id_datos,$id_contacto,$descripcion);
    
    if ($id_prvd != 'null' OR $id_prvd != 0) {

        if (us_prvd::agregar_prvd_a_us($id_prvd,$_SESSION['usuario']->getId_user())) {
            $tpl = new TemplatePower("template/exito.html");
            $tpl->prepare();
            $tpl->newBlock("alta_prvd_exito");
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


function modificar(){
    $id_prvd = $_GET['id_prvd'];
    $tpl = new TemplatePower("template/modificar_proveedor.html");
    $tpl->prepare();
    

    
    foreach ($_SESSION["proveedores"] as $key => $valor) {
       
                // foreach ($value as $clave => $valor) {
                //este prvd pertenece a este usuario?               
        
        if ($id_prvd == $valor->getId_proveedor()) {

            $nombre = $valor->getid_datos_prvd()->getNombre();
            $cuit = $valor->getid_datos_prvd()->getCuit();

            $fecha_alt = $valor->getid_datos_prvd()->getFecha_alta();

            $descripcion = $valor->getDescripcion();

            $direccion =  $valor->getId_contacto()->getDireccion();
            $correo = $valor->getId_contacto()->getCorreo();
            $telefono = $valor->getId_contacto()->getNro_caracteristica().$valor->getId_contacto()->getNro_telefono();
            
            $tpl->assign("id_prvd", $id_prvd);
            $tpl->newBlock("form");

            
            $tpl->assign("nombre", $nombre);

            $tpl->assign("cuit", $cuit);
            $tpl->assign("fecha_alt", $fecha_alt);
            $tpl->assign("descripcion", $descripcion);

            if ($direccion == 'NULL') {
                $tpl->assign("direccion", '');
            }else{
                $tpl->assign("direccion", $direccion);
            }

            
            if ($correo == 'NULL' ) {
                $tpl->assign("correo", '');
            }else{
                $tpl->assign("correo", $correo);
            }

            if ($telefono == 'NULL' || $telefono == '0') {
                $tpl->assign("telefono", '');
            }else{

                $tpl->assign("telefono", $telefono);
            }

        }

        
    }

        //}   
    return $tpl->getOutputContent();    

}


function alta_modificacion(){
    $id_prvd = $_GET['id_prvd'];
    $nombre = $_POST['prvd_nombre'];
    
    $cuit = $_POST['prvd_cuit'];

    $fecha_alta = $_POST['prvd_fecha_alta'];
    $descripcion = $_POST['prvd_descripcion'];

    $direccion = $_POST['prvd_direccion'];
    $correo = $_POST['prvd_correo'];
    $telefono = $_POST['prvd_telefono'];

    $prdv = proveedor::generar_prvd($id_prvd);

    
    
    $ok = $prdv->update($id_prvd,'descripcion',$descripcion);

    
    $ok_1 = prvd_datos::update($prdv->getid_datos_prvd()->getId_datos_prvd(),'nombre',$nombre);


    $ok_2 = prvd_datos::update($prdv->getid_datos_prvd()->getId_datos_prvd(),'cuit',$cuit);

    $ok_3 = us_prvd_contacto::update($prdv->getId_contacto()->getId_contacto(),'direccion',$direccion);
    $ok_4 = us_prvd_contacto::update($prdv->getId_contacto()->getId_contacto(),'correo',$correo);

            //$ok_5 = $prdv->getId_contacto()->update($prdv->getId_contacto()->getId_contacto(),'telefono',$telefono);&& $ok_5


    if ($ok_1 && $ok_2 && $ok_3 && $ok_4) {
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