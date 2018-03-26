var art_lote_local_  = 0;
var id_lote  = 0;

function stock_art(obj){
    buscar_art_(obj.id,1);
    id_lote = obj.id;
    $('#stock_art').modal();
}

function precio_art(obj){
    $('#precio_art').modal();
    $("#error_precio_art").hide();
    $("#exito_precio_art").hide();
    buscar_art_(obj.id,2);
    id_lote = obj.id;
  
}

function cargar_stock_art_cerrar_model(){
    //$("#exito_carga_art").hide();
    //$("#error_carga_art").hide();

    $("#exito_carga_art").fadeOut(5);
    $("#error_carga_art").fadeOut(5);
    $("#advertencia_carga_art").fadeOut(5);
}

function precio_art_cerrar_model(){
    //$("#exito_carga_art").hide();
    //$("#error_carga_art").hide();

    $("#error_precio_art").fadeOut(5);
    $("#exito_precio_art").fadeOut(5);
     
}

function cargar_stock_art(obj){
    $('#modal_mv_art').modal();
    $('#codigo_art').val(obj.id);
    $("#exito_carga_art").hide();
    $("#error_carga_art").hide();
    $("#advertencia_carga_art").hide();

    $('#cantidad_mv').val('');
    //buscar_art_(obj.id,2);
    id_lote = obj.id;
  
}

function cargar_codigobarras_art_cerrar_model(){
    //$("#exito_carga_art").hide();
    //$("#error_carga_art").hide();

    $("#exito_codigobarras_art").fadeOut(5);
    $("#error_codigobarras_art").fadeOut(5);
    $("#advertencia_codigobarras_art").fadeOut(5);
}

function cargar_codigo_art(obj){
    id_lote = obj.id;
    $('#modal_codigobarras_art').modal();
    $('#codigo_art_codigobarras').val(obj.id);
    $("#exito_codigobarras_art").hide();
    $("#error_codigobarras_art").hide();
    $("#advertencia_codigobarras_art").hide();
    $('#codigo_barras_art').val('');
    
    $('input[name=codigo_barras_art]').focus();
    //$('input:text:visible:first', '#codigo_barras_art').focus();
    //$('#codigo_barras_art').focus();
    //$('#codigo_barras_art').select();
}



function mv_art(obj){
    console.log("MvModal");
    $('#modal_mv_art').modal();
    
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
             
            var valores = JSON.parse(response);
             
            if (valores.status == 'ok') {
                 $("#exito_precio_art").show();
            }else{
                 $("#error_precio_art").show();
            }

        }); 
        setTimeout(precio_art_cerrar_model, 4000);
    });

    $('#btn_mv_art_cargar').click(function(){
        console.log("Cargar_Articulos");
        let codigo = $("#codigo_art").val();
        let tipo_mv = 'carga';
        let local_mv = $("#local_mv option:selected").val();
        let cantidad_mv = $("#cantidad_mv").val();
        let detalle = $("#detalle_mv").val();
        console.log("Id_lote");
        console.log(codigo);
        console.log("cantidad");
        console.log(cantidad_mv);
        console.log("tipo_mv");
        console.log(tipo_mv);
        console.log("detalle");
        console.log(detalle);
        console.log("id_local");
        console.log(local_mv);
        var params = {
                id_lote: codigo,
                cantidad: cantidad_mv,
                tipo_mv: tipo_mv,
                detalle : detalle,
                id_local : local_mv,
                opcion: 6
            };
        $.get("template/venta_/ajax_lista.php", params, function (response) {
            console.log(response);
            var valores = JSON.parse(response);
            console.log(valores);
            if (valores.status == 'ok') {
                console.log("Exito");
                console.log(valores);
                $("#exito_carga_art").show();
            }else{
                console.log("Sin Exito");
                console.log(valores);
                if (valores.status == 'err2') {
                    $("#advertencia_carga_art").show();
                }else{
                    $("#error_carga_art").show();
                }
                
                
            }
        }); 
        setTimeout(cargar_stock_art_cerrar_model, 4000);
    });

    $('#btn_mv_art_codigobarras').click(function(){
        console.log("Cargar Codigo de Barras");
        let codigo = $("#codigo_art").val();
        let tipo_mv = 'codigo_barras';
        let codigobarras = $("#codigo_barras_art").val();

        console.log("Id_lote");
        console.log(codigo);
        console.log("tipo_mv");
        console.log(tipo_mv);
        console.log("codigobarras");
        console.log(codigobarras);

        var params = {
                id_lote: codigo,
                tipo_mv: tipo_mv,
                codigobarras : codigobarras,
                opcion: 7
            };
        $.get("template/venta_/ajax_lista.php", params, function (response) {
            console.log(response);
            var valores = JSON.parse(response);
            console.log(valores);
            if (valores.status == 'ok') {
                console.log("Exito");
                console.log(valores);
                $("#exito_codigobarras_art").show();
            }else{
                console.log("Sin Exito");
                console.log(valores);
                if (valores.status == 'err2') {
                    $("#advertencia_codigobarras_art").show();
                }else{
                    $("#error_codigobarras_art").show();
                }
                
                
            }
        }); 
        setTimeout(cargar_codigobarras_art_cerrar_model, 4000);
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
