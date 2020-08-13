function mostrarComentarios(){
    document.getElementById("coment1").style.display = "block";
    document.getElementById("comentarOcultar").style.display = "block";
    document.getElementById("comentarMostrar").style.display="none";
    document.getElementById("introducirComentario").style.display = "block";
    document.getElementById("tituloComentarios").style.display = "block";
    document.getElementById("asideComent").style.right="0px";
}
function ocultarComentarios(){
    document.getElementById("coment1").style.display = "none";
    document.getElementById("comentarOcultar").style.display="none";
    document.getElementById("comentarMostrar").style.display="block";
    document.getElementById("introducirComentario").style.display="none";
    document.getElementById("tituloComentarios").style.display="none";
    document.getElementById("asideComent").style.right="-200px";
}

function validarEmail(val){
    var expresionMail = /^\w+([\.-]*\w*)*@\w+([\.-]*\w*)*\.(\w{2,4})+$/;
    var correcto=true;
    if(!expresionMail.test(val)){
        correcto=false;
    }
    return correcto;
}
function todoRelleno(){
    var relleno=true;
    var nom= document.getElementById('nombre').value;
    var em = document.getElementById('mail').value;
    var atext =document.getElementById('areaComentario').value;
    if(nom.length == 0){
        relleno = false;
        alert('No has introducido el nombre');
    }
    if(em.length == 0){
        relleno = false;
        alert('No has introducido el email');
    }
    else{
        if(!validarEmail(em)){
            alert("Correo no valido");
            document.getElementById('mail').value="";
        }
    }
    if(atext.length == 0){
        relleno = false;
        alert('No has introducido ningun comentario');
    }
    return relleno;
}


var coment;
var palabrasProhibidas=[];

function añadeProhibidas(pal){
    palabrasProhibidas.push(pal);
}
function cambiarPalabra(pala){
    var nuevo = pala;
    var ind;

    for(i = 0; i < palabrasProhibidas.length; i++){
        if(pala.indexOf(palabrasProhibidas[i]) != -1){
        	var num = palabrasProhibidas[i].length;
        	var estrellas="*";
        	for(j = 0; j < num; j++){
        		estrellas += "*";
        	}
        	nuevo = nuevo.replace(palabrasProhibidas[i], estrellas);
        	console.log(nuevo);
        }
    }
    if(nuevo != pala){
    	document.getElementById('areaComentario').value = nuevo;
    }
}
function revisarPalabrasProhibidas(){
    coment =new String(document.getElementById('areaComentario').value);
    cambiarPalabra(coment);
    
}
function addComentario(){
    var nuevoComent = document.createElement("div","id=coment3");
    var nuevoAutor = document.createElement("h5");
    var nuevaFecha = document.createElement("h6");
    var nuevoC = document.createElement("p");
    var au = document.createTextNode(document.getElementById('nombre').value);
    var fech = new Date();
    nuevaFecha.classList.add('fecha');

    nuevoAutor.appendChild(au);
    nuevaFecha.appendChild(document.createTextNode(fech.getDate()+"/"+fech.getMonth()+"/"+fech.getFullYear()));
    nuevaFecha.appendChild(document.createElement("br"));
    if(fech.getMinutes() >= 10){
        nuevaFecha.appendChild(document.createTextNode(fech.getHours()+":"+fech.getMinutes()));
    }
    else{
        nuevaFecha.appendChild(document.createTextNode(fech.getHours()+":"+"0"+fech.getMinutes()));
    }
    nuevoC.appendChild(document.createTextNode(document.getElementById('areaComentario').value));
    nuevoComent.appendChild(nuevoAutor);
    nuevoComent.appendChild(nuevaFecha);
    nuevoComent.appendChild(nuevoC);
    document.getElementById('comentarioExistente').appendChild(nuevoComent);
}
function mandarComentario(){
    var rel = todoRelleno();
    var em = validarEmail(document.getElementById('mail').value);
    if(rel){
        if(em){
            addComentario();
        }
    }
}



//SLIDER
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlides");
    if (n > x.length) {slideIndex = 1}
    if (n < 1) {slideIndex = x.length}
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
    }
    x[slideIndex-1].style.display = "block";  
}