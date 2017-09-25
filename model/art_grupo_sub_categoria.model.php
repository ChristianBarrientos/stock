<?php
class art_grupo_sub_categoria {
	
	private $id_gsct;
    private $id_sub_categoria;

    public function __construct($id_gsct, $id_sub_categoria)
    {
        $this->id_gsct = $id_gsct;
        $this->id_sub_categoria = $id_sub_categoria;
         
    
       
    }

    public static function obtener_sub_categoria($id_gsct){
        global $baseDatos;
        
        $res = $baseDatos->query("SELECT * FROM art_grupo_sub_categoria WHERE id_gsct = $id_gsct");  

        $res_fil = $res->fetch_assoc();

        if (count($res_fil) != 0) {
            $datos_sub_cat = art_sub_categoria::obtener_sub_categoria($res_fil['id_sub_categoria']);

            return $datos_sub_cat;
        }
        else{
            return false;
        }
    }


    public function getId_gsct()
    {
        return $this->id_gsct;
    }
    
    public function setId_gsct($id_gsct)
    {
        $this->id_gsct = $id_gsct;
        return $this;
    }

    public function getId_sub_categoria()
    {
        return $this->id_sub_categoria;
    }
    
    public function setId_sub_categoria($id_sub_categoria)
    {
        $this->id_sub_categoria = $id_sub_categoria;
        return $this;
    }
}
?>