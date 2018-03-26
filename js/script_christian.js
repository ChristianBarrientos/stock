
//Variables Globales
var Valor_Medio_Pago;
var Medio_Pago;
var Valor_Total_Pago;
var counter = 1;
var fecha_datapicker_gasto = 1;

$(document).ready(function()
    {   
    
        $('#filtrar').keyup(function () {
                     
                    var rex = new RegExp($(this).val(), 'i');
                    $('.buscar tr').hide();
                    $('.buscar tr').filter(function () {
                        return rex.test($(this).text());
                    }).show();
 
                })
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
        
        //Añadir detalle de grupo atributos art
        $('#uno_mas_ctdetalle').click(function () {
       

            var divclearfix = $(document.createElement('div'));
            divclearfix.addClass("clearfix");

            var container = $(document.createElement('div'));
            container.addClass("control-label  col-md-1  col-sm-1 col-xs-12");
            $(container).append('<label  for="art_tipo" value="Nombre:">Nombre:</label>');

            var container2 = $(document.createElement('div'));
            

            var container3 = $(document.createElement('div'));
            container3.addClass("col-md-3 col-sm-3 col-xs-12");
            $(container3).append('<input type="text" class="form-control" id="gct_nombre" placeholder="Subtitulo del Gasto." name="gct_nombre[]">');

            //container3.appendTo(container2);
            $(container2).append(container3);


            var containerval = $(document.createElement('div'));
            containerval.addClass("control-label  col-md-1  col-sm-1 col-xs-12");
            $(containerval).append('<label  for="art_tipo">Descripcion:</label>');

            var container2val = $(document.createElement('div'));
            

            var container3val = $(document.createElement('div'));
            container3val.addClass("col-md-3 col-sm-3 col-xs-12");
            $(container3val).append('<input type="text" class="form-control" id="gct_des" placeholder="Valor del Gasto." name="gct_des[]">');

            //container3.appendTo(container2);
            $(container2val).append(container3val);

            ////

           


            //$('#bloque_gastos_detalles').after(container,container2);
            $('#bloque_gastos_detalles').append(divclearfix);
            $('#bloque_gastos_detalles').append(container);
            $('#bloque_gastos_detalles').append(container2);
            $('#bloque_gastos_detalles').append(containerval);
            $('#bloque_gastos_detalles').append(container2val);
            

        });

        //Añadir detalle de Ventas Antiguas
        $('#uno_mas_ventaanrigua').click(function () {
            
       
            counter = counter + 1;
            
            var container = $(document.createElement('div'));
            $id_nuevo = 'id_nuevo' + counter;
            $(container).attr("id",$id_nuevo);
            $('#supremo').append(container);


            
            var packing = $('#div_formulario');
            var clone = packing.clone();
            clone.appendTo('#supremo');

            clone.attr('id', 'packing_' + counter);
            clone.find('.bootstrap-select').remove();
            clone.find('select').selectpicker();

            /*$('#select_art_lote_local').clone().attr('id', 'newSel').attr('name', 'newSel').appendTo($('#d2'));

    // make the current value selected
            $("#newSel > option[value='" + $('#select_art_lote_local').val() + "']").attr('selected', 'selected');
   
            */

            //$('#div_formulario').clone().appendTo('#supremo');

            /*var divclearfix = $(document.createElement('div'));
            divclearfix.addClass("clearfix");

            var container = $(document.createElement('div'));
            container.addClass("control-label col-md-1 col-sm-1 col-xs-12");
            $(container).append('<label  for="gs_unico_nombre">Articulo:</label>');

            var container2 = $(document.createElement('div'));
            

            var container3 = $(document.createElement('div'));
            container3.addClass("col-md-3 col-sm-3 col-xs-12"); 
            $(container3).attr("id","id_nuevo");
            $('#select_art_lote_local').clone().appendTo('#id_nuevo');

            //$(container3).append('<input type="text" class="form-control" id="gs_unico_nombre" placeholder="Subtitulo del Gasto." name="gs_unico_nombre[]">');

            //container3.appendTo(container2);
            $(container2).append(container3);

            $('#supremo').append(divclearfix);
            $('#supremo').append(container);
            $('#supremo').append(container2);*/
        
            
              

/*
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
            fecha_datapicker_gasto = fecha_datapicker_gasto + 1; */

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
            
            var importe =  $(this).val();
            var costo = $("#art_precio_base").val();

            //var T1 = str.match(/\[(.*)\]/).pop();
            //alert(T1);
            //var moneda = document.getElementById("select_art_moneda"); 
            

            var moneda = String($("#select_art_moneda option:selected").html());
            $valor_valor_sin_letras = moneda.replace(/^[a-zA-Z\s]*/,"");
            $valor_moneda = $valor_valor_sin_letras.replace(/\(|\)/g,"");
             

            if (costo != '') {
                if ($valor_moneda != '') {
                    var precio_aux = importe * costo;
                    var precio_final = precio_aux * $valor_moneda;
                      

                }else{
                    var precio_aux = (importe * costo) / 100;
                    var precio_final = (parseFloat (precio_aux) + parseFloat(costo));
                }
                
                $("#valor_calculado_ganancia").text("Pesos Argentinos: " + precio_final.toFixed(2));
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

         //Agregar codigo a input al GENERA 
         $("#generar_artcodigo").click(function() {
            
            var codigo = $('#img_genera_codigo').attr('value');;

            //var asociado = $(img).val();
           $("#art_codigo_barras").val(codigo);
          

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

function mostrar_lista_art(){
     
    $('#mitabla').DataTable({
            "order": [[1, "asc"]],
            "language":{
                "lengthMenu": "Mostrar _MENU_ registros por pagina",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrada de _MAX_ registros)",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search": "Buscar:",
                "zeroRecords":    "No se encontraron registros coincidentes",
                "paginate": {
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },                  
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "sAjaxSource": "php_recurso/server_process.php"
        });
}


function mostrar_lista_art_cargar(){
     
    $('#mitabla').DataTable({
            "order": [[1, "asc"]],
            "language":{
                "lengthMenu": "Mostrar _MENU_ registros por pagina",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrada de _MAX_ registros)",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search": "Buscar:",
                "zeroRecords":    "No se encontraron registros coincidentes",
                "paginate": {
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },                  
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "sAjaxSource": "php_recurso/server_process_cargar.php"
        });
}

function mostrar_lista_art_traslado(){
    $('#mitabla').DataTable({
            "order": [[1, "asc"]],
            "language":{
                "lengthMenu": "Mostrar _MENU_ registros por pagina",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrada de _MAX_ registros)",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search": "Buscar:",
                "zeroRecords":    "No se encontraron registros coincidentes",
                "paginate": {
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },                  
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "sAjaxSource": "php_recurso/server_process_traslado.php"
        });
   
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
            $separador  = "";
            $array_auxiliar = $forma_pago_porciento.split($separador);
            $signo_medio_pago = $array_auxiliar[0];
            
            $forma_pago_sin_porciento = $forma_pago_porciento.replace(/%/,"");

            $forma_pago_sin_menos = $forma_pago_sin_porciento.replace(/-/,"");
            $porcentaje_final = $forma_pago_sin_menos.replace(/\(|\)/g,"");//Sin parentesis
            
           
            if(select_forma.indexOf('%') != -1){
                $valor_parcial_porciento = (Number($porcentaje_final) * Number($valor_precio_))/100;
                
                if ($signo_medio_pago == '-') {
                    $valor_finali_finali = Number($valor_precio_) - Number($valor_parcial_porciento);
                }
                else{
                    $valor_finali_finali = Number($valor_precio_) + Number($valor_parcial_porciento);
                }
                

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
    Valor_Medio_Pago_2 = Valor_Medio_Pago.replace(/$/,"");
    $("#cuotas_art_venta").val(Valor_Medio_Pago);

    $("#precio_final_art_venta").val(Valor_Total_Pago);
}

function calculo_ganancia(){
     
    let pf = parseFloat($("#art_precio_final").val());
    let pc = parseFloat($("#art_precio_base").val());
    var moneda = String($("#select_art_moneda option:selected").html());
    let pm_aux = moneda.replace(/^[a-zA-Z\s]*/,"");
    let pm = parseFloat(pm_aux.replace(/\(|\)/g,""));
    let im = 0;
    if (pf != null && pc != null && pm != null) {

        im = (pf/pc) / pm;
        console.log(im);
        
        $("#art_ganancia").val(im.toFixed(6));
    }else{
        console.log("No se puede realizar el calculo.");
    }

    $("#total_pagar").text("Pesos Argentinos:$" + pf.toFixed(2));
    $("#valor_calculado_ganancia").text("Pesos Argentinos: " + pf.toFixed(2));
}

function calculo_ganancia2(){
     
    let pf = parseFloat($("#prc_final").val());
    let pc = parseFloat($("#costo_art").val());
    var moneda = String($("#art_moneda option:selected").html());
    
    let pm_aux = moneda.replace(/^[a-zA-Z\s]*/,"");
     
    let pm = parseFloat(pm_aux.replace(/\(|\)|\$/g,""));

    let im = 0;
    if (pf != null && pc != null && pm != null) {
        
        im = (pf/pc) / pm;
        
        
        $("#importe_art").val(im.toFixed(6));
    }else{
        console.log("No se puede realizar el calculo.");
    }
    pf = Math.ceil(pf);
    $("#prc_final").val(pf)
    //$("#total_pagar").text("Pesos Argentinos:$" + pf.toFixed(2));
    //$("#valor_calculado_ganancia").text("Pesos Argentinos: " + pf.toFixed(2));
}

function calculo_precio_valores(){
     
    //let pf = parseFloat($("#prc_final").val());
    console.log("Cat Valores");
    let pc = parseFloat($("#costo_art").val());
    var moneda = String($("#art_moneda option:selected").html());
    let pm_aux = moneda.replace(/^[a-zA-Z\s]*/,"");
    let pm = parseFloat(pm_aux.replace(/\(|\)|\$/g,""));
    let imp = parseFloat($("#importe_art").val());

    pf = (parseFloat((pc * pm)) * parseFloat(imp));
    pf = Math.ceil(pf);
    console.log("pc:"+pc+"imp:"+imp+"pm:"+pm+"pf:"+pf);
    $("#prc_final").val(pf)
}

/*String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };

function elimina_espacios(){
     
    let cb = $("#art_codigo_barras").val();
    if (cb.trim().length == 0) {
         alert('Campo vacio');
    }
}

function desabilita_input(this){
    //var input = document.getElementById('input');
    this.disabled = true;
}*/
function sumatoria_ventas_antiguas(){
    console.log("sumatoria_ventas_antiguas");
    //let pf = parseFloat($("#art_precio_final").val());
    var nombres_paises = $(".art_total");
    let total_ = 0;
    

    for (var i = 0; i <= nombres_paises.length; i++) {
        console.log(nombres_paises.val());
        total_ = total_  + parseFloat(nombres_paises.val());
    }

    console.log(total_);

    //$("#venta_totales").text("" + total_.toFixed(2));
}

 

 
