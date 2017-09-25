 <?php
 include("include_config.php");
 $mensaje = '';
                $nombre = ucwords(strtolower($_POST['art_general']));
                #echo $nombre;
                
                $res = articulo::alta_art($nombre);
                
                echo $mensaje;

             ?>