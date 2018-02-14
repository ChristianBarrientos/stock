var unasola = true;
var articulos =  new Array();
var total_ventas = 0;
var numero = 0;
var Ventas = new Array();
var Medios_Pagos = new Array();
var total_final = 0;
var total_final_final = 0;
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
      foco_cajabusqueda();

      $('#div_carga')
          .hide()
          .ajaxStart(function() {
            $(this).show();
          })
        .ajaxStop(function() {
            $(this).hide();
          });
      $( "#medio_pago_valor_total" ).prop( "disabled", true );
      $("#forma_pago_select_2_bloque").hide();
      

    $("#CajaBusqueda").keypress(function(e) {
          $(".tap2").show();
          $(".tap1").show();
          var code = (e.keyCode ? e.keyCode : e.which);
          if(code==13){
            let art;
            console.log("EnterKey2");
            art_obtener();
          }
          foco_cajabusqueda();
        });

    $('#btn_vender').click(function(){ 
      $(".tap2").show();
      $(".tap1").show();
      repaso_general();
      if (( typeof venta_.ventas !== 'undefined' || typeof venta_.medio_pago !== 'undefined' || typeof venta_.total !== 'undefined') && Ventas.length >= 1) {
        
        venta_ = new Venta_final(Ventas,Medios_Pagos,total_final,local_);
        let Datos = new FormData();
        venta_aux = JSON.stringify(venta_); 
        Datos.append("Venta_",venta_aux);
        var params = {
          id_local: venta_.local.id_local,
          total: total_ventas.toFixed(2),
          medios_pago: venta_.medio_pago,
          articulos:venta_.ventas,
          cuotas: cantidad_cuotas
        };
        console.log(params);
        $.get("controladores/vende_.php", params, function (response) {
          $(".tap2").hide();
          $(".tap1").hide();
          var valores = JSON.parse(response);
          if (valores.status == 'ok') {
            console.log("Vendido");
            clear_full();
            console.log(valores);
            alert("EXITO! Venta realizada con exito. " + valores.result['rg_detalle']);
          }else{
            console.log(valores);
            alert("ERROR! No se pudo generar la venta con exito. Revise que los datos ingresados esten completos.");
          }
        });

      }else{
        console.log(venta_.ventas);
        console.log(venta_.medio_pago);
        console.log(venta_.total);
        console.log(Ventas.length);
        alert("Faltan Datos por Completar!");
      }
    });
    foco_cajabusqueda();
	});	

Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
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
  let Busqueda = $("#CajaBusqueda").val();
  Busqueda = Busqueda.replace(" ","");
  if (!isNaN(Busqueda) || (Busqueda.indexOf("MOTOMATCH") > -1) || (Busqueda.indexOf("CASSAROCHOPP") > -1) ) {
    console.log("Solo Numeros CB");
    cb = true;
  }
  let Datos = new FormData();

  if (Busqueda.length >= 2 && (Busqueda != '' || Busqueda != null)) {
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
      
      if (valores.status == 'ok') {
         
        $("#Sinresultados").html(' ');
        
        for (var i = 0; i <= valores.result.length; i++) {

          if (typeof valores.result[i] !== 'undefined') {
            art = valores.result[i].Articulo;
            marca = ','.concat(valores.result[i].Marca);
            tipo = ','.concat(valores.result[i].Tipo);
            art_marca = art.concat(marca);
            id_lote = valores.result[i].id_lote;
            precio_final = valores.result[i].precio_base;
            attr = valores.result[i].attr;
                                  
            let nombre_art = art_marca.concat(tipo);
            let final = nombre_art.concat(',');
            let final2 = final.concat(id_lote);
            let final22 = final2.concat(',');
            final3 = final22.concat(attr);
            articulos.push(final3);
            articulos = articulos.unique();

          } 
        }
        if (cb) {
          out = articulos[0].split(',');
          articulo_nombre = articulos[0];
          var params = {
            lote: out[3]
          }; 

          $("#Sinresultados").html(' ');
          agregar_fila(params,out,articulo_nombre);
          articulos = [];
        }else{
          busqueda_auto();
        }
        
               
      }else{
        console.log("SinCoincidencias");
        $(".tap2").hide();
        $(".tap1").hide();
        $("#Sinresultados").html('Sin Coincidencias. No se encuentra cargador el articulo: '+Busqueda);
      }
            }
          });
    }else{
      console.log("EntroAlElse");
    }
        
      return final3;
      }

function busqueda_auto(){

  $("#CajaBusqueda").autocomplete({
    source: articulos,
    select: function (event, item) {
      out = item.item.value.split(',');
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
        $(".tap2").hide();
        $(".tap1").hide();
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
            var fila="<tr id="+numero+"><td>"+numero_col+"</td><td>"+articulo_nombre+"</td><td WIDTH='10'>"+input+"</td><td>"+precio_final.toFixed(2)+"</td><td>"+button+"</td></tr>";
            $('#venta_total').before(fila);
            numero = numero + 1;
          }else{
            console.log("Sin Resultados");
          }
          venta = new Venta(out[3],$("#"+id_input).val(),precio_final.toFixed(2));         
          Ventas.push(venta);
        }   
        total_ventas = parseFloat(total_ventas) + parseFloat(precio_final.toFixed(2));
        $("#total_venta").text(total_ventas.toFixed(2));
        $("#CajaBusqueda").val("");
      }); 
}

function calculo_total(){
  let id_forma_pago = document.getElementById("forma_pago_select").value;
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
  valor_finali_finali_2 = Number(total_ventas);
  $("#total_venta_final").text(valor_finali_finali.toFixed(1));
  $("#medio_pago_valor_total" ).prop( "disabled", false );

  let valor = $("#agrega_medio_pago").val();

  Medios_Pagos = [];

  let bandera = document.getElementById("forma_pago_select_2").value;
  let saltillo = true;
  if (id_aux == 0 || id_aux == 1) {
    saltillo = true;
  }else{
    saltillo = false;
  }
  if (saltillo) {
    input_value_1 = $("#medio_pago_valor_total").val();
    medio_pago1 = new Medio_Pago(id_forma_pago,input_value_1);
    Medios_Pagos.push(medio_pago1);
    $("#medio_pago_valor_total").val(valor_finali_finali.toFixed(1));

    $("#medio_pago_valor_total").on('input', function(){
      var cant = parseInt(this.value, valor_finali_finali.toFixed(1));
      $(this).attr('max', valor_finali_finali.toFixed(1));
    });

  }else{
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
  total_final_final = valor_finali_finali_2.toFixed(1);
  calcular_cuotas();
  calcular_diferencia_mp();
}

function borrar_options(select){
  $(select).html("");
}

function calcular_cuotas() {
  let id_forma_pago = document.getElementById("forma_pago_select").value;
  if (id_forma_pago != 'null') {
    let cuota = 0;
    let texto_opt = '';
    var select = document.getElementById("cantidad_cuotas");
    borrar_options(select);
    for (var i = 1; i<= 12; i++) {
        cuota = total_final/i;
        cuota = parseFloat(cuota);
        texto_opt = i+" x "+cuota.toFixed(1);
        var x = document.getElementById("cantidad_cuotas");
        var option = document.createElement("option");
        option.text = texto_opt;
        option.value = i;
        x.add(option);
    }
    if (una_vez) {
      let select_forma = String($("#cantidad_cuotas option:selected").html());
      cantidad_cuotas = String(select_forma);
      una_vez = false;
    }
    $("#cantidad_cuotas").selectpicker("refresh"); 
  }
}

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
}

function agregar_elimina_medio_pago(e){
  var oID = $(e).attr("id");
  var valor = $(e).val();
  if (valor == 1) {
    $(e).val(2);
    $(e).text("Eliminar Medio de Pago");
    id_aux = 2;
    $("#forma_pago_select_2_bloque").show();

  }else{
    $(e).val(1);
    $(e).text("Agregar Medio de Pago");
    id_aux = 1
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
  var tabla = document.getElementById("tabla_pv");
  borrar_options(tabla);
  let tabla_nueva = '<table id="tabla_pv" cellpadding="15" class="table table-bordered " align="center"><thead><tr><th>N°</th><th>Articulo</th><th>Cantidad</th><th>Precio/Unidad</th></tr></thead><tbody><tr id="venta_total"><td  colspan='+"3"+' ><strong>SubTotal:</strong> </td><td  colspan='+"1"+' ><strong> $ <label id="total_venta"><label></strong></td></tr><tr id="venta_total"><td  colspan='+"3"+' ><strong>Total:</strong> </td><td  colspan='+"1"+' ><strong> $ <label id="total_venta_final"><label></strong></td></tr></tbody></table>';
  var div_contenedor_tabla = document.getElementById("Resultados");

  $(div_contenedor_tabla).html(tabla_nueva);

  venta_ .ventas='';
  venta_ .total='';
  articulos = [];
  total_ventas = 0;
  numero = 0;
  Ventas = [];
  Medios_Pagos = [];
  total_final = 0;
  cantidad_cuotas = '';
  final3 = '';
  id_aux = 0;
  una_vez = true;
   
  foco_cajabusqueda();

}

function repaso_general(){
  console.log("Local Seleciona");
  local_seleciona();
  console.log("LCantidad Cuotas");
  cantidad_cuotas_act();
  console.log("Calculo Total");
  calculo_total();
  console.log("Fin Repaso");
  foco_cajabusqueda();

}

function foco_cajabusqueda(){
  if ( $("#CajaBusqueda").length ) {
    document.getElementById("CajaBusqueda").focus();
  }
  
}

