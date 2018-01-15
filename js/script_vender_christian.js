var unasola = true;
var articulos =  new Array();

var numero = 1;
$(document).ready(function()
    {    
      	/*$("#tags").autocomplete({
			source: availableTags,
		});*/ 
    	$("#CajaBusqueda").keyup(function(){
    		
            let Busqueda = $("#CajaBusqueda").val();
            let Datos = new FormData();
            if (Busqueda.length == 2 && Busqueda != ' ') {
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
					//if (valores.status == 'ok') {
					                        //console.log(Object.keys(valores.result));
					    //console.log(valores.result[0]);
					                        
					                   	 
					    for (var i = 0; i <= valores.result.length; i++) {

					        if (typeof valores.result[i] !== 'undefined') {
							  art = valores.result[i].Articulo;
						           marca = ','.concat(valores.result[i].Marca);
						           tipo = ','.concat(valores.result[i].Tipo);
						           art_marca = art.concat(marca);
						           id_lote = valores.result[i].id_lote;
						           precio_final = valores.result[i].precio_base;
						           //moneda = valores.result[i].Moneda;

						           let nombre_art = art_marca.concat(tipo);
						           let final = nombre_art.concat(',');
						           let final2 = final.concat(id_lote);
						           articulos.push(final2);
						           //console.log(valores.result[i].id_lote);
						           //console.log(valores.result[i].importe);
						           //console.log(valores.result[i].precio_base);

					        } 
					    }
					                        	
					//} 

                    //console.log(articulos);
                    $("#CajaBusqueda").autocomplete({
						source: articulos,
						select: function (event, item) {
							//console.log(item.item);
							out = item.item.value.split(',');
							var params = {
								lote: out[3]
							}; 

							$.get("template/venta_/ajax_venta2.php", params, function (response) {

								var json = JSON.parse(response);
								if (json.status == 'ok'){
									
									//console.log(json.result);
                                    //console.log(numero);
                                    let precio_costo = json.result.precio_base;
                                    let importe = json.result.importe;
                                    let moneda = json.result.moneda;
                                    if (numero == 1) {

                                        //$("#Numero").html(numero);
                                        //$("#Articulo").html(item.item.value);
                                        //$("#Precio").html(precio_costo);
                                        //$("#Lote").html(out[3]);
                                        console.log(numero);
                                        //Para los Inputs 
                                        if (numero == 1) {
                                            var fila="<tr><td>"+numero+"</td><td>"+item.item.value+"</td><td>"+precio_costo+"</td><td>"+out[3]+"</td></tr>";
                                            document.getElementById("tablita").innerHTML = fila;
                                        }

                                    }else{

                                    }
									

                                   
                                    numero = numero + 1;
								}else{
									console.log("Sin Resultados");
								}
							}); 
							//console.log(out[3]);
							$("#Articulo").html(item.item.value);
							 
							
						}
					});  	
                        
                }
                });

  			}

  		});
	});	
	 
    	/*$("#CajaBusqueda").keyup(function(){
    		unasola = true;
            let Busqueda = $("#CajaBusqueda").val();
            let Datos = new FormData();
            if (Busqueda.length == 2 && Busqueda != ' ') {
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
                        //console.log(Object.keys(valores.result));
                        //console.log(valores.result[0]);
                        /*for(var aux in valores.result)
                            console.log(aux['Articulo']);*/
                        	 
                        	/*for (var i = 0; i <= valores.result.length; i++) {

                        		if (typeof valores.result[i] !== 'undefined') {
									art = valores.result[i].Articulo;
	                        		marca = ','.concat(valores.result[i].Marca);
	                        		tipo = ','.concat(valores.result[i].Tipo);
	                        		art_marca = art.concat(marca);

	                        		let nombre_art = art_marca.concat(tipo);
	                        		articulos.push(nombre_art);

	                        		//console.log(valores.result[i].id_lote);
	                        		//console.log(valores.result[i].importe);
	                        		//console.log(valores.result[i].precio_base);

                        		} 
                        	}
                        	
                       	}
                          
                    	}
                     
                    
             
                })
            
           	/*select: function (event, item) {
								console.log(Respuesta);
								alert("AcS");
								var valores = JSON.parse(item);
				                if (typeof valores.result[i] !== 'undefined') {
									art = valores.result[i].Articulo;
					                marca = ','.concat(valores.result[i].Marca);
					                tipo = ','.concat(valores.result[i].Tipo);
					                art_marca = art.concat(marca);
					                let nombre_art = art_marca.concat(tipo);
					                $("#Articulo").html(nombre_art);	 
				                } 	 
							}
            var articulos = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
            	'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            	'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
            	'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
            	'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
            	'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
            	'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
            	'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
            	'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
        	];
        	//console.log(articulos2);
        	var states = new Bloodhound({
            	datumTokenizer: Bloodhound.tokenizers.whitespace,
            	queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            	local: articulos
        	});

        	$('#bloodhound .typeahead').typeahead({
                hint: true,
                highlight: true,
                minLength: 4
            },
            {
                name: 'states',
                source: states
            });

            }//Cierre de la funcion Keyup
            console.log(articulos);
        	})*/	

    		

        /*$('#bloodhound .typeahead').typeahead(null, {
            name: 'states',
            limit: 10,
            source: states
        });*/
    //});