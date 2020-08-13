$(obtenerResultado());
function obtenerResultado(valores){
	$.ajax(
		{
			url:"../consultaIndex.php",
			type: 'POST',
			dataType: 'html',
			data:{valores: valores},
		}
		)
	.done(function (res){
		$('.imagenes').html(res);
	})
}

$(document).on('keyup', '#busqueda', function() {
	var v = $(this).val();
	if(v != ""){
		obtenerResultado(v);
	}
	else{
		obtenerResultado();
	}
});
