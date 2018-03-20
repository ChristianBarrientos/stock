<?php
class art_movimiento {

	private $id;
	private $id_art_lote;
	private $cantidad;
	private $tipo_mv;

	private $fecha_hora;
	private $usuario;
	private $descripcion;
	private $id_local;
	private $id_local_2;
	private $id_prvd;
	

public function __construct($id, $id_art_lote, $cantidad,$tipo_mv,$fecha_hora,$usuario,$descripcion,$id_local,$id_local_2,$id_prvd)
    {
        $this->id = $id;
        $this->id_art_lote = $id_art_lote;
        $this->cantidad = $cantidad;
        $this->tipo_mv = $tipo_mv;
        $this->fecha_hora = $fecha_hora;
        $this->usuario = $usuario;
        $this->descripcion = $descripcion;
        $this->id_local = $id_local;
        $this->id_local_2 = $id_local_2;
        $this->id_prvd = $id_prvd;
       
    }
    
public static function  alta ($id_art_lote, $cantidad,$tipo,$fecha_hora,$usuario,$id_art_lote_local,$id_art_lote_local_2 = null,$id_prvd= null,$descripcion = 'null'){
		global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $art_movimiento = art_movimiento::ultimo_id();
        
        $sql = "INSERT INTO `art_movimiento`(`id_movimiento`, `id_art_lote`, `cantidad`, `tipo`, `fecha_hora`, `usuario`, `detalle`, `id_art_lote_local`,`id_art_lote_local_2`,`id_prvd` ,`descripcion`) VALUES (0,$id_art_lote,$cantidad,$tipo,'$fecha_hora',$usuario,'$detalle',$id_art_lote_local,$id_art_lote_local_2,$id_prvd'$descripcion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $art_movimiento;
        }else{
            printf("Errormessage: %s\n", $baseDatos->error);
            return false;
        }
	}

public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_movimiento'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }

public static function obtener($id_movimiento){
		global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_movimiento`");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $movimientos = array();

            foreach ($filas as $clave => $valor) { 
                 
                
                $movimientos[] = new art_movimiento($valor['id_movimiento'],$valor['id_art_lote'],$valor['cantidad'],$valor['tipo'],$valor['fecha_hora'],$valor['usuario'],$valor['id_art_lote_local'],$valor['id_art_lote_local_2'],$valor['id_prvd'],$valor['descripcion']);
            }
             
            return $movimientos;
        }
        else{
           
            return false;
        }
	}
 
public static function alta_carga($id_art_lote,$id_local,$cantidad,$fecha_hora,$detalle){

	$tipo = 'carga';
	$usuario = $_SESSION['usuario']->getId_user();

	global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $art_movimiento = art_movimiento::ultimo_id();
        
        $sql = "INSERT INTO `art_movimiento`(`id_movimiento`, `id_art_lote`, `cantidad`, `tipo`, `fecha_hora`, `usuario`, `id_art_lote_local`, `id_art_lote_local_2`, `id_prvd`, `descripcion`) VALUES (0,$id_art_lote,[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10])";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $art_movimiento;
        }else{
            printf("Errormessage: %s\n", $baseDatos->error);
            return false;
        }
	

}

public static function decomiso($id_art_lote, $cantidad,$fecha_hora,$usuario){
	$tipo_mv = 'decomiso';
	$usuario = $_SESSION['usuario']->getId_user();
}

public static function devuelto($id_art_lote, $cantidad,$fecha_hora,$usuario){
	$tipo_mv = 'devuelto';
	$usuario = $_SESSION['usuario']->getId_user();
}

public static function traslado($id_art_lote, $cantidad,$fecha_hora,$usuario){
	$tipo_mv = 'traslado';
	$usuario = $_SESSION['usuario']->getId_user();
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

	public function getId_lote_2()
	{
	    return $this->id_lote_2;
	}
	
	public function setId_lote_2($id_lote_2)
	{
	    $this->id_lote_2 = $id_lote_2;
	    return $this;
	}

	public function getDescripcion()
	{
	    return $this->descripcion;
	}
	
	public function setDescripcion($descripcion)
	{
	    $this->descripcion = $descripcion;
	    return $this;
	}

	public function getUsuario()
	{
	    return $this->usuario;
	}
	
	public function setUsuario($usuario)
	{
	    $this->usuario = $usuario;
	    return $this;
	}
	public function getFecha_hora()
	{
	    return $this->fecha_hora;
	}
	
	public function setFecha_hora($fecha_hora)
	{
	    $this->fecha_hora = $fecha_hora;
	    return $this;
	}
	public function getId()
	{
	    return $this->id;
	}
	
	public function setId($id)
	{
	    $this->id = $id;
	    return $this;
	}

	public function getArt_lote()
	{
	    return $this->id_art_lote;
	}
	
	public function setArt_lote($id_art_lote)
	{
	    $this->id_art_lote = $id_art_lote;
	    return $this;
	}

	public function getCantidad()
	{
	    return $this->cantidad;
	}
	
	public function setCantidad($cantidad)
	{
	    $this->cantidad = $cantidad;
	    return $this;
	}

	public function getTipo_mv()
	{
	    return $this->tipo_mv;
	}
	
	public function setTipo_mv($tipo_mv)
	{
	    $this->tipo_mv = $tipo_mv;
	    return $this;
	}

}