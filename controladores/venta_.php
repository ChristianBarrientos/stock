<?php
class Venta_Controller{

	public static function mostrar_parametros_medio(){
               
                
        $tpl = new TemplatePower("template/mostrar_parametros_ventas_medio.html");
        $tpl->prepare();

        if (Ingreso_Controller::admin_ok()) {
        }
        else{
            return Ingreso_Controller::salir();
        }
        return $tpl->getOutputContent();
    }

 
	public static function alta_parametros_medio(){
               
                
        $tpl = new TemplatePower("template/parametros_ventas_medio.html");
        $tpl->prepare();
        return $tpl->getOutputContent();
    }

    public static function confirmar_alta_parametros_medio(){
               
                
        $tpl = new TemplatePower("template/seccion_admin_articulos.html");
        $tpl->prepare();
    }
}

?>
                                