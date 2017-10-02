<?php
class Proveedor_Controller{


	public static function menu (){
        
       $tpl = new TemplatePower("template/seccion_admin_proveedores.html");
                $tpl->prepare();
                if (Ingreso_Controller::admin_ok()) {
                        
                        if ($_SESSION['usuario']->obtener_locales($_SESSION['usuario'])) {
                            if (isset($_SESSION["proveedores"] )) {
                                
                           
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
                                    }
                            }
     
                        }
                        else{
                               $tpl->newBlock("sin_proveedores");
                        }
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