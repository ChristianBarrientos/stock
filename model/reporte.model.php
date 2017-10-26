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
    public static function reporte_vt(){
    	
    }
    public static function reporte_vc(){
    	
    }
    public static function reporte_co(){
    	
    }
    public static function reporte_vem(){
    	
    }
    public static function reporte_aem(){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * 
                                FROM `art_lote_local`");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
       
        if (count($filas) != 0) {
            $art_lotelocal = array();
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {
               
                $art_lotelocal[] = art_lote_local::generar_lote_local($valor['id_lote_local']);
            }
            return $art_lotelocal;
        }
        else{
           
            return false;
        }
    	
    }
    public static function reporte_sa(){
    	
    }




}

?>