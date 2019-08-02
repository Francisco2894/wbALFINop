$("#searchTxts").change(event => {
	$.get(`asesor/${event.target.value}`, function(res, sta){
		$("#searchTxt").empty();
		$("#searchTxt").append(`<option value="0">Ninguno</option>`);
		$("#searchTxt").append(`<option value="T0ALL">Todos</option>`);
		res.forEach(element => {
			$("#searchTxt").append(`<option value=${element.idPerfil}> ${element.nombre} </option>`);
		});
	});
});

$("#searchTxtsg").change(event => {
	$.get(`gestores/${event.target.value}`, function(res, sta){
		$("#searchTxt").empty();
		$("#searchTxt").append(`<option value="0"> Ninguno </option>`);
		res.forEach(element => {
			$("#searchTxt").append(`<option value=${element.idPerfil}> ${element.nombre} </option>`);
		});
	});
});
