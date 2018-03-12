


$(document).ready(function()
    {   
       

});

function mostrar_art(obj){
         
    console.log("Comienza");
    $('#'+obj.id).show();

        $("#lista_art_foto div").each(function(){
             
            if (obj.id != this.id) {
              $('#'+this.id).hide();
            }

          });

    }

function detalles_art(obj){
   
  buscar_art(obj.id);
}  

function buscar_art(id_art){
  var params = {
        lote: id_art
        }; 
   $.get("template/venta_/ajax_autoventa.php", params, function (response) {  
        
          var json = JSON.parse(response);
           
           
          if (json.status == 'ok'){
            console.log("IF");
          }else{
            console.log(json[0]);
            articulo_nombre = json[0];
            costo = json[1];
            importe = json[2];
            moneda = 1;
           
            var precio_aux = (parseFloat(importe) * parseFloat(costo)) / 100;
             
            var precio_final = (precio_aux * moneda) + parseFloat(costo);

            id_input = "cantidad"+numero;
            id_input2 = "borrar"+numero;
            input = "<input id='"+id_input+"' type='number' size='5' value='1' min='1' onchange='actualiza_cantidad(this)'>";
            button = "<button id="+id_input2+" type='button' onclick='borrar_fila(this)' class='btn btn-danger'>X</button>";
            numero_col = numero + 1;
            var fila="<tr id="+numero+"><td>"+numero_col+"</td><td>"+articulo_nombre+"</td><td WIDTH='10'>"+input+"</td><td>"+parseFloat(precio_final.toFixed(2))+"</td><td>"+button+"</td></tr>";
            $('#venta_total').before(fila);
          }
          
         
      }); 
}