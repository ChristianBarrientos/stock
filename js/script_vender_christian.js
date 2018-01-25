var unasola = true;
var articulos =  new Array();
var total_ventas = 0;
var numero = 0;
var Ventas = new Array();
var Medios_Pagos = new Array();
var total_final = 0;
var final3 = '';
var venta_ ='';

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


$(document).ready(function()
    {   
       
      //$('#medio_pago_valor_total').attr('disabled','false');
      $( "#medio_pago_valor_total" ).prop( "disabled", true );
      $("#forma_pago_select_2_bloque").hide();
      
      
      	/*$("#tags").autocomplete({
			source: availableTags,
		});*/ 

    $("#CajaBusqueda").keypress(function(e) {
          var code = (e.keyCode ? e.keyCode : e.which);
          if(code==13){
            let art;
            art = art_obtener();
            //articulos = articulos.unique();
            
            out = art.split(',');
                  //console.log(out);
            articulo_nombre = out[0]+','+out[1]+','+out[2];
            var params = {
              lote: out[3]
            }; 
            agregar_fila(params,out,articulo_nombre);
            calculo_total();

          }
        });

    	$("#CajaBusqueda").keyup(function(){
    		art_obtener();
        calculo_total();
            
  		});

    $('#btn_vender').click(function(){ 

      if (typeof venta_.local !== 'undefined') {
        alert("Venderas");
        //console.log(venta_);
        let Datos = new FormData();
        venta_aux = JSON.stringify(venta_);
        //data: {'array':JSON.stringify(array)},//capturo array     
        Datos.append("Venta_",venta_aux);

        $.ajax({
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
        });

      }else{
        //console.log(venta_);
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
    Ventas[id].cantidad = cantidad_n;
  }
  calculo_total();

}

function borrar_fila(input){

  var oID = $(input).attr("id");
   
  id_tr = oID.replace(/^[a-zA-Z\s]*/, "");
  let cantidad = Ventas[id_tr].cantidad;
  let precio = Ventas[id_tr].precio_final;
  let auxiliar = cantidad * precio;

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
              id_tr_ = id_tr_for - 1;
            }else{
              id_tr_ = id_tr_for;
            }
            id_input_ = "cantidad"+id_tr_;
            id_input_2 = "borrar"+id_tr_;
            valor = Ventas[id_tr_].cantidad;
            id_tr_for_2 = id_tr_;
            this.innerHTML = id_tr_ + 1;
          }
          if (counter == 2) {
            input = "<input id='"+id_input_+"' type='number' size='5' value="+valor+" min='1' onchange='actualiza_cantidad(this)'>";
            this.innerHTML = input;
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
   
  let Busqueda = $("#CajaBusqueda").val();

  let Datos = new FormData();

  if (Busqueda.length >= 2 && (Busqueda != '' || Busqueda != null)) {
    //alert(Busqueda);
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
    
    var valores = JSON.parse(Respuesta);
    //var valores = Respuesta;
    //console.log(valores["1"]);
    
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

      busqueda_auto();
                    
    }else{
     
      $("#Sinresultados").html('Sin Coincidencias');
    }

                    //console.log(articulos);
                       
            }
          });
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
      $("#Sinresultados").html(' ');
      agregar_fila(params,out,articulo_nombre);
      
    }
  });
}

function agregar_fila(params,out,articulo_nombre){
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
            ///CajaBusqueda
            
            
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
  if (!($("#forma_pago_select_2_bloque").hasClass("hide"))) {
    
    input_value_1 = $("#medio_pago_valor_total").val();
    medio_pago1 = new Medio_Pago(id_forma_pago,input_value_1);
    Medios_Pagos.push(medio_pago1);
    $("#medio_pago_valor_total").val(valor_finali_finali.toFixed(1));

    $("#medio_pago_valor_total").on('input', function(){
      var cant = parseInt(this.value, valor_finali_finali.toFixed(1));
      $(this).attr('max', valor_finali_finali.toFixed(1));
    });

  }else{
    input_value_1 = $("#medio_pago_valor_total").val();
    input_value_2 = $("#medio_pago_valor_total_2").val();
    medio_pago1 = new Medio_Pago(id_forma_pago,input_value_1);
    medio_pago2 = new Medio_Pago(id_forma_pago,input_value_2);
    Medios_Pagos.push(medio_pago1);
    Medios_Pagos.push(medio_pago2);
  }


  total_final = valor_finali_finali.toFixed(1);
  //venta_ = new Venta_final(Ventas,Medios_Pagos,total_final);
  

}
//calcular diferencia medios de pagos
function calcular_diferencia_mp(el){

  $("#medio_pago_valor_total").on('input', function(){
    var cant = parseInt(this.value, valor_finali_finali.toFixed(1));
    $(this).attr('max', valor_finali_finali.toFixed(1));
  });

}

function agregar_elimina_medio_pago(e){
  var oID = $(e).attr("id");
  var valor = $(e).val();
  if (valor == 1) {
    $(e).val(2);
    $(e).text("Eliminar Medio de Pago");
    $("#forma_pago_select_2_bloque").show();
  }else{
    $(e).val(1);
    $(e).text("Agregar Medio de Pago");
    $("#forma_pago_select_2_bloque").hide();
  }
}


function local_seleciona(){
  let id_local = document.getElementById("local_select").value;
  let select_forma = String($("#local_select option:selected").html());
  let nombre_local = String(select_forma);
  local = new Local(id_local,nombre_local);
  venta_ = new Venta_final(Ventas,Medios_Pagos,total_final,local);
}
