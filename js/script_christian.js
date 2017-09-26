

$(document).ready(function()
    {
        //PARA VERIFICAR SI LA CANTIDAD TODAL ES MENOR QUE LA CANTIDAD PARCIAL
       $("#art_carga_btn").click(function(){
            var cantidad_parcial_total = 0;
            var cantidad_total = parseInt($("#art_cantidad_total").val());

            var locales = document.getElementsByClassName("art_local_cantidad_");

            // Convertimos el HTMLCollection a array
            //console.log([].slice.call(locales));
            
            for (var i = 0 ; i < locales.length; i++) {
                //$("#art_local_cantidad_" + i).on('keyup', function(){
                 
                var cantidad_parcial_total = cantidad_parcial_total + parseInt(locales[i].value);
                 
                
                //}).keyup();
            }
           
            if (cantidad_total < cantidad_parcial_total ) {
                    alert("La sumatoria parcial de la distribucion por local no puede ser diferente a la cantidad total del deposito.");
                    for (var i = 0 ; i < locales.length; i++) {
                        //$("#art_local_cantidad_" + i).on('keyup', function(){
                         
                        locales[i].value = 0;
                         
                        
                        //}).keyup();
                    }
                }
            if (cantidad_total > cantidad_parcial_total ) {
                    alert("La sumatoria parcial de la distribucion por local no puede ser diferente a la cantidad total del deposito.");
                    for (var i = 0 ; i < locales.length; i++) {
                        //$("#art_local_cantidad_" + i).on('keyup', function(){
                         
                        locales[i].value = 0;
                         
                        
                        //}).keyup();
                    }
                }
        });
      
      for (var i = 0 - 1; i <= 0; i++) {
          {id_local}
          $("#check_art_locales_"+,click(function(){
            
            alert("ok")
            var locales = document.getElementsByClassName("art_local");
            var div_locales = document.getElementsByClassName("div_art_local");
            // Convertimos el HTMLCollection a array
            //console.log([].slice.call(locales));
            
            for (var i = 0 ; i < locales.length; i++) {
                //$("#art_local_cantidad_" + i).on('keyup', function(){
                 
                alert(div_locales[i]);
                 
                
                //}).keyup();
            }
           
          
        });
      }
      //mostrar ocultar div locales
      $("#check_art_locales").click(function(){
            
            alert("ok")
            var locales = document.getElementsByClassName("art_local");
            var div_locales = document.getElementsByClassName("div_art_local");
            // Convertimos el HTMLCollection a array
            //console.log([].slice.call(locales));
            
            for (var i = 0 ; i < locales.length; i++) {
                //$("#art_local_cantidad_" + i).on('keyup', function(){
                 
                alert(div_locales[i]);
                 
                
                //}).keyup();
            }
           
          
        });
        
       //GENERA PRECIO POR PORCENTAJES
        $("#art_precio_tarjeta").blur(function(){
           
            var porciento =  $(this).val();
            var preciobase = $("#art_precio_base").val();
            if (preciobase != '') {
                var precio_tarjeta_aux = (porciento * preciobase) / 100;
            
                $("#valor_calculado_tarjeta").text("Pesos Argentinos: " + (parseInt(precio_tarjeta_aux) + parseInt(preciobase)));
                $(this).val(porciento + '%')
            }
            
          
            
        });

        /*$("#art_precio_tarjeta").on('keyup', function(){
             var porciento =  $(this).val();
            var preciobase = $("#art_precio_base").val();
            if (preciobase != '') {
                var precio_tarjeta_aux = (porciento * preciobase) / 100;
            
                $("#valor_calculado_tarjeta").text("Pesos Argentinos: " + (parseInt(precio_tarjeta_aux) + parseInt(preciobase)));
                $(this).val(porciento + '%')
            }
            }).keyup();*/
 

        $("#art_precio_credito_argentino").blur(function(){
           
            var porciento =  $(this).val();
            var preciobase = $("#art_precio_base").val();
            if (preciobase != '') {
                var precio_tarjeta_aux = (porciento * preciobase) / 100;
            
                $("#valor_calculado_credito_personal").text("Pesos Argentinos: " + (parseInt(precio_tarjeta_aux) + parseInt(preciobase)));
                $(this).val(porciento + '%')
            }
            
        });

        //mOSTRAR oCULTAR pROVEEDOR
        $("#conlocal_art").click(function(){
          
            if ($('#conlocal_art').prop('checked') ) {

                $('#con_prvd_').show(); //muestro mediante id
            }
            else{
                $('#con_prvd_').hide(); //oculto
            }

            
        });

        $("#cargar_art_general").click(function () {
            //saco el valor accediendo a un input de tipo text y name = nombre
            valor = $('input:text[name=art_general]').val();
            //saco el valor accediendo al id del input = nombre
            $("#select_art_general").selectpicker();
            $("#select_art_general").append('<option value="'+valor+'" selected="">'+valor+'</option>');
            $("#select_art_general").selectpicker("refresh");
                
            $("#form_art_general").after("<p id='ok_carga_art'>Agregado!</p>");
            
            $("#cerrar_modal").click(function(){
                $("#ok_carga_art").remove();
                $('input:text[name=art_general]').val(null)
            });

        });

        $("#cargar_art_marca").click(function () {
            //saco el valor accediendo a un input de tipo text y name = nombre
            valor = $('input:text[name=art_marca]').val();
            //saco el valor accediendo al id del input = nombre
            $("#select_art_marca").selectpicker();
            $("#select_art_marca").append('<option value="'+valor+'" selected="">'+valor+'</option>');
            $("#select_art_marca").selectpicker("refresh");
            //saco el valor accediendo al class del input = nombre
            $("#form_art_marca").after("<p id='ok_carga_art'>Agregado!</p>");   
            $("#cerrar_modal1").click(function(){
                $("#ok_carga_art").remove();
                $('input:text[name=art_marca]').val(null)
            });
            

        });

         $("#cargar_art_tipo").click(function () {
            //saco el valor accediendo a un input de tipo text y name = nombre
            valor = $('input:text[name=art_tipo]').val();
            //saco el valor accediendo al id del input = nombre
            $("#select_art_tipo").selectpicker();
            $("#select_art_tipo").append('<option value="'+valor+'" selected="">'+valor+'</option>');
            $("#select_art_tipo").selectpicker("refresh");
            //saco el valor accediendo al class del input = nombre
            $("#form_art_tipo").after("<p id='ok_carga_art'>Agregado!</p>");
            $("#cerrar_moda2").click(function(){
                $("#ok_carga_art").remove();
                $('input:text[name=art_tipo]').val(null)
            });   
            

        });
});


 

 



function guardar_art_general(){
    alert("ajax!")
    $.ajax({
        url:'',
        type: 'POST',
        data:{art_marca:art_marca},
        datatype: 'jsonp',
        crossDomain: true,
        error: function(){
            alert("Error ajax?.")
        },
        success: function(){
            resu = eval("("+res+")");
            console.log(resu["respuesta"]);
            if (resu["respuesta"] == "si") {
                alert("Exito al Guardar.")
            }
            else{
                alert("Error al Guardar.")
            }
        }
    })
}