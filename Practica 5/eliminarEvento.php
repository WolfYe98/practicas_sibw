<?php  
	require_once "vendor/autoload.php";
	include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	if($_SERVER['REQUEST_METHOD'] ===  'POST'){
		iniciarConexion();
		if(isset($_POST['eliminarEvento'])){
			eliminarEvento($_POST['eliminarEvento']);
			cerrarConexion();
			unset($_POST['eliminarEvento']);
			header("Location: index.php");
		}
	}

?>