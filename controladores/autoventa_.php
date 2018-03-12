
<?php

class Autoventa_Controller{

	public static function inicio(){

		if (isset($_SESSION["usuario"])){
	        if ($_SESSION["permiso"] == 'ADMIN') {
	            $tpl = new TemplatePower("template/autoventa.html");
	            $tpl->prepare();
	            $categorias = articulo::obtener_articulos();
	             
	            foreach ($categorias as $key => $value) {
	            	$tpl->newBlock("categorias");
                    $id_cat = $value->getId_articulo();
                    $nombre_cat = $value->getNombre();
                    $tpl->assign("id_cat",$id_cat);
                    $tpl->assign("nombre_cat",$nombre_cat	);
                    
	            }
	            $art_categoria = array();
	            foreach ($_SESSION['lotes'] as $key => $value) {
	            	 
	            	$id_categotia = $value->getId_art_conjunto()->getId_articulo()->getId_articulo();
	            	$nombre = $value->getId_art_conjunto()->getId_marca()->getNombre();
                    $tipo = $value->getId_art_conjunto()->getId_tipo()->getNombre();
                    $nombre_ = $nombre.','.$tipo;
                    $nombre_ = str_replace(' ','',$nombre_);

                    $id_art_lote = $value->getId_lote();
                    $array_aux['id_cat'] = $id_categotia;
                    $array_aux['id_lote'] = $id_art_lote;
                    $array_aux['nombre'] = $nombre_;
                    
                    if ($value->getId_art_fotos() !=  null) {
                    	 
                    	$array_aux['pat_img'] = $value->getId_art_fotos()[0]->getPath_foto();
                    }else{
                    	$array_aux['pat_img'] = false;
                    }
                    
                    $art_categoria[] = $array_aux;
                    
	            }

				uasort($art_categoria, 'Autoventa_Controller::sort_by_orden');
				$una_sola_vez = true;
				foreach ($art_categoria as $key => $value) {
					
					if ($una_sola_vez) {
						$bandera = $value['id_cat'];
						$una_sola_vez = false;

						$tpl->newBlock("lista_art");
						$tpl->assign("id_categoria",$value['id_cat']);
	
					}

					if ($bandera == $value['id_cat']) {
						$tpl->newBlock("lista_art_foto");
						$tpl->assign("id_art",$value['id_lote']);

						if ($value['pat_img']) {
							$tpl->newBlock("imagen_lista_art");
							$tpl->assign("pat_imagen",$value['pat_img']);
							 
						}else{
							$tpl->newBlock("imagen_lista_art");
							 
							$tpl->assign("pat_imagen",'imagenes/system/img_no_disp.jpg');
						}
						

					}else{
						$una_sola_vez = true;
					}
					

					
				}
				 
	           
	    	}

	       
	   }
	   else{
	       return Ingreso_Controller::salir();
	   }

	   return $tpl->getOutputContent();

	}
	public static function sort_by_orden ($a, $b) {
				    return $a['id_cat'] - $b['id_cat'];
				}
}
?>