function mostrarDatos(){
	document.getElementById("formularioDatos").style.display = "flex";
	document.getElementById("ordenarComents").style.display = "none";
	document.getElementById("ordenarEventos").style.display = "none";
	document.getElementById("aniadirEvento").style.display="none";
	document.getElementById("modificarTipoUsuario").style.display="none";
}

function mostrarComents(){
	document.getElementById("formularioDatos").style.display = "none";
	document.getElementById("ordenarComents").style.display = "flex";
	document.getElementById("ordenarEventos").style.display = "none";
	document.getElementById("aniadirEvento").style.display="none";
	document.getElementById("modificarTipoUsuario").style.display="none";
}
function mostrarEventos(){
	document.getElementById("formularioDatos").style.display = "none";
	document.getElementById("ordenarComents").style.display = "none";
	document.getElementById("ordenarEventos").style.display = "flex";
	document.getElementById("aniadirEvento").style.display="none";
	document.getElementById("modificarTipoUsuario").style.display="none";
}
function mostrarAniadirEventos(){
	document.getElementById("formularioDatos").style.display = "none";
	document.getElementById("ordenarComents").style.display = "none";
	document.getElementById("ordenarEventos").style.display = "none";
	document.getElementById("aniadirEvento").style.display="flex";
	document.getElementById("modificarTipoUsuario").style.display="none";
}
function mostrarTipoUser(){
	document.getElementById("formularioDatos").style.display = "none";
	document.getElementById("ordenarComents").style.display = "none";
	document.getElementById("ordenarEventos").style.display = "none";
	document.getElementById("aniadirEvento").style.display="none";
	document.getElementById("modificarTipoUsuario").style.display="flex";
}

function ajustarComentarios(){
	var x = document.getElementsByClassName("coments");
	if(window.outerWidth < 700){
		for(i = 0; i < x.length; i++){
			x[i].style.font_size="60%";
		}
	}	
}