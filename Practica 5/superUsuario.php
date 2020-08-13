<?php  
	require_once "vendor/autoload.php";
	include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	iniciarConexion();
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if(isset($_POST['cambiarTipoUser'])){
			setTipoUser($_POST['cambiarTipoUser'],$_POST['tipo']);
		}
	}
	header("Location: panelUsuario.php");
?>