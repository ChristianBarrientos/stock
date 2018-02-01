var unasola = true;
var articulos =  new Array();
var total_ventas = 0;
var numero = 0;
var Ventas = new Array();
var Medios_Pagos = new Array();
var total_final = 0;
var local_ = '';
var cantidad_cuotas = '';
var final3 = '';
var venta_ ='';
var id_aux = 0;
var una_vez = true;
function Venta(id_lote, cantidad,precio_final) {
  this.id_lote = id_lote;
  this.cantidad = cantidad;
  this.precio_final = precio_final;
}

function Medio_Pago(id_medio_pago, subtotal) {
  this.id_medio_pago = id_medio_pago;
  this.subtotal = subtotal;
}

function Local(id_local, nombre) {
  this.id_local = id_local;
  this.nombre = nombre;
}

function Venta_final(ventas, medio_pago,total,local = null) {
  this.ventas = ventas;
  this.medio_pago = medio_pago;
  this.total = total;
  this.local = local;
}
$( "#cantidad_cuotas" ).prop( "disabled", true );

$(document).ready(function()
    {   
      $('#div_carga')
          .hide()
          .ajaxStart(function() {
            $(this).show();
          })
        .ajaxStop(function() {
            $(this).hide();
          });
       
      //$('#medio_pago_valor_total').attr('disabled','false');
      $( "#medio_pago_valor_total" ).prop( "disabled", true );
      
      $("#forma_pago_select_2_bloque").hide();
      
      
      	/*$("#tags").autocomplete({
			source: availableTags,
		});*/ 

    $("#CajaBusqueda").keypress(function(e) {
          //busqueda_auto();
          var code = (e.keyCode ? e.keyCode : e.which);
          
          if(code==13){
            //var delayInMilliseconds = 100; //1 second
            let art;
            console.log("EnterKey2");
            art_obtener();
            //setTimeout(function() {
              
              //articulos = articulos.unique();
              
              /*out = art.split(',');
                    //console.log(out);
              articulo_nombre = out[0]+','+out[1]+','+out[2];
              var params = {
                lote: out[3]
              }; 
              console.log(params);
              console.log(params.lote);
              if (params.lote == null || params == null) {
                console.log("EntroIF");
              }else{
                console.log("EntroELSE");
                console.log(params);
                console.log(params.lote);
                agregar_fila(params,out,articulo_nombre);
                calculo_total();
              }*/
            //}, delayInMilliseconds);
            
            

          }else{
            if(code==32){

              
              art_obtener();
              calculo_total();
            }else{
              console.log("Else");
              //alert("Pulsaste la tecla con código: "+e.which);
            }
          }

        });

    	/*$("#CajaBusqueda").keyup(function(){
        console.log("Aca");
    		art_obtener();
        calculo_total();
            
  		});*/

    $('#btn_vender').click(function(){ 
      //typeof venta_.local !== 'undefined' ||
      
      repaso_general();
      if (( typeof venta_.ventas !== 'undefined' || typeof venta_.medio_pago !== 'undefined' || typeof venta_.total !== 'undefined') && Ventas.length >= 1) {
        //alert("Venderas");
        //console.log(venta_);
        
        console.log(venta_);
        venta_ = new Venta_final(Ventas,Medios_Pagos,total_final,local_);
        
        let Datos = new FormData();
        venta_aux = JSON.stringify(venta_); 
        
        Datos.append("Venta_",venta_aux);
        var params = {
          id_local: venta_.local.id_local,
          total: venta_.total,
          medios_pago: venta_.medio_pago,
          articulos:venta_.ventas,
          cuotas: cantidad_cuotas
        };
        
        $.get("controladores/vende_.php", params, function (response) {
          //console.log(response);
          //console.log(response['status']);
          //console.log("Despues");
          //var valores = JSON.parse(JSON.stringify(response));
          
          var valores = JSON.parse(response);
           

          //console.log(valores);

          if (valores.status == 'ok') {
            console.log("Vendido");
            clear_full();
            console.log(valores);
            alert("EXITO! Venta realizada con exito. " + valores.result['rg_detalle']);


          }else{
            console.log(valores);

            console.log("Error")
            alert("ERROR! No se pudo generar la venta con exito. Revise que los datos ingresados esten completos.");
          }
          //var json = JSON.parse(response);
          /*if (json.status == 'ok'){
            console.log("OK");
            console.log(json);
          }else{
            console.log("ERROR");
            console.log(json);
          }*/

        });
        /*$.ajax({
          url: "controladores/vende_.php",
          method: "POST",
          data: Datos,
          cache: false,
          contentType: false,
          processData: false,    
          success: function(Respuesta){
            var valores = Respuesta;
            console.log(valores);
            }
        });*/

      }else{
        //console.log(venta_);
        console.log(venta_.ventas);
        console.log(venta_.medio_pago);
        console.log(venta_.total);
        console.log(Ventas.length);

        alert("Faltan Datos por Completar!");
      }
    });
	});	


//Eliminar Duplicados
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

//Eliminar Fila
$('#cerrar-modal').click(function(){ 

    alert("Borraras");

    });

$('.cerrar-modal').click(function(){ 

    alert("Borraras");

    });

function actualiza_cantidad(input){

  let cantidad_n = input.value;
  let auxiliar = 0;
  var oID = $(input).attr("id");
  id = oID.replace(/^[a-zA-Z\s]*/, "");
  let cantidad_v = Ventas[id].cantidad;
  let precio = Ventas[id].precio_final;
  if (cantidad_v != cantidad_n) {
    let diff = 0;
    if (cantidad_n > cantidad_v) {
      diff = cantidad_n - cantidad_v;
      auxiliar = precio * diff;
      total_ventas = parseFloat(total_ventas) + parseFloat(auxiliar.toFixed(2));
    }else{  
      diff = cantidad_v - cantidad_n;
      auxiliar = precio * diff;
      total_ventas = parseFloat(total_ventas) - parseFloat(auxiliar.toFixed(2));
    }
    $("#total_venta").text(total_ventas.toFixed(2));
    Ventas[id].cantidad = cantidad_n;//
  }
  calculo_total();

}

function borrar_fila(input){

  var oID = $(input).attr("id");
  //console.log($(input).val());
  //$(input).val(1);
  //console.log($(input).val());

  id_tr = oID.replace(/^[a-zA-Z\s]*/, "");
  let cantidad = Ventas[id_tr].cantidad;
  let precio = Ventas[id_tr].precio_final;
  let auxiliar = cantidad * precio;

  //Ventas[id_tr].cantidad = 1;

  total_ventas = parseFloat(total_ventas) - parseFloat(auxiliar.toFixed(2));
  $("#total_venta").text(total_ventas.toFixed(2));
  
  let counter = 0;
  $('#tabla_pv tr').each(function() {
     
    id_tr_for = parseInt(this.id);
    
    if (this.id != null && this.id != '' && this.id != 'venta_total') {
        $("#"+this.id+" td").each(function(){
          if (counter == 0) {
            id_id = parseInt(this.innerHTML);
            if (id_tr < id_tr_for && id_tr_for != 0)  {
              //console.log("If");
              id_tr_ = id_tr_for - 1;
            }else{
              //console.log("Else");
              id_tr_ = id_tr_for;
            }
            id_input_ = "cantidad"+id_tr_;
            id_input_2 = "borrar"+id_tr_;
            valor = Ventas[id_tr_].cantidad;
            id_tr_for_2 = id_tr_;
            this.innerHTML = id_tr_ + 1;
          }
          if (counter == 2) {
            input_out = "<input id='"+id_input_+"' type='number' size='5' value="+valor+" min='1' onchange='actualiza_cantidad(this)'>";
            this.innerHTML = input_out;
          }
          if (counter == 4) {
            button = "<button id="+id_input_2+" type='button' onclick='borrar_fila(this)' class='btn btn-danger'>X</button>";
            this.innerHTML = button;
          }
          counter = counter + 1;
      });
        numero = numero - 1;
        this.id = parseInt(id_tr_for_2);
    }
    counter = 0;
  });
  Ventas.splice(id_tr, 1);
  $("#"+id_tr+"").remove();
  if (Ventas.length == 0) {
    numero = 0;
  }
  calculo_total();
}

function art_obtener(){
  let cb = false;
  //console.log("art_obtener");
  let Busqueda = $("#CajaBusqueda").val();
  if (!isNaN(Busqueda) || (Busqueda.indexOf("MOTOMATCH") > -1)) {
    console.log("Solo Numeros");
    cb = true;
  }else{
    console.log("ELSE");
    
  }
  //console.log(Busqueda);
  //console.log("Finart_obtener");
  let Datos = new FormData();

  if (Busqueda.length >= 2 && (Busqueda != '' || Busqueda != null)) {
    //alert(Busqueda);
    //console.log("DentroDelIF");
    var dataList = document.querySelector('#json-art'),
    input = document.querySelector('#art');
                
    Datos.append("BusquedaArt",Busqueda);
    $.ajax({
      url: "template/venta_/ajax_venta.php",
      method: "POST",
      data: Datos,
      cache: false,
      contentType: false,
      processData: false,
    success: function(Respuesta){ 
    //var valores = JSON.parse(JSON.stringify(Respuesta));
    //var valores = JSON.stringify(Respuesta);
    //console.log(Respuesta);
    var valores = JSON.parse(Respuesta);
    //var valores = Respuesta;
    //console.log(valores);
    //console.log(valores['status']);
    
    if (valores.status == 'ok') {
       
      $("#Sinresultados").html(' ');
      //console.log(Object.keys(valores.result));
      //console.log(valores.result);
      //console.log(valores.result.unique()); 
      //valores.result = valores.result.unique();
      
      for (var i = 0; i <= valores.result.length; i++) {

        if (typeof valores.result[i] !== 'undefined') {
          art = valores.result[i].Articulo;
          marca = ','.concat(valores.result[i].Marca);
          tipo = ','.concat(valores.result[i].Tipo);
          art_marca = art.concat(marca);
          id_lote = valores.result[i].id_lote;
          precio_final = valores.result[i].precio_base;
          attr = valores.result[i].attr;
                                     //moneda = valores.result[i].moneda;
          let nombre_art = art_marca.concat(tipo);
          let final = nombre_art.concat(',');
          let final2 = final.concat(id_lote);
          let final22 = final2.concat(',');
          final3 = final22.concat(attr);
                                     //let final33 = final3.concat(',');
                                     //let final4 = final33.concat(moneda);
          articulos.push(final3);
          articulos = articulos.unique();
                         //console.log(valores.result[i].id_lote);
                         //console.log(valores.result[i].importe);
                         //console.log(valores.result[i].precio_base);

        } 
      }
      if (cb) {
        //console.log("CB");
        //console.log(articulos[0]);
        out = articulos[0].split(',');
        articulo_nombre = articulos[0];
        var params = {
          lote: out[3]
        }; 
        //console.log(params);
        //console.log(params.lote);
        $("#Sinresultados").html(' ');
        //console.log("LlamaFuncionAgregarFila");
        agregar_fila(params,out,articulo_nombre);
        articulos = [];
      }else{
        busqueda_auto();
      }
      
             
    }else{
      console.log("SinCoincidencias");
      $("#Sinresultados").html('Sin Coincidencias');
    }          
            }
          });
    }else{
      console.log("EntroAlElse");
    }
        
      return final3;
      }

function busqueda_auto(){
  //console.log(articulos);
  //console.log("BusquedaAuto");

  $("#CajaBusqueda").autocomplete({
    source: articulos,
    select: function (event, item) {
      //console.log(item.item);
      out = item.item.value.split(',');
      //console.log(out);
      articulo_nombre = item.item.value;
      var params = {
        lote: out[3]
      }; 
      //console.log(params);
      //console.log(params.lote);
      $("#Sinresultados").html(' ');
      //console.log("LlamaFuncionAgregarFila");
      agregar_fila(params,out,articulo_nombre);
      
    }
  });
}

function agregar_fila(params,out,articulo_nombre){
  //console.log("AgregarFila");
  $.get("template/venta_/ajax_venta2.php", params, function (response) {  
        let bandera = true;
        if (Ventas.length != 0) {
          for (var i = 0; i < Ventas.length; i++) {
            if (Ventas[i].id_lote == params.lote) {
                id_tr_aux = "cantidad"+i;
                cantidad_vv = $("#"+id_tr_aux).val();
                cantidad_nn = parseInt(cantidad_vv) + 1;
                $("#"+id_tr_aux).val(cantidad_nn);
                Ventas[i].cantidad = cantidad_nn;
                precio_final = parseFloat(Ventas[i].precio_final);
                bandera = false;
                break;
            }else{
              bandera = true; 
            }
          }
        }
        if(bandera){
          //console.log(response);
          var json = JSON.parse(response);
          if (json.status == 'ok'){
            let costo = json.result.precio_base;
            let importe = json.result.importe;
            let moneda = json.result.moneda;
            var precio_aux = importe * costo;
            var precio_final = precio_aux * moneda;
            id_input = "cantidad"+numero;
            id_input2 = "borrar"+numero;
            input = "<input id='"+id_input+"' type='number' size='5' value='1' min='1' onchange='actualiza_cantidad(this)'>";
            button = "<button id="+id_input2+" type='button' onclick='borrar_fila(this)' class='btn btn-danger'>X</button>";
            numero_col = numero + 1;
                        //var fila="<tr id="+numero+"><td>"+numero_col+"</td><td>"+item.item.value+"</td><td WIDTH='10'>"+input+"</td><td>"+precio_final.toFixed(2)+"</td><td><input id="+id_input2+" class='cerrar-modal' name='modal' type='radio' onclick='borrar_fila(this)'/> <label for='cerrar-modal'> X </label> </td></tr>";
                        //var fila="<tr id="+numero+"><td>"+numero_col+"</td><td>"+item.item.value+"</td><td WIDTH='10'>"+input+"</td><td>"+precio_final.toFixed(2)+"</td></tr>";
            var fila="<tr id="+numero+"><td>"+numero_col+"</td><td>"+articulo_nombre+"</td><td WIDTH='10'>"+input+"</td><td>"+precio_final.toFixed(2)+"</td><td>"+button+"</td></tr>";
            $('#venta_total').before(fila);
            numero = numero + 1;
          }else{
            console.log("Sin Resultados");
          }
          venta = new Venta(out[3],$("#"+id_input).val(),precio_final.toFixed(2));         
          Ventas.push(venta);
        //console.log(Ventas);
        }   
        
        total_ventas = parseFloat(total_ventas) + parseFloat(precio_final.toFixed(2));
        $("#total_venta").text(total_ventas.toFixed(2));
        $("#CajaBusqueda").val("");
      }); 
}

function calculo_total(){
  //id_aux_2 = 1;
  let id_forma_pago = document.getElementById("forma_pago_select").value;
  //$( "#myselect" ).val();
  //let select_forma = String($("#forma_pago_select option:selected").html());
  let select_forma = String($("#forma_pago_select option:selected").html());
  let valor_forma = String(select_forma);

  let forma_pago_porciento = valor_forma.substr(-5);
  let separador  = "";
  let array_auxiliar = forma_pago_porciento.split(separador);
  let signo_medio_pago = array_auxiliar[0];

  let forma_pago_sin_porciento = forma_pago_porciento.replace(/%/,"");
  let forma_pago_sin_menos = forma_pago_sin_porciento.replace(/-/,"");
  let porcentaje_medio_pago = forma_pago_sin_menos.replace(/\(|\)/g,"");

  if(select_forma.indexOf('%') != -1){
    valor_parcial_porciento = (Number(porcentaje_medio_pago) * Number(total_ventas))/100;      
    if (signo_medio_pago == '-') {
      valor_finali_finali = Number(total_ventas) - Number(valor_parcial_porciento);
    }
    else{
      valor_finali_finali = Number(total_ventas) + Number(valor_parcial_porciento);
    }
  }else{  
    valor_finali_finali = Number(total_ventas);
  }

  $("#total_venta_final").text(valor_finali_finali.toFixed(1));

  //$('#medio_pago_valor_total').attr('disabled',' ');
  $("#medio_pago_valor_total" ).prop( "disabled", false );

  let valor = $("#agrega_medio_pago").val();

  Medios_Pagos = [];
  //let bandera = $("#forma_pago_select_2").val();

  let bandera = document.getElementById("forma_pago_select_2").value;
  //console.log(bandera);
  //if (!($("#forma_pago_select_2").hasClass("hide"))) {
  let saltillo = true;
  if (id_aux == 0 || id_aux == 1) {
    saltillo = true;
  }else{
    saltillo = false;
  }
  //if ((bandera == 0 || bandera == 'null') && saltillo) {
  if (saltillo) {
    input_value_1 = $("#medio_pago_valor_total").val();
    medio_pago1 = new Medio_Pago(id_forma_pago,input_value_1);
    Medios_Pagos.push(medio_pago1);
    $("#medio_pago_valor_total").val(valor_finali_finali.toFixed(1));

    $("#medio_pago_valor_total").on('input', function(){
      var cant = parseInt(this.value, valor_finali_finali.toFixed(1));
      $(this).attr('max', valor_finali_finali.toFixed(1));
    });
    //id_aux_2 = 0;

  }else{
    //console.log("2DosMP");
    id_forma_pago = document.getElementById("forma_pago_select").value;
    let id_forma_pago2 = document.getElementById("forma_pago_select_2").value;
    input_value_1 = $("#medio_pago_valor_total").val();
    input_value_2 = $("#medio_pago_valor_total_2").val();
    medio_pago1 = new Medio_Pago(id_forma_pago,input_value_1);
    medio_pago2 = new Medio_Pago(id_forma_pago2,input_value_2);
    Medios_Pagos.push(medio_pago1);
    Medios_Pagos.push(medio_pago2);
  }

  total_final = valor_finali_finali.toFixed(1);
  //venta_ = new Venta_final(Ventas,Medios_Pagos,total_final);
  //id_aux_2 = 0;
  calcular_cuotas();
  //id_aux_2 = 1;
  calcular_diferencia_mp();
}

function borrar_options(select){
  $(select).html("");
}

function calcular_cuotas() {
  let id_forma_pago = document.getElementById("forma_pago_select").value;
  // && cantidad_cuotas == 0
  if (id_forma_pago != 'null') {
    let cuota = 0;
    let texto_opt = '';
    var select = document.getElementById("cantidad_cuotas");
    borrar_options(select);
    for (var i = 1; i<= 12; i++) {
        cuota = total_final/i;
        //selected="selected"
        cuota = parseFloat(cuota);
        texto_opt = i+" x "+cuota.toFixed(1);
        //$('#cantidad_cuotas').append('<option value="'+i+'" >'+texto_opt+'</option>')
        var x = document.getElementById("cantidad_cuotas");
        var option = document.createElement("option");
        option.text = texto_opt;
        option.value = i;
        x.add(option);
    }
    
    //if (typeof local_.id_local !== 'undefined') {
    

    
    if (una_vez) {
      let select_forma = String($("#cantidad_cuotas option:selected").html());
      cantidad_cuotas = String(select_forma);
      una_vez = false;
    }
    $("#cantidad_cuotas").selectpicker("refresh");
    
    //$("#cantidad_cuotas").prop( "disabled", false );
    //let id_local = document.getElementById("cantidad_cuotas").value;
    
  }
}

//calcular diferencia medios de pagos
function calcular_diferencia_mp(el){

  $("#medio_pago_valor_total").on('input', function(){
    var cant = parseInt(this.value, parseFloat(total_final).toFixed(1));
    $(this).attr('max', parseFloat(total_final).toFixed(1));
  });
  calculo_total_2m();

}

function calculo_total_2m(){
  let input_1 = document.getElementById("medio_pago_valor_total").value;
  let input_2 = parseFloat(total_final) - parseFloat(input_1);
  $("#medio_pago_valor_total_2").val(input_2.toFixed(1));
  //calculo_total();
  
}

function agregar_elimina_medio_pago(e){
  var oID = $(e).attr("id");
  var valor = $(e).val();
  if (valor == 1) {
    $(e).val(2);
    $(e).text("Eliminar Medio de Pago");
    //$("#forma_pago_select_2").val('null');
    id_aux = 2;
    //$("#forma_pago_select_2 option[value="+ 0 +"]").attr("selected",true);
    $("#forma_pago_select_2_bloque").show();

  }else{
    $(e).val(1);
    $(e).text("Agregar Medio de Pago");
    id_aux = 1
    //$("#forma_pago_select_2").val('-1');
    //$("#forma_pago_select_2 option[value="+ 0 +"]").attr("selected",true);
    $("#forma_pago_select_2_bloque").hide();


  }
  calculo_total();
}

function local_seleciona(){
  console.log("local_seleciona");
  let id_local = document.getElementById("local_select").value;
  let select_forma = String($("#local_select option:selected").html());
  let nombre_local = String(select_forma);
  local_ = new Local(id_local,nombre_local);

  venta_ = new Venta_final(Ventas,Medios_Pagos,total_final,local_);
}

function cantidad_cuotas_act(){
  let select_forma = String($("#cantidad_cuotas option:selected").html());
  cantidad_cuotas = String(select_forma);
}

function una_vez(){
  una_vez = true;
}

function clear_full() {
  //document.getElementById("forma_pago_select").value = '1';
  var tabla = document.getElementById("tabla_pv");
  borrar_options(tabla);
  let tabla_nueva = '<table id="tabla_pv" cellpadding="15" class="table table-bordered " align="center"><thead><tr><th>N°</th><th>Articulo</th><th>Cantidad</th><th>Precio/Unidad</th></tr></thead><tbody><tr id="venta_total"><td  colspan='+"3"+' ><strong>SubTotal:</strong> </td><td  colspan='+"1"+' ><strong> $ <label id="total_venta"><label></strong></td></tr><tr id="venta_total"><td  colspan='+"3"+' ><strong>Total:</strong> </td><td  colspan='+"1"+' ><strong> $ <label id="total_venta_final"><label></strong></td></tr></tbody></table>';
  var div_contenedor_tabla = document.getElementById("Resultados");

  $(div_contenedor_tabla).html(tabla_nueva);
  
  //Venta_final(Ventas,Medios_Pagos,total_final,local_)
  //Venta_final(ventas, medio_pago,total,local = null)

  venta_ .ventas='';
  //venta_ .medio_pago='';
  venta_ .total='';
  articulos = [];
  total_ventas = 0;
  numero = 0;
  Ventas = [];
  Medios_Pagos = [];
  total_final = 0;
  //local_ = '';
  cantidad_cuotas = '';
  final3 = '';
  id_aux = 0;
  una_vez = true;

}

function repaso_general(){
  //una_vez();
  console.log("Local Seleciona");
  local_seleciona();
  console.log("LCantidad Cuotas");
  cantidad_cuotas_act();
  console.log("Calculo Total");
  calculo_total();
  console.log("Fin Repaso");

}

