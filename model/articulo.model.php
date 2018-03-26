<?php
class articulo {
	
	private $id_articulo;
    private $nombre;
    private $id_marca;

    public function __construct($id_articulo, $nombre, $id_marca = null)
    {
        $this->id_articulo = $id_articulo;
        $this->nombre = $nombre;
        $this->id_marca = $id_marca;
    
       
    }


    public static function alta_art_general($nombre,$des = 'null'){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_articulo_ = articulo::ultimo_id_articulo();
        
        $sql = "INSERT INTO `art_articulo`(`id_articulo`, `nombre`, `descripcion`) VALUES (0,'$nombre','$des')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_articulo_;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_articulo(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_articulo'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function obtener_articulos(){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM art_articulo");  

        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0) {
           $art_nombres = array();
             
            foreach ($filas as $key => $value) {
                 
                $art_nombres[]= new articulo($value['id_articulo'],$value['nombre'] );
            }
            //$zona = mp_zona::obtener_zona__explicita($id_zona);
            
            return $art_nombres;
        }
        else{
            return false;
        }
        
    }

    public static function generar_articulo($id_articulo){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_articulo` WHERE id_articulo = $id_articulo");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
             
            $articulo = new articulo($res_fil['id_articulo'],$res_fil['nombre'],$res_fil['descripcion']);
            return $articulo;
        }
        else{
            
            return false;
        }
    }

    public static function busqueda_ajax($id){
        global $baseDatos;

        if (Ingreso_Controller::es_admin()) {
            $id_usuario =  $_SESSION["usuario"]->getId_user();
        }else{
            $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
        }

            
        $res = $baseDatos->query("    SELECT *
                FROM art_lote 
                WHERE id_lote  = '$id'");

        $filas = $res->fetch_all(MYSQLI_ASSOC);

        if (count($filas) != 0 && $filas != null AND $filas != '') {
            $attr = '';
            $counter = 0;
            foreach ($filas as $clave => $valor) { 
                $lote = art_lote::generar_lote($valor['id_lote']);
                $id_lote = $lote->getId_lote();
                $nombre = $lote->getId_art_conjunto()->getId_tipo()->getNombre();
                $filas[$counter]['attr'] = [$id_lote,$nombre];
                $counter = $counter + 1;
            }
            
            $data['status'] = 'ok';
            $data['result'] = $filas;
            
            return $data;
        }
        else{
            $data['status'] = 'err';
            $data['result'] = '';
            return false;
        }
    }

    public static function busqueda_ajax2($id){
        global $baseDatos;

        if (Ingreso_Controller::es_admin()) {
            $id_usuario =  $_SESSION["usuario"]->getId_user();
        }else{
            $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
        }

            
        $res = $baseDatos->query("    SELECT *
                FROM art_lote_local 
                WHERE id_lote  = $id");

        $filas = $res->fetch_all(MYSQLI_ASSOC);

        if (count($filas) != 0 && $filas != null AND $filas != '') {
            $attr = '';
            $counter = 0;
            foreach ($filas as $clave => $valor) { 
                $lote = art_lote_local::generar_lote_local($valor['id_lote']);
                     
                $filas[$counter]['attr'] = $lote;

            }
            
            $data['status'] = 'ok';
            $data['result'] = $filas;
            
            return $data;
        }
        else{
            $data['status'] = 'err';
            $data['result'] = '';
            return false;
        }
    }

    public static function busqueda_ajax3($id){
        global $baseDatos;

        if (Ingreso_Controller::es_admin()) {
            $id_usuario =  $_SESSION["usuario"]->getId_user();
        }else{
            $id_usuario = usuario::obtener_jefe($_SESSION["usuario"]->getId_user());
        }

            
        $res = $baseDatos->query("SELECT *
                FROM art_lote,art_moneda
                WHERE art_lote.id_lote  = $id and art_lote.id_moneda = art_moneda.id_moneda");

        $filas = $res->fetch_all(MYSQLI_ASSOC);

        if (count($filas) != 0 && $filas != null AND $filas != '') {
            $attr = '';
            $counter = 0;
            foreach ($filas as $clave => $valor) { 
                $lote = art_lote_local::generar_lote_local($valor['id_lote']);
                $valor_moneda = $valor['valor'];
                $filas[$counter]['attr'] = $lote;
                $filas[1]['valor_moneda'] = $valor_moneda;

            }
            
            $data['status'] = 'ok';
            $data['result'] = $filas;
            
            return $data;
        }
        else{
            $data['status'] = 'err';
            $data['result'] = '';
            return false;
        }
    }

    public static function act_stock_ajax($id_lote_local,$id_lote,$stock){
        global $baseDatos;

         
        $stock_viejo_lote_local = $baseDatos->query("SELECT cantidad_parcial FROM art_lote_local WHERE id_lote_local  = $id_lote_local");
        $stock_viejo_lote_local = $stock_viejo_lote_local->fetch_assoc();

        $stock_viejo_lote_local = $stock_viejo_lote_local['cantidad_parcial'];

        if ($stock_viejo_lote_local == $stock) {
            $data['status'] = 'ok';
            $data['result'] = ['Sin Cambios'];
            return $data;
        }else{
            $new_stock_lote_local = $stock_viejo_lote_local + $stock;
        }
         

        //$new_stock_lote_local = intval($stock_viejo_lote_local) + intval($aux);
        //$new_stock_lote_local = $stock;

        $stock_viejo_lote = $baseDatos->query("SELECT cantidad_total FROM art_lote WHERE id_lote  = $id_lote");
        $stock_viejo_lote = $stock_viejo_lote->fetch_assoc();
        $stock_viejo_lote = $stock_viejo_lote['cantidad_total'];


        $new_stock_lote = intval($stock_viejo_lote) + intval($stock);
         

        $act_lotelocal = articulo::update_ajax_22($id_lote_local,$new_stock_lote_local,'cantidad_parcial');

        if ($act_lotelocal) {
            $act_lote = articulo::update_ajax_2($id_lote,$new_stock_lote,'cantidad_total');
            if ($act_lote) {
                $data['status'] = 'ok';
                $data['result'] = ['id_lote',$id_lote,'cantidad_total',$new_stock_lote,'id_lote_local',$id_lote_local,'cantidad_parcial',$new_stock_lote_local];
            }else{
                $data['status'] = 'err';
                $data['result'] = "No Act Lote".$id_lote;  
                  
            }
        }else{
            $data['status'] = 'err';
            $data['result'] = "No Act lote local".$id_lote_local; 
        }
        
        return $data;
    }

    public static function update_ajax($id,$valor,$campo){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query("UPDATE `art_lote_local` SET $campo = $valor WHERE id_lote_local = $id");  
        //echo "Ajax_";
       //printf("Errormessage: %s\n", $baseDatos->error);
        return $res;
    }

    public static function update_ajax_22($id,$valor,$campo){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query("UPDATE `art_lote_local` SET cantidad_parcial = $valor WHERE id_lote_local = $id");  
        //echo "Ajax_";
       //printf("Errormessage: %s\n", $baseDatos->error);
        return $res;
    }

    public static function comprueba_pass($pass){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query("SELECT * FROM usuarios WHERE acceso='ADMIN' AND pass = '$pass'");  
        $filas = $res->fetch_all();
        if ($filas) {
            return true;
        }else{
            return false;
        }
        
    }

    

    public static function update_ajax_2($id,$valor,$campo){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query("UPDATE `art_lote` SET $campo = $valor WHERE id_lote = $id");  
        //echo "Ajax_2";
        //printf("Errormessage: %s\n", $baseDatos->error);
        return $res;
    }

    public static function act_precio_ajax($id_lote,$costo,$importe,$moneda){
        //obtener empleados por local
        global $baseDatos;
        
        $res = $baseDatos->query("UPDATE `art_lote` SET precio_base = $costo, importe = $importe, id_moneda = $moneda WHERE id_lote = $id_lote");  
        if ($res) {
            $data['status'] = 'ok';
            $data['result'] = "Exito";     
        }else{
            $data['status'] = 'err';
            $data['result'] = "Error"; 
        }
        
        return $data;
    }

     


    




    public function getId_articulo()
    {
        return $this->id_articulo;
    }
    
    public function setId_articulo($id_articulo)
    {
        $this->id_articulo = $id_articulo;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getId_marca()
    {
        return $this->id_marca;
    }
    
    public function setId_marca($id_marca)
    {
        $this->id_marca = $id_marca;
        return $this;
    }
}