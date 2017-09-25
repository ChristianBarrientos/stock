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
                                     
                                    $tpl->assign("descripcion", $value->getDescripcion());
                                    $tpl->assign("prvd_foto", $value->getid_datos_prvd()->getId_foto());
                                    $tpl->assign("prvd_fecha_alta", $value->getid_datos_prvd()->getFecha_alta());
                                    $tpl->assign("prvd_cuit",$value->getid_datos_prvd()->getCuit());
                                    $tpl->assign("prvd_direccion",$value->getId_contacto()->getDireccion());
                                    $tpl->assign("prvd_correo",$value->getId_contacto()->getCorreo());
                                    $tpl->assign("prvd_telefono",$value->getId_contacto()->getNro_telefono());
                                    
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