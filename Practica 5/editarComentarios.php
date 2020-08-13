<?php 
	require_once "vendor/autoload.php";
	include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	iniciarConexion();
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if(isset($_POST['eliminarComentario'])){ //eliminarComentario es un int, y es el numero del comentario
			eliminarComentario($_POST['eliminarComentario']);
			unset($_POST['eliminarComentario']);
		}
		else{
			if(isset($_POST['editarComentario'])){
				editarComentario($_POST['newComentario'],$_POST['editarComentario']);
				unset($_POST['newComentario']);
				unset($_POST['editarComentario']);
			}
		}
		header("Location: panelUsuario.php");
	}

?>