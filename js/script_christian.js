

$(document).ready(function()
    {   //Buscador sincronico
        //document.getElementById('art_cantidad_total').focus();
        $('#filtrar').keyup(function () {
                     
                    var rex = new RegExp($(this).val(), 'i');
                    $('.buscar tr').hide();
                    $('.buscar tr').filter(function () {
                        return rex.test($(this).text());
                    }).show();
 
                })


       
        $("#seleccionar_local_art input").on("click",function(e) {
               
              // Aquí pones el código que se ejecutará.
              
              var valor = $(this).attr('value');
               
              if ($(this).prop('checked') ) {
                     
                    $('#'+ valor).show(); //muestro mediante id
                    //$('#' + valor).css('display','block');
                }

            else{
                    //$('#' + valor).hide(); //oculto
                     
                    $('#' + valor).hide().prop('required',false)
                    //$('#' + valor).css('display','none');
                }
            });
        /*$("#check_art_locales_"),click(function(){
            
            alert("ok")
            
            // Convertimos el HTMLCollection a array
            //console.log([].slice.call(locales));
            
            for (var i = 0 ; i < locales.length; i++) {
                //$("#art_local_cantidad_" + i).on('keyup', function(){
                 
                alert(div_locales[i]);
                 
                
                //}).keyup();
            }
           
          
        });*/
      
        //PARA VERIFICAR SI LA CANTIDAD TODAL ES MENOR QUE LA CANTIDAD PARCIAL
       $("#art_carga_btn").click(function(){
            var cantidad_parcial_total = 0;
            var cantidad_total = parseInt($("#art_cantidad_total").val());

            var locales = document.getElementsByClassName("art_local_cantidad_");
            
            //console.log($(locales).parents('div'));
            var padres = $(locales).parents('div');
            // Convertimos el HTMLCollection a array
            //console.log([].slice.call(locales));
            var id_div =  [];

            for (var i = 0; i < padres.length; i++) {
                var id_padre = $(padres[i]).attr("id");
                if(/check_art_locales_/.test(id_padre)) {
                    id_div.push(id_padre);
                }                
                
            }
            
            var locales_check =  $(".check_art_locales");
            var locales_check_ok = [];
           
            $(".check_art_locales").each(function(){
                var value_check_ok = $(this).val()
                    if ($(this).prop('checked') ) {
                        var siok = value_check_ok.substring(18,20);
                        locales_check_ok.push(siok);
                    }
                
            });
             
             
            for (var i = 0 ; i <= locales.length; i++) {
                 
                //$("#art_local_cantidad_" + i).on('keyup', function(){
                for (var x = 0; x <= locales_check_ok.length; x++) {

                    if (locales_check_ok[x] == i) {
                        
                        var cantidad_parcial_total = cantidad_parcial_total + parseInt(locales[i-1].value);

                    }
                }
                
                 
                
                //}).keyup();
            }
            alert("aca");
            alert(cantidad_total);
            alert(cantidad_parcial_total);
            if (cantidad_total != cantidad_parcial_total ) {
                    alert("La sumatoria parcial de la distribucion por local no puede ser diferente a la cantidad total del deposito.");
                    for (var i = 0 ; i < locales.length; i++) {
                        //$("#art_local_cantidad_" + i).on('keyup', function(){
                         
                        locales[i].value = null;
                        event.preventDefault();
                        
                        //}).keyup();
                    }
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

        $("#cargar_general_btn").click(function () {
            
            $('#art_general').click();
             

        });
        //AGREGAR GENERAL ENTEROK
        $("#cargar_general_btn").click(function() {

            $('#art_general_chris').focus();

             $(document).keyup(function(e) {
                if(e.keyCode == 13) {
                    //alert("enter");

                    //document.getElementById('cargar_art_general').click();
                    //$('#cargar_art_marca').trigger('click');
                    //alert("ok")
                    $('#cargar_art_general').trigger('click');
                    $('#cerrar_modal').trigger('click');
                }
            });


        });

        //AGREGAR MARCA ENTEROK
        $("#cargar_marca_btn").click(function() {

            $('#art_marca').focus();
            
             $(document).keyup(function(e) {
                if(e.keyCode == 13) {
                    //alert("enter");

                    //document.getElementById('cargar_art_marca').click();
                    //$('#cargar_art_marca').trigger('click');
                    //alert("ok")
                    $('#cargar_art_marca').trigger('click');
                    $('#cerrar_modal1').trigger('click');
                }
            });


        });

        //AGREGAR TIPO ENTEROK
        $("#cargar_tipo_btn").click(function() {

            $('#art_tipo').focus();
            
             $(document).keyup(function(e) {
                if(e.keyCode == 13) {
                    //alert("enter");

                    document.getElementById('cargar_art_tipo').click();
                    //$('#cargar_art_marca').trigger('click');
                    //alert("ok")
                    $('#cerrar_modal2').trigger('click');
                }
            });


        });

        $("#cargar_art_general").click(function () {
            //$('#art_general_chris').focus(function(){
               
            //});
            //saco el valor accediendo a un input de tipo text y name = nombre
            
            //var pcart =document.getElementById('art_general_chris');
            //setTimeout(function(){pcart.focus();}, 1);
            
            //$('#art_general_chris').on( "focus", handler )
             
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
        //Finalizar Venta, enviar valor
        $('.forma_pago_select').on('change',function() {
            //alert('aca');
            var selected = $(':selected', this);

            var optgroup = selected.parent().attr('label');
            var acas = selected.text();
             
            //var option = $(this).val();
            //alert(optgroup);
            //alert(selected.closest('optgroup').attr('label'));
             
            //var input = ','.concat(option);
            var option = $('#forma_pago_select').find(":selected").text();
             

            $("#medio_art_venta").val(optgroup);

            $("#cuotas_art_venta").val(option);
             
 
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