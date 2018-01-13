var unasola = true;
var articulos =  new Array();
$(document).ready(function()
    {   
    	$("#CajaBusqueda").keyup(function(){
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
                        	 
                        	for (var i = 0; i <= valores.result.length; i++) {

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

 
		var items = Respuesta;

		$("#tag").autocomplete({
			source: items,
			select: function (event, item) {
				var params = {
					equipo: item.item.value
				};
				 
				var json = JSON.parse(response);
				$("#nombre").html(json.nombre);
				$("#avatar").attr("src", json.icono);
					 
				 
			}
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
        	})

    		

        /*$('#bloodhound .typeahead').typeahead(null, {
            name: 'states',
            limit: 10,
            source: states
        });*/
    });