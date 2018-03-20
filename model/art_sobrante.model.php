<?php
class art_sobrante {

	private $id;
	private $cantidad;
	private $usuario;
	private $descripcion;

public function __construct($id, $cantidad, $usuario,$descripcion)
    {
        $this->id = $id;
        $this->cantidad = $cantidad;
        $this->usuario = $usuario;
        $this->descripcion = $descripcion;
       
    
       
    }

public static function  alta ($cantidad, $usuario,$fecha_hora, $descripcion = 'null'){
		global $baseDatos;
        
        //$id_contacto_tel = $this::alta_contacto($telefono);
        $id_categoria = art_sobrante::ultimo_id();
        
        $sql = "INSERT INTO `art_sobrante`(`id_sobrante`, `cantidad`, `usuario`, `fecha_hora`, `descripcion`) VALUES (0,$cantidad,$usuario,'$fecha_hora','$descripcion')";
        $res = $baseDatos->query($sql);
        if ($res) {
             
            return $id_categoria;
        }else{
            printf("Errormessage: %s\n", $baseDatos->error);
            return false;
        }
	}

public static function ultimo_id(){
        global $baseDatos;
        $sql_fecha_ab = "SELECT AUTO_INCREMENT AS LastId FROM information_schema.tables WHERE TABLE_SCHEMA='stock' AND TABLE_NAME='art_sobrante'";
        $res = $baseDatos->query($sql_fecha_ab);
        $res_fil = $res->fetch_assoc();
        
        return $res_fil['LastId'];
    }




public static function obtener($id_sobrante){
		global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM `art_sobrante`");  

        $filas = $res->fetch_all(MYSQLI_ASSOC);
        if (count($filas) != 0){
            $gastos_unicos = array();

            foreach ($filas as $clave => $valor) {

                $id_gasto_unico = gs_gasto_unico::generar($valor['id_gasto_unico']);
                
                
                $gastos_unicos[] = $id_gasto_unico;

            }
            $ggs = new gs_grupo($id_ggs,$gastos_unicos);
            return $ggs;
        }
        else{
           
            return false;
        }

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

	public function getCantidad()
	{
	    return $this->cantidad;
	}
	
	public function setCantidad($cantidad)
	{
	    $this->cantidad = $cantidad;
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

	public function getDescripcion()
	{
	    return $this->descripcion;
	}
	
	public function setDescripcion($descripcion)
	{
	    $this->descripcion = $descripcion;
	    return $this;
	}

}