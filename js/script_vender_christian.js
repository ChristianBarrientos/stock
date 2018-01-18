var unasola = true;
var articulos =  new Array();
var total_ventas = 0;
var numero = 1;
var Ventas = new Array();

function Venta(id_lote, cantidad) {
  this.id_lote = id_lote;
  this.cantidad = cantidad;
}

$(document).ready(function()
    {    
      	/*$("#tags").autocomplete({
			source: availableTags,
		});*/ 
    	$("#CajaBusqueda").keyup(function(){
    		
            let Busqueda = $("#CajaBusqueda").val();
            let Datos = new FormData();

            if (Busqueda.length == 2 && (Busqueda != '' || Busqueda != null)) {
             
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
                    //console.log(Respuesta);
                    var valores = JSON.parse(Respuesta);

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
                                   let final3 = final22.concat(attr);
                                   //let final33 = final3.concat(',');
                                   //let final4 = final33.concat(moneda);
						           articulos.push(final3);
                                   articulos = articulos.unique();
						           //console.log(valores.result[i].id_lote);
						           //console.log(valores.result[i].importe);
						           //console.log(valores.result[i].precio_base);

					        } 
					    }
					                        	
					}else{
                        $("#Sinresultados").html('Sin Coincidencias');
                    }

                    //console.log(articulos);
                    $("#CajaBusqueda").autocomplete({
						source: articulos,
						select: function (event, item) {
							//console.log(item.item);
							out = item.item.value.split(',');
              //console.log(out);
							var params = {
								lote: out[3]
							}; 
                             
							$.get("template/venta_/ajax_venta2.php", params, function (response) {
                                
								var json = JSON.parse(response);

								if (json.status == 'ok'){
                  //console.log(json.result);
                  let costo = json.result.precio_base;
                  let importe = json.result.importe;
                  let moneda = json.result.moneda;
                  var precio_aux = importe * costo;
                  var precio_final = precio_aux * moneda;
                  id_input = "cantidad"+numero;
                  input = "<input id='"+id_input+"' type='number' size='5' value='1' min='1'>";

                  //var fila="<tr id="+numero+"><td>"+numero+"</td><td>"+item.item.value+"</td><td WIDTH='10'>"+input+"</td><td>"+precio_final.toFixed(2)+"</td><td><input id='cerrar-modal' name='modal' type='radio' /> <label for='cerrar-modal'> X </label> </td></tr>";
                  var fila="<tr id="+numero+"><td>"+numero+"</td><td>"+item.item.value+"</td><td WIDTH='10'>"+input+"</td><td>"+precio_final.toFixed(2)+"</td></tr>";
                  $('#venta_total').before(fila);

                  numero = numero + 1;

                  
                  total_ventas = parseFloat(total_ventas) + parseFloat(precio_final.toFixed(2));
                  $("#total_venta").text(total_ventas);
								}else{
									console.log("Sin Resultados");
								}
                venta = new Venta(out[3],$("#"+id_input).val());
                 
                Ventas.push(venta);
               
							}); 
              	
               
              
						}
					});  	
                        
        }
        });

  			} 

  		});

  $('#btn_vender').click(function(){ 

      alert("Venderas");
      console.log(Ventas);

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



