<?php
	require_once "vendor/autoload.php";
	include("bd.php");
	
	iniciarConexion();
	
	if(isset($_GET['ev'])){
		$idEv = $_GET['ev'];
	}
	else{
		$idEv = -1;
	}
	if(is_numeric($idEv)){
		if(!empty($_POST['nombre'])){
			if(!empty($_POST['email'])){
				if(!empty($_POST['areatexto'])){
					if(!comentarioRepetido($idEv)){
						addComment($idEv, $_POST['nombre'], $_POST['email'], $_POST['areatexto']);
						echo "Comentario añadido!";
						echo '<br/>';
						echo '<a href="evento.php?ev='.$idEv.'">Click aqui para volver a la pagina evento</a>';
					}
					else{
						echo "El comentario es un comentario repetido \n";
						echo '<a href="evento.php?ev='.$idEv.'">Click aqui para volver a la pagina evento</a>';
					}
					cerrarConexion();
				}
			}
		}
	}
	

?>