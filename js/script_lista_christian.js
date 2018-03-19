var art_lote_local_  = 0;
var id_lote  = 0;

function stock_art(obj){
    buscar_art_(obj.id,1);
    id_lote = obj.id;
    $('#stock_art').modal();
}

function precio_art(obj){
    $('#precio_art').modal();
    buscar_art_(obj.id,2);
    id_lote = obj.id;
  
}

function buscar_art_($id_lote,$opcion){
    let Datos = new FormData();
    switch($opcion) {
        case 1:
        //Buscar cantidad   
            
            //Datos.append("id_lote",,"opcion",1);
            buscar_art_lote($id_lote,1);
            break;
        case 2:
        //Buscar Precio
            buscar_art_lote($id_lote,2);
            
            break;
        default:
            alert("Opcion NO valida");
    }

   
}

function buscar_art_lote(id,opc){
    
    var params = {
          id_lote: id,
          opcion: opc,
          
        };
    $.get("template/venta_/ajax_lista.php", params, function (response) {


        var valores = JSON.parse(response);
        console.log('Respuesta:');
        console.log(valores);
        if (valores.status == 'ok') {

            art_lote_local_ = valores.result;
            switch(opc) {
                case 1:
                    Object.keys(valores.result).forEach(function (key) {
                         
                        let id_local = valores.result[key]['id_local'];
                        let cantidad = valores.result[key]['cantidad_parcial'];
                        $("#stock_"+id_local+"").val(cantidad);
                        //$("#stock_"+id_lote+"").text(total_ventas.toFixed(2));
                        
                    });
                    break;
                case 2:
                    console.log("Precio");
                    console.log(valores.result);

                    let costo = valores.result[0]['precio_base'];
                    let importe = valores.result[0]['importe'];
                    let moneda = valores.result[0]['id_moneda'];
                    let valor_moneda = valores.result[1]['valor_moneda'];
                    $("#costo_art").val(costo);
                    //$( "#art_moneda" ).select(moneda);

                    if (moneda == 1) {
                         
                        $("#art_moneda option[value="+ 1 +"]").attr("selected",true);
                    }else{
                        
                        $("#art_moneda option[value="+ 1 +"]").attr("selected",false);
                    }

                    if (moneda == 2) {
                         
                        $("#art_moneda option[value="+ 2 +"]").attr("selected",true);
                    }else{
                         
                        $("#art_moneda option[value="+ 2 +"]").attr("selected",false);
                    }

                    $("#art_moneda").selectpicker("refresh"); 
                    
                    //$("#art_moneda").val(moneda);
                    $("#importe_art").val(importe);
                    precio_final = (parseFloat((costo * valor_moneda)) * parseFloat(importe));
                    

                    $("#prc_final").val(Math.ceil(precio_final));
                    
                    break;
                default:
                    alert("Opcion NO valida");
            }

        }else{
            $("#msj_noencontrado").show();
        }
         

          
        
    });

}



$(document).ready(function(){   
    $('#act_precio').click(function(){
         
        let costo = parseFloat($("#costo_art").val());
        let importe = parseFloat($("#importe_art").val());
        let moneda = $("#art_moneda option:selected").val();

        var params = {
                id_lote: id_lote,
                costo: costo,
                id_moneda: moneda,
                importe: importe,
                opcion: 4
            };
        $.get("template/venta_/ajax_lista.php", params, function (response) {
            console.log(response);
            var valores = JSON.parse(response);
            console.log(valores);
            if (valores.status == 'ok') {
                    
            }else{
                $("#msj_noencontrado").show();
            }
        }); 

    });


    $('#act_stock').click(function(){
        console.log("Act Stock");
        console.log("Id_lote:"+id_lote);
        let indice = 1;
        Object.keys(art_lote_local_).forEach(function (key) {
            let new_stock = $("#stock_"+indice).val();

            console.log('Valor Stock ID:'+"#stock_"+key+'NEWSTOCK:'+ new_stock);
            var params = {
              id_lote: id_lote,
              opcion: 3,
              id_lote_local: art_lote_local_[key]['id_lote_local'],
              stock : $("#stock_"+indice).val()
              
            };
            indice = indice + 1;
            
            $.get("template/venta_/ajax_lista.php", params, function (response) {
                console.log(response);
                var valores = JSON.parse(response);
                console.log(valores);
                if (valores.status == 'ok') {
                    art_lote_local_ = valores.result;
                    Object.keys(valores.result).forEach(function (key) {
                         
                        let id_lote = valores.result[key]['id_local'];
                        let cantidad = valores.result[key]['cantidad_parcial'];

                        $("#stock_"+id_lote+"").val(cantidad);
                        //$("#stock_"+id_lote+"").text(total_ventas.toFixed(2));
                        
                    });
                }else{
                    $("#msj_noencontrado").show();
                }
            });           
        }); 
    });
});
