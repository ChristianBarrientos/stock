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
    public static function reporte_co($fecha_desde,$fecha_hasta,$medio){

        global $baseDatos;
        if ($medio == 0) {
           
            $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au, `art_venta_medio` AS vm
                                WHERE av.fecha_hora 
                                                BETWEEN '$fecha_desde' AND '$fecha_hasta'
                                                AND au.id_venta = av.id_venta 
                                                AND vm.id_medio = av.id_medio "); 
        }
        else{
            print_r($medio);
            $res = $baseDatos->query("SELECT * 
                                FROM `art_venta` AS av, `art_unico` as au, `art_venta_medio` AS vm
                                WHERE av.fecha_hora 
                                                BETWEEN '$fecha_desde' AND '$fecha_hasta'
                                                AND au.id_venta = av.id_venta 
                                                AND vm.id_medio = av.id_medio 
                                                AND vm.id_medio = '$medio'"); 
        }
        
      
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