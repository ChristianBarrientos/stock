<?php
class art_lote_local {
	
	
    private $id_lote;
    private $id_local;
    private $cantidad_parcial;
    private $id_carga;
    private $id_lote_local;
  

    public function __construct($id_lote_local, $id_lote, $id_local,$cantidad_parcial,$id_carga)
    {
         
        $this->id_lote_local = $id_lote_local;
        $this->id_lote = $id_lote;
        $this->id_local = $id_local;
        $this->cantidad_parcial = $cantidad_parcial;
        $this->id_carga = $id_carga;

        
    }

    public static function reiniciar_autoincrement(){
        global $baseDatos;
        $sql = "ALTER TABLE art_lote_local AUTO_INCREMENT = 1";
        $res = $baseDatos->query($sql);
        return $res;
    }

    public static function alta_art_lote_local($id_lote,$id_local,$cantidad_parcial,$id_carga){
        global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_lote_local = art_lote_local::ultimo_id_lote_local();
        
        $sql = "INSERT INTO `art_lote_local`(`id_lote_local`, `id_lote`, `id_local`, `cantidad_parcial`, `id_carga`) VALUES (0,$id_lote,$id_local,$cantidad_parcial,$id_carga)";
        $res = $baseDatos->query($sql);
        //printf("Errormessage: %s\n", $baseDatos->error);
        if ($res) {
             
            return $id_lote_local;
        }else{
            echo "Alta ArtLoteLocal";
            echo "\n";
            echo "Id_lote:";echo $id_lote;
            echo "id_local:";echo $id_local;
            echo "cantidad_parcial:";echo $cantidad_parcial;
            echo "id_carga:";echo $id_carga;
            printf("Errormessage: %s\n", $baseDatos->error);
            return false;
        }

    }
    public static function ultimo_id_lote_local(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_lote_local'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_lote_local($id_lote){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_lote_local` WHERE id_lote = $id_lote"); 
        
        $filas = $res->fetch_all(MYSQLI_ASSOC);
        
        if (count($filas) != 0) {
            
            $lote_local = array();
            
            //$usuario_prvd = array(0);
            foreach ($filas as $clave => $valor) {

                $id_lote = art_lote::generar_lote($valor['id_lote']);
                $id_local = $valor['id_local']; //art_local::generar_local_2($valor['id_local']);
                $id_carga = art_carga::generar_carga($valor['id_carga']); 
              
                 
                $lote_local[] = new art_lote_local($valor['id_lote_local'],
                                $id_lote,$id_local,$valor['cantidad_parcial'],$id_carga);

            }
            return $lote_local;
        }
        else{
           
            return false;
        }
    }
    

    public static function generar_lote_local_id_($id_lote_local){
       
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_lote_local WHERE id_lote_local = $id_lote_local");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {

            $id_lote = art_lote::generar_lote($res_fil['id_lote']);
            $id_local = art_local::generar_local_2($res_fil['id_local']);
            $id_carga = art_carga::generar_carga($res_fil['id_carga']); 
              
                 
            $lote_local = new art_lote_local($res_fil['id_lote_local'],
                                $id_lote,$id_local,$res_fil['cantidad_parcial'],$id_carga);
            return $lote_local;
        }
        else{
            return false;
        }
    }

    public static function actualiza_($nuevo,$nuevonuevo,$id_lote_local = null,$id_lote = null){
       
        global $baseDatos;
        
        if ($id_lote_local == null) {
            # code...
            $id_lote_local = $_SESSION["art_lote_local"]->getId_lote_local();
        }

        

        $sql = "UPDATE `art_lote_local` SET `cantidad_parcial`= $nuevonuevo
                WHERE `id_lote_local` = $id_lote_local ";
        $res = $baseDatos->query($sql);

        if ($id_lote_local == null) {
            # code...
            $sql2 = art_lote_local::actualiza_lote_cantidad($nuevo);
        }
        else{
            $sql2 = art_lote_local::actualiza_lote_cantidad($nuevo,$id_lote);
        }
        
        if ($sql && $sql2) {
             
            return $res; 
        }
        else{
            return false;
        }
        
          
    

    }

    public static function obtener_lote_local_oper($id_lote,$id_local){
       
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_lote_local` WHERE id_lote = $id_lote AND id_local = $id_local");  

        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
           
            return $res_fil['id_lote_local'];
        }
        else{
            return false;
        }
        
          
    

    }

    public static function cantidad_parcial_modificada($id_lote_local,$cantidad_parcial){
       
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_lote_local` WHERE id_lote_local = $id_lote_local ");  

        $res_fil = $res->fetch_assoc();

        if (count($res_fil) != 0) {
            return $res_fil['cantidad_parcial'] ;
            if ($res_fil['cantidad_parcial'] != $cantidad_parcial) {
                return true;
            }else{
                return false;
            }
            
        }
        else{
            return false;
        }
        
          
    

    }

    public static function update_cantidad_parcial($id_lote_local,$cantidad_parcial){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_lote_local` SET `cantidad_parcial`='$cantidad_parcial' WHERE id_lote_local = $id_lote_local");  
         
        return $res;
       


    }



    public static function actualiza_lote_cantidad($nuevo,$id_lote_local = null){
        global $baseDatos;
        if ($id_lote_local == null) {
            # code...
            $id_lote = $_SESSION["art_lote_local"]->getId_lote()->getId_lote();
        }
        else{

            $id_lote = $id_lote_local;
        }
        
         

        $sql = "UPDATE `art_lote` SET `cantidad_total`=$nuevo 
                WHERE `id_lote` = $id_lote";
        $res = $baseDatos->query($sql);
        
         
        return $res; 
         
    }

    public function getId_lote_local()
    {
        return $this->id_lote_local;
    }
    
    public function setId_lote_local($id_lote_local)
    {
        $this->id_lote_local = $id_lote_local;
        return $this;
    }

    public function getId_lote()
    {
        return $this->id_lote;
    }
    
    public function setId_lote($id_lote)
    {
        $this->id_lote = $id_lote;
        return $this;
    }
    public function getId_local()
    {
        return $this->id_local;
    }
    
    public function setId_local($id_local)
    {
        $this->id_local = $id_local;
        return $this;
    }
    public function getCantidad_parcial()
    {
        return $this->cantidad_parcial;
    }
    
    public function setCantidad_parcial($cantidad_parcial)
    {
        $this->cantidad_parcial = $cantidad_parcial;
        
    }

    public function getId_carga()
    {
        return $this->id_carga;
    }
    
    public function setId_carga($id_carga)
    {
        $this->id_carga = $id_carga;
        return $this;
    }

}