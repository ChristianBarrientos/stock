<?php
class reporte {

	public static function reporte_av($fecha_desde,$fecha_hasta){
        global $baseDatos;

        $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au
                                WHERE av.fecha_hora BETWEEN '$fecha_desde' AND '$fecha_hasta'
                                AND au.id_venta = av.id_venta");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
       
        if (count($filas) != 0) {
            $art_unico = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
               
                $art_unico[] = art_unico::generar_unico($valor['id_unico']);
            }
            return $art_unico;
        }
        else{
           
            return false;
        }
    }

    public static function reporte_av_todos(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au
                                WHERE au.id_venta = av.id_venta");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            $art_unico = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
               
                $art_unico[] = art_unico::generar_unico($valor['id_unico']);

            }
             
            
             
            return $art_unico;
        }
        else{
           
            return false;
        }
    }
    public static function reporte_vm(){

    	
    }
    public static function reporte_vc(){
    	
    }
    public static function reporte_co($fecha_desde,$fecha_hasta,$medio,$local){

        global $baseDatos;
        if ($medio == 0 && $local == 0) {
           
            $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au, `art_venta_medio_pago` AS vm
                                WHERE av.fecha_hora 
                                                BETWEEN '$fecha_desde' AND '$fecha_hasta'
                                                AND au.id_venta = av.id_venta 
                                                AND vm.id_medio_pago = av.id_medio_pago "); 
        }
        if ($medio != 0 && $local == 0) {
            # code...
            $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au, `art_venta_medio_pago` AS vm
                                WHERE av.fecha_hora 
                                                BETWEEN '$fecha_desde' AND '$fecha_hasta'
                                                AND au.id_venta = av.id_venta 
                                                AND vm.id_medio_pago = av.id_medio_pago 
                                                AND vm.id_medio_pago = '$medio'"); 
        }

        if ($medio == 0 && $local != 0) {
            # code...
            echo "aca";
            $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au, `art_lote_local` as ll, `art_venta_medio_pago` AS vm
                                WHERE av.fecha_hora 
                                                BETWEEN '$fecha_desde' AND '$fecha_hasta'
                                                AND au.id_venta = av.id_venta 
                                                AND ll.id_lote_local = au.id_lote_local 
                                                AND ll.id_local = '$local'
                                                AND vm.id_medio_pago = av.id_medio_pago"); 
            //AND vm.id_medio = av.id_medio 
        }

        if ($medio != 0 && $local != 0) {
            $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au, `art_lote_local` as ll, `art_venta_medio_pago` AS vm
                                WHERE av.fecha_hora 
                                                BETWEEN '$fecha_desde' AND '$fecha_hasta'
                                                AND au.id_venta = av.id_venta 
                                                AND vm.id_medio_pago = av.id_medio_pago 
                                                AND vm.id_medio_pago = '$medio'
                                                AND ll.id_lote_local = au.id_lote_local 
                                                AND ll.id_local = '$local'"); 
        }

         
            
       
        printf("Errormessage: %s\n", $baseDatos->error);
       
        $filas = $res->fetch_all(MYSQLI_ASSOC);
       
        if (count($filas) != 0) {
            $art_unico = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
               
                $art_unico[] = art_unico::generar_unico($valor['id_unico']);
            }
            return $art_unico;
        }
        else{
           
            return false;
        }
    	
    }
    public static function reporte_vem(){
    	
    }
    public static function reporte_aem($fecha_desde,$fecha_hasta){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `us_acceso` 
                                  WHERE fecha_hora_inicio BETWEEN '$fecha_desde' AND '$fecha_hasta'");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
      
        if (count($filas) != 0) {
            $us_acessos = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
               
                $us_acessos[] = us_acceso::generar_acceso($valor['id_acceso']);
            }
            return $us_acessos;
        }
        else{
           
            return false;
        }
    	
    }

    public static function reporte_gs($id_gs_des,$fecha_desde,$fecha_hasta){
        global $baseDatos;
        
        if ($id_gs_des == 0) {
            # code...
            //Obtener los gastos y sus tipos
            $permisos = $_SESSION['usuario']->getAcceso();
            if ($permisos == 'ADMIN') {
                # code...
                $id_jefe = $_SESSION['usuario']->getId_user();
            }else{
                $id_jefe = usuario::obtener_jefe($_SESSION['usuario']->getId_user());
            }
            $us_gastos = us_gastos::obtener($id_jefe);
             
            $gasto = $us_gastos->getId_us_ggs();
            
            $gastos = array();

            foreach ($gasto as $key => $value) {
                # code...
                $gasto2 = $value->getId_gasto();

                $gasto_ = $gasto2->getId_ggs()->getId_gasto_unico();
            
                foreach ($gasto_ as $key3 => $value3) {

                        $fecha2 = explode(" ",$fecha_desde);
                        $fecha3 = $fecha2[0];
                        $fecha_desde_ = strtotime("$fecha3");

                        $fecha2 = explode(" ",$fecha_hasta);
                        $fecha3 = $fecha2[0];
                        $fecha_hasta_ = strtotime("$fecha3");

                        $fecha_hora = strtotime($value3->getFecha_hora());

                        if ($fecha_desde_ < $fecha_hora && $fecha_hasta_ > $fecha_hora) {
                            $gastos[] = [$gasto2,$gasto_];
                        }
                    //}
                }
            }
           
            return $gastos;


        }else{
            $res = $baseDatos->query("SELECT * FROM `gs_gastos` 
                                  WHERE id_gs_des = $id_gs_des");
        }
          

        $filas = $res->fetch_all(MYSQLI_ASSOC);
      
        if (count($filas) != 0) {
            $gastos = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
                $gasto = gs_gastos::generar_gasto($valor['id_gasto']);
                $id_ggs = $gasto->getId_ggs()->getId_gasto_unico();
                foreach ($id_ggs as $key => $value) {
                    # code...

                    $fecha2 = explode(" ",$fecha_desde);
                    $fecha3 = $fecha2[0];
                    $fecha_desde_ = strtotime("$fecha3");

                    $fecha2 = explode(" ",$fecha_hasta);
                    $fecha3 = $fecha2[0];
                    $fecha_hasta_ = strtotime("$fecha3");

                    $fecha_hora = strtotime($value->getFecha_hora());

                    if ($fecha_desde_ < $fecha_hora && $fecha_hasta_ > $fecha_hora) {
                        $gastos[] = $gasto;
                    }
                }
                
            }
            return $gastos;
        }
        else{
           
            return false;
        }
        
    }
    public static function reporte_sa(){

        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_lote_local` ORDER BY  id_local");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
      
        if (count($filas) != 0) {
            $art_lote_local = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
               
                $art_lote_local[] = art_lote_local::generar_lote_local_id_($valor['id_lote_local']);
            }
            return $art_lote_local;
        }
        else{
           
            return false;
        }
        
    }
    	
   



}

?>