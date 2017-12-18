
//Variables Globales
var Valor_Medio_Pago;
var Medio_Pago;
var Valor_Total_Pago;

var fecha_datapicker_gasto = 1;

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

        //Añadir detalle de Gasto
        $('#uno_mas_gsdetalle').click(function () {
       

            var divclearfix = $(document.createElement('div'));
            divclearfix.addClass("clearfix");

            var container = $(document.createElement('div'));
            container.addClass("control-label  col-md-1  col-sm-1 col-xs-12");
            $(container).append('<label  for="art_tipo" value="Nombre:">Nombre:</label>');

            var container2 = $(document.createElement('div'));
            

            var container3 = $(document.createElement('div'));
            container3.addClass("col-md-3 col-sm-3 col-xs-12");
            $(container3).append('<input type="text" class="form-control" id="gs_unico_nombre" placeholder="Subtitulo del Gasto." name="gs_unico_nombre[]">');

            //container3.appendTo(container2);
            $(container2).append(container3);


            var containerval = $(document.createElement('div'));
            containerval.addClass("control-label  col-md-1  col-sm-1 col-xs-12");
            $(containerval).append('<label  for="art_tipo">Valor:</label>');

            var container2val = $(document.createElement('div'));
            

            var container3val = $(document.createElement('div'));
            container3val.addClass("col-md-3 col-sm-3 col-xs-12");
            $(container3val).append('<input type="text" class="form-control" id="gs_unico_valor" placeholder="Valor del Gasto." name="gs_unico_valor[]">');

            //container3.appendTo(container2);
            $(container2val).append(container3val);

            ////

            var containerfecha = $(document.createElement('div'));
            containerfecha.addClass("col-md-3 col-sm-3 col-xs-12");

            var containerfecha2 = $(document.createElement('div'));
            containerfecha2.addClass("input-group date");
            variable_datapicker_reference = "datetimepicker" + fecha_datapicker_gasto;
            $(containerfecha2).attr('id', variable_datapicker_reference);
            $input_fecha ='<input placeholder="AAAA-MM-DD" name="gs_unico_fechahora[]" type="text" class="form-control"/> <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span></span>';
            $(containerfecha2).append($input_fecha);

            $(containerfecha).append(containerfecha2);
            //container3.appendTo(container2);
            
            scripr_datapicker = "<script type='text/javascript'>$(function () {$('#"+variable_datapicker_reference+"').datetimepicker({format: 'YYYY-MM-DD HH:MM:SS'});});</script>";
            ////
            
           var container_des = $(document.createElement('div'));
            container_des.addClass("control-label  col-md-1  col-sm-1 col-xs-12");
            $(container_des).append('<label  for="art_tipo"></label>');

            var container2des = $(document.createElement('div'));
            

            var containerdes = $(document.createElement('div'));
            containerdes.addClass("col-md-5 col-sm-3 col-xs-12");
            $(containerdes).append('<input type="text" class="form-control" id="gs_unico_des" placeholder="Observacion referida al gasto.." name="gs_unico_des[]">');

            //container3.appendTo(container2);
            $(container2des).append(containerdes);


            //$('#bloque_gastos_detalles').after(container,container2);
            $('#bloque_gastos_detalles').append(divclearfix);
            $('#bloque_gastos_detalles').append(container);
            $('#bloque_gastos_detalles').append(container2);
            $('#bloque_gastos_detalles').append(containerval);
            $('#bloque_gastos_detalles').append(container2val);
            $('#bloque_gastos_detalles').append(containerfecha);
            
              
            
            $('#bloque_gastos_detalles').append(container_des);
            $('#bloque_gastos_detalles').append(container2des);

            $('#bloque_gastos_detalles').append(scripr_datapicker);

            var container_linea = $(document.createElement('<br><br>'));
            $('#bloque_gastos_detalles').append(container_linea);
            fecha_datapicker_gasto = fecha_datapicker_gasto + 1; 

        });
        //Actualizar Precio Masivamente
        $('#actualiza_masivamente').click(function () {
                     
            //alert("aca");
            $valor = $('#filtrar').val();
            if ($valor.length == 0) {
                 
                alert("Debe ingresar un articulo en el buscador");
                $('#articulo_actualiza_precio_masivamente_label').text("Sin Articulo Seleccionado");
                $('#actualiza_precio_masivamente').attr("disabled", true);

            }
            else{
                console.log($valor);
                $('#articulo_actualiza_precio_masivamente_label').text($valor);
               
                $('#articulo_actualiza_precio_masivamente').val($valor);
                $('#actualiza_precio_masivamente').attr("disabled", false);
            }
            /*$('.buscar tr').hide();
            $('.buscar tr').filter(function () {
            return rex.test($(this).text());
            }).show();*/
 
        })


       
         /*$("#seleccionar_local_art input").on("click",function(e) {
               
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
       $("#check_art_locales_"),click(function(){
            
            alert("ok")
            
            // Convertimos el HTMLCollection a array
            //console.log([].slice.call(locales));
            
            for (var i = 0 ; i < locales.length; i++) {
                //$("#art_local_cantidad_" + i).on('keyup', function(){
                 
                alert(div_locales[i]);
                 
                
                //}).keyup();
            }
           
          
        });
      
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
                    
                    console.log(locales[i].value)
                     
                    if (locales_check_ok[x] == i ) {
                        
                        var cantidad_parcial_total = cantidad_parcial_total + parseInt(locales[i].value);

                    }
                }
                
                 
                
                //}).keyup();
            }
            alert(cantidad_parcial_total); 
            $("#art_cantidad_total").text("Cantidad Total:" + cantidad_parcial_total);
            
            /*if (cantidad_total != cantidad_parcial_total ) {
                    alert("La sumatoria parcial de la distribucion por local no puede ser diferente a la cantidad total del deposito.");
                    for (var i = 0 ; i < locales.length; i++) {
                        //$("#art_local_cantidad_" + i).on('keyup', function(){
                         
                        locales[i].value = null;
                        event.preventDefault();
                        
                        //}).keyup();
                    }
                }
            
        });

       $(".art_local_cantidad_").blur(function(){
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
             
            cantidad_parcial_total = 0;
            console.log(locales)
            for (var i = 0 ; i < locales.length; i++) {
                 
                //$("#art_local_cantidad_" + i).on('keyup', function(){
                for (var x = 0; x < locales_check_ok.length; x++) {
                    
                    //console.log(locales[i].value)
                    if (locales[i].value != '') {
                        valor_cantidad_xxx = parseInt(locales[i].value);
                        if (locales_check_ok[x] == i ) {
                            console.log(cantidad_parcial_total);
                            console.log(valor_cantidad_xxx);
                        
                            var cantidad_parcial_total = cantidad_parcial_total + valor_cantidad_xxx;

                        }
                    }
                    
                }
                
                 
                
                //}).keyup();
            }
            //alert(cantidad_parcial_total); 
            $("#art_cantidad_total").text("Cantidad Total:" + cantidad_parcial_total);
            
        });
      */
     
      
        
        //GENERA PRECIO POR PORCENTAJES GANANCIAS
        $("#art_ganancia").blur(function(){
            
            var porciento =  $(this).val();
            var preciobase = $("#art_precio_base").val();
            if (preciobase != '') {

                var precio_tarjeta_aux = (porciento * preciobase) / 100;
                var final_ganancia = (parseFloat (precio_tarjeta_aux) + parseFloat(preciobase));
                $("#valor_calculado_ganancia").text("Pesos Argentinos: " + final_ganancia.toFixed(2));
                //$(this).val(porciento + '%')
                
                $(this).val(porciento)
            }
            
          
            
        });
        
       //GENERA PRECIO POR PORCENTAJES TARJETA
        $("#art_precio_tarjeta").blur(function(){
           
            var porciento =  $(this).val();
            var preciobase = $("#art_precio_base").val();
            if (preciobase != '') {
                var precio_tarjeta_aux = (porciento * preciobase) / 100;
            
                $("#valor_calculado_tarjeta").text("Pesos Argentinos: " + (parseInt(precio_tarjeta_aux) + parseInt(preciobase)));
                //$(this).val(porciento + '%')
                $(this).val(porciento)
            }
            
          
            
        });

        $("#art_precio_tarjeta_masivo").blur(function(){
           
            var porciento =  $(this).val();
            var preciobase = $("#art_precio_base_masivo").val();
            if (preciobase != '') {
                var precio_tarjeta_aux = (porciento * preciobase) / 100;
            
                $("#valor_calculado_tarjeta").text("Pesos Argentinos: " + (parseInt(precio_tarjeta_aux) + parseInt(preciobase)));
                //$(this).val(porciento + '%')
                $(this).val(porciento)
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
                //$(this).val(porciento + '%')
                $(this).val(porciento)
            }
            
        });

        $("#art_precio_credito_argentino_masivo").blur(function(){
           
            var porciento =  $(this).val();
            var preciobase = $("#art_precio_base_masivo").val();
            if (preciobase != '') {
                var precio_tarjeta_aux = (porciento * preciobase) / 100;
            
                $("#valor_calculado_credito_personal").text("Pesos Argentinos: " + (parseInt(precio_tarjeta_aux) + parseInt(preciobase)));
                //$(this).val(porciento + '%')
                $(this).val(porciento)
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

        //Agregar Descripcion a Tipo de Gasto
         $("#cargar_art_tipo").click(function() {
                var des_gasto = $("#gs_des").val();
               
                //$(".aca_va_des_gasto").val(des_gasto);
                $(".aca_va_des_gasto").text(des_gasto);
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
        $("#cargar_marca_btn").click(function() {
            /*alert('aca');
            var selected = $(':selected', this);

            var optgroup = selected.parent().attr('label');
            var acas = selected.text();
             
            //var option = $(this).val();
            //alert(optgroup);
            //alert(selected.closest('optgroup').attr('label'));
             
            //var input = ','.concat(option);
            var option = $('#forma_pago_select').find(":selected").text();*/
           

            $("#medio_art_venta").val(Medio_Pago);

            $("#cuotas_art_venta").val(Valor_Medio_Pago);

            $("#precio_final_art_venta").val(Valor_Total_Pago);
             
 
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


 function calcular_total(){
             
            //Realiza Venta. Calculo de TOTAL A PAGAR
            //Valor Forma de PAGO
           
            $select_valor = document.getElementById("valor_pago_select");
            //Obtener select seleccionado
            $id_forma_pago = document.getElementById("forma_pago_select").value;
            
            var select_forma = String($("#forma_pago_select option:selected").html());
            
            ///
            $valor_valor = $select_valor.value;
            $valor_forma = String(select_forma);
             
            $valor_valor_sin_x = $valor_valor.replace(/x/,"");
            $valor_valor_sin_peso = $valor_valor_sin_x.replace(/\$/,"");

          
            if($valor_valor.indexOf('x') != -1){
                  
                $cantidad_cuotas = $valor_valor_sin_peso.substr(0,2);
                if ($cantidad_cuotas == 0) {
                    $cantidad_cuotas = 1;
                }
                $valor_cuotas = $valor_valor_sin_peso.substr(2);
                $valor_precio_ = Number($cantidad_cuotas) * Number($valor_cuotas);
            }else{
               
                $cantidad_cuotas = 1;
                $valor_cuotas = 1;
                $valor_precio_ = Number($valor_valor_sin_peso);
            }
            
            $forma_pago_porciento = $valor_forma.substr(-5);
            $forma_pago_sin_porciento = $forma_pago_porciento.replace(/%/,"");
            $forma_pago_sin_menos = $forma_pago_sin_porciento.replace(/-/,"");
            $porcentaje_final = $forma_pago_sin_menos.replace(/\(|\)/g,"");
            
           
            if(select_forma.indexOf('%') != -1){
                $valor_parcial_porciento = (Number($porcentaje_final) * Number($valor_precio_))/100;
                
                $valor_finali_finali = Number($valor_precio_) - Number($valor_parcial_porciento);

            }else{
            //    $valor_parcial_porciento = (1* Number($valor_precio_))/100;
               
                $valor_finali_finali = Number($valor_precio_);
            }
            
            
        
            $("#total_pagar").text("Pesos Argentinos:$" + $valor_finali_finali.toFixed(1));
        
            
            

            Valor_Medio_Pago = $valor_valor;
            Medio_Pago = $id_forma_pago;
            Valor_Total_Pago = $valor_finali_finali.toFixed(1);

            //$("#medio_art_venta").val(String(Medio_Pago));

            //$("#cuotas_art_venta").val(String(Valor_Medio_Pago));

            //$("#precio_final_art_venta").val(String(Valor_Total_Pago));
            
           
             
        };
function enviar_datos_venta(){


    $("#medio_art_venta").val(Medio_Pago);

    $("#cuotas_art_venta").val(Valor_Medio_Pago);

    $("#precio_final_art_venta").val(Valor_Total_Pago);
}


 

 
