<?php
class Empleado_Controller{

	public static function cargar_empleado(){
		if (isset($_SESSION["usuario"])){
        	if ($_SESSION["permiso"] == 'ADMIN') {
        		$tpl = new TemplatePower("template/cargar_empleado.html");
				$tpl->prepare();
				

				foreach ($_SESSION['locales'] as $key => $value) {
                        $tpl->newBlock("locales_empleado_alta");
                        $cadena = $value->getId_zona();
                        $direccion = after_last (',', $cadena);
                        $tpl->assign("id_local", $direccion);
                        $tpl->assign("nombre_local", $value->getNombre());
                                
                                
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

	public static function menu(){
			if (Ingreso_Controller::es_admin()) {
				$tpl = new TemplatePower("template/seccion_admin_empleado.html");
				$tpl->prepare();
			$empleados_si = false;

			if ($_SESSION['usuario']::obtener_locales($_SESSION['usuario'])) {
				  
				foreach ($_SESSION["locales_empleados"] as $key => $value) {
					foreach ($_SESSION["locales_empleados"][$key] as $clave => $valor) {
						//print_r($valor->getId_datos()->getFecha_alta());
						if ($valor->getAcceso() == 'OPER') {
							$empleados_si = true;
							$tpl->newBlock("con_empleados");
							$tpl->assign("empl_nombre", $valor->getId_datos()->getNombre().' '.$valor->getId_datos()->getApellido());
                            $fecha_alta = $valor->getId_datos()->getFecha_alta();
                            //if (strcmp($fecha_alta, "0000-00-0" ) == 0 ) {
                            if ($fecha_alta ==  0000-00-0 ) {
                                $tpl->assign("empl_fecha_alta", 'Sin Definir.');
                            }else{

                                $tpl->assign("empl_fecha_alta", $fecha_alta);    
                            }
							$empl_dni = $valor->getId_datos()->getDni();
                            if ($empl_dni == null) {
                                $tpl->assign("empl_dni", 'Sin Definir.');
                            }
                            else{
                                $tpl->assign("empl_dni",$empl_dni );
                            }
							$fecha_nac = $valor->getId_datos()->getFecha_nac();
                            if ($fecha_nac == 0000-00-0) {
                                $tpl->assign("empl_fecha_nac", 'Sin Definir.');
                            }
                            else{
                                $tpl->assign("empl_fecha_nac",$fecha_nac );
                            }
							$empl_direccion = $valor->getId_contacto()->getDireccion();
                            if ($empl_direccion == 'NULL') {
                                $tpl->assign("empl_direccion", 'Sin Definir.');
                            }else{
                                $tpl->assign("empl_direccion",$empl_direccion );
                            }
							$empl_correo = $valor->getId_contacto()->getCorreo();
                            if ($empl_correo == 'NULL') {
                                $tpl->assign("empl_correo", 'Sin Definir.');
                            }
                            else{
                                $tpl->assign("empl_correo",$empl_correo );
                            }
							$empl_telefono = $valor->getId_contacto()->getNro_caracteristica().'-'.$valor->getId_contacto()->getNro_telefono();
                            if ($empl_telefono == '0-') {
                                $tpl->assign("empl_telefono", 'Sin Definir.');
                            }else{
                                $tpl->assign("empl_telefono",$empl_telefono );
                            }
							
							$tpl->assign("empl_foto", $valor->getId_datos()->getFoto());
						}
					}
					
				}
				
				

			}
			if (!($empleados_si)) {
				$tpl->newBlock("sin_empleados");
			}
			
			

			}
			else{
				return Ingreso_Controller::salir();
			}
		
			return $tpl->getOutputContent();
	}

	public static function alta_empelado(){

		$nombre = ucwords(strtolower($_POST['empl_nombre']));
        $apellido = ucwords(strtolower($_POST['empl_apellido']));
        $genero = $_POST['empl_genero'];
        $dni = $_POST['empl_dni'];
        $fecha_nac = $_POST['empl_fecha_nac'];
        $fecha_alta = $_POST['empl_fecha_alta'];
        $direccion = ucwords(strtolower($_POST['empl_direccion']));
 		$correo = ucwords(strtolower($_POST['empl_correo']));
        $telefono = $_POST['empl_telefono'];
        $usuario = $_POST['empl_usuario'];
        $pass = $_POST['empl_pass'];
        $locales = $_POST['empl_local'];
        if ($dni == null) {
            $dni = 'NULL';
        }
        if ($fecha_nac == null) {
            $fecha_nac = 'NULL';
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

        //Cargar en tabla us_datos
        //ucwords(strtolower($_POST['empl_correo']))
        /*echo "FECHA ALTA:";
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
        */
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
        return $tpl->getOutputContent();
	}
	
}
?>