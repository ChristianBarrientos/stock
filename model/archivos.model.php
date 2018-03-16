<?php
class archivo {

	private $id;
	private $nombre;
	private $titulo;
	private $ubicacion;
	private $size;
	private $tipo;
	private $archivo_tmp_name;

public static function  cargar_datos ($nom, $size, $tipo, $archivo_tmp_name, $art_nombre,$ubi = null){
		//global $baseDatos;
		if (!$size== 0) {
            $ruta = $archivo_tmp_name;
            $tipo_a = substr(strrchr($nom, "."), 1);
            //nombre archivo destino $_SESSION["usuario"]->getId_user()
            $autor = $_SESSION["usuario"]->getId_user();
            if ($ubi == null) {
            	$wind = "imagenes/art/";
            	$linux = "/var/www/html/stock/imagenes/art";
            	$destino =  $wind. $autor . "_" . "_" . $art_nombre . "." . $tipo_a;
            	
            }else{
            	 
            	$wind = $ubi;
            	$linux = "/var/www/html/stock/".$ubi;
            	$destino =  $wind.  $art_nombre."." . $tipo_a;
            	 
            }
            
            
			
		 
            copy($ruta, $destino);
            $archivo->ubicacion = $destino;
        }

		$archivo->nombre = $nom;
		$archivo->size = $size;
		$archivo->tipo = substr(strrchr($nom, "."), 1);
		$archivo->archivo_tmp_name = $archivo_tmp_name;
		$archivo->art_nombre = $art_nombre;
		
		return $destino;

		//$sql = "SELECT MAX(id) AS id FROM archivos";
		//$this -> id = $sql +1;
	}
public static function carga_bd(){
		

	}


	function getnombre (){
		return $this -> nombre;
	}
	function getsize (){
		return $this -> size;
	}
	function gettipo (){
		return $this -> tipo;
	}
	function getTitulo (){
		return $this -> titulo;
	}
	function getarchivo_tmp_name (){
		return $this -> archivo_tmp_name;
	}
	function getId (){
		return $this -> id;
	}

	function getUbicacion (){
		return $this -> ubicacion;
	}

}