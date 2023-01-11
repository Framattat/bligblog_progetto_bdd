// funzione per inviare form in asincrono ho come parametri: 
// il bottone (che viene disabilitato 2000ms per evitare spam) e 
// la key dei form e box errore dove stamperò gli errori se presenti
	function invia_form(key,button){
		console.log(button);
		button.attr("disabled","disabled");
		$('#box_errori_'+key).hide();
		//creo le variabili per 
		var form = $('#form_'+key);
		var postData = form.serializeArray();
		var formURL = form.attr("action_validate");
		$.ajax({
			// parametri della funzione ajax
			url : formURL,
			type: "POST",
			//data: sono i dati ritornati dalla pagina a cui punta il form
			data : postData,
			success:function(data, textStatus, jqXHR){	
				//se i dati in post sono ok, procedo e submito il form, verrà eseguito un controllo in sincrono 
				if (data == "1"){
					console.log("tutto ok");
					$("#form_"+key).submit();
					return true;
				} else {
					//il div box errori stampa gli errori controllati col validatore
					$("#box_errori_"+key).html(data).slideDown();
					console.error(data);
				}
				//funzione che disabilita il bottone per 2000ms
				setTimeout(function(){
					button.removeAttr("disabled");
				}, 2000);
			},
			error: function(jqXHR, textStatus, errorThrown){
				//se fallisce l'invio	
				console.error(errorThrown);
				setTimeout(function(){
					button.removeAttr("disabled");
				}, 2000);
			}
		});
	};