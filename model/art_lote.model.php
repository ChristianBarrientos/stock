<?php
class art_lote {
	
	private $id_lote;
    private $id_proveedor;
    private $cantidad;
    private $id_art_conjunto;
    private $id_cb;
    private $id_gc;
    private $id_art_fotos;
    private $precio_base;
    private $importe;
 

    public function __construct($id_lote, $id_proveedor, $cantidad,$id_art_conjunto,$id_cb,$id_gc,$id_art_fotos,$precio_base,$importe)
    {
        $this->id_lote = $id_lote;
        $this->id_proveedor = $id_proveedor;
        $this->cantidad = $cantidad;
        $this->id_art_conjunto = $id_art_conjunto;
        $this->id_cb = $id_cb;
        $this->id_gc = $id_gc;
        $this->id_art_fotos = $id_art_fotos;
        $this->precio_base = $precio_base;
        $this->importe = $importe;
      
    }

    public static function alta_art_lote($id_art_conjunto, $cantidad_total, $codigo_barras,$id_art_fotos,$precio_base,$importe,$id_proveedor = 'null' ,$id_gc= 'null',$descripcion = 'null'){
        global $baseDatos;
       
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_lote = art_lote::ultimo_id_lote();
        
        $sql = "INSERT INTO `art_lote`(`id_lote`, `id_art_conjunto`, `id_provedor`, `cantidad_total`, `codigo_barras`, `id_gc`, `descripcion`, `id_art_fotos`, `precio_base`, `importe`) VALUES (0,$id_art_conjunto,$id_proveedor,$cantidad_total,'$codigo_barras',$id_gc,'$descripcion',$id_art_fotos,$precio_base,$importe)";
        $res = $baseDatos->query($sql);
        //printf("Errormessage: %s\n", $baseDatos->error);
        if ($res) {
             
            return $id_lote;
        }else{
             
            return false;
        }

    }
    public static function ultimo_id_lote(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_lote'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

    public static function generar_lote($id_lote){
        global $baseDatos;

        $res = $baseDatos->query("SELECT * FROM `art_lote` WHERE id_lote = $id_lote");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $id_art_conjunto = art_lote::obtener_art_conjunto($res_fil['id_art_conjunto']);
            if ($res_fil['id_provedor'] != null) {
                $prvd = proveedor::generar_prvd($res_fil['id_provedor']);
            }
            else{
                $prvd = 'null';
            }

            $cb = ($res_fil['codigo_barras']);
            if ($res_fil['id_gc'] != null) {
                # code...
                $gc = art_grupo_categoria::generar_gc($res_fil['id_gc']);
            }
            else{
                $gc = null;
            }
            
            if ($res_fil['id_art_fotos'] != null) {
                # code...
                $fotos = art_fotos::generar_fotos($res_fil['id_art_fotos']);
            }else{
                $fotos = null;
            }

            //$lote = new art_local($res_fil['id_local'],$res_fil['nombre'],$res_fil['descripcion'],$zona,$cant_empl);
            //$lote = new art_local($res_fil['id_local'],$prvd,$res_fil['cantidad_total'],$id_art_conjunto,$cb,$gc);
            $lote = new art_lote($res_fil['id_lote'],$prvd,$res_fil['cantidad_total'],$id_art_conjunto,$res_fil['codigo_barras'],$gc,$fotos,$res_fil['precio_base'],$res_fil['importe']);

            
            return $lote;
        }
        else{
            
            return false;
        }
    }

    public static function obtener_art_conjunto($id_art_conjunto){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_conjunto` WHERE id_art_conjunto = $id_art_conjunto");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            $conjunto = art_conjunto::generar_conjunto($res_fil['id_art_conjunto']);   
            
            //$id_lote, $id_proveedor, $cantidad,$id_art_conjunto,$id_cb,$id_gc
            /*$lote = new art_local($res_fil['id_local'],$prvd,$res_fil['cantidad_total'],$conjunto,$cb,$gc);*/
            return $conjunto;
        }
        else{
            
            return false;
        }
    }

    public static function obtener_cantidad_total($id_lote){
        global $baseDatos;
        $res = $baseDatos->query("SELECT * FROM `art_lote` WHERE id_lote = $id_lote");  
        $res_fil = $res->fetch_assoc();
        if (count($res_fil) != 0) {
            
            return $res_fil['cantidad_total'];
        }
        else{
            
            return false;
        }
    }

    public static function update_cantidad_total($id_lote,$cantidad_total){
        //obtener empleados por local
        global $baseDatos;
       
        $res = $baseDatos->query(" UPDATE `art_lote` SET `cantidad_total`='$cantidad_total' WHERE id_lote = $id_lote");  
         
        return $res;
    }

    public static function update($id_lote,$fila,$valor_nuevo){
        //obtener empleados por local
        global $baseDatos;
        switch ($fila) {
            case 'precio_base':
                # code...
                $res = $baseDatos->query(" UPDATE `art_lote` SET `precio_base`='$valor_nuevo' WHERE id_lote = $id_lote");  
                break;
             case 'importe':
                # code...
                $res = $baseDatos->query(" UPDATE `art_lote` SET `importe`='$valor_nuevo' WHERE id_lote = $id_lote");  
                break;
             case 'cantidad_total':
                # code...
                $res = $baseDatos->query(" UPDATE `art_lote` SET `cantidad_total`='$valor_nuevo' WHERE id_lote = $id_lote");  
                break;
            default:
                # code...
                break;
        }
        
         
        return $res;
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

    public function getId_proveedor()
    {
        return $this->id_proveedor;
    }
    
    public function setId_proveedor($id_proveedor)
    {
        $this->id_proveedor = $id_proveedor;
        return $this;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
         
    }

    public function getId_art_conjunto()
    {
        return $this->id_art_conjunto;
    }
    
    public function setId_art_conjunto($id_art_conjunto)
    {
        $this->id_art_conjunto = $id_art_conjunto;
        return $this;
    }

    public function getId_cb()
    {
        return $this->id_cb;
    }
    
    public function setId_cb($id_cb)
    {
        $this->id_cb = $id_cb;
        return $this;
    }

    public function getId_gc()
    {
        return $this->id_gc;
    }
    
    public function setId_gc($id_gc)
    {
        $this->id_gc = $id_gc;
        return $this;
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

    public function getId_art_fotos()
    {
        return $this->id_art_fotos;
    }
    
    public function setId_art_fotos($id_art_fotos)
    {
        $this->id_art_fotos = $id_art_fotos;
        return $this;
    }


    public function getPrecio_base()
    {
        return $this->precio_base;
    }
    
    public function setPrecio_base($precio_base)
    {
        $this->precio_base = $precio_base;
        return $this;
    }

    public function getImporte()
    {
        return $this->importe;
    }
    
    public function setImporte($importe)
    {
        $this->importe = $importe;
        return $this;
    }

}
?>