<?php 
	require_once "vendor/autoload.php";
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	include("bd.php");

	$usernameerror = false;
	iniciarConexion();
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if(!checkUserNameRepetido($_POST['usuario'])){
			if(addUsuario($_POST['usuario'],$_POST['contrasenia'],$_POST['nacimiento'])){
				header("Location: login.php?ev=".$_GET['ev']);
			}
		}
		else{
			$usernameerror = true;
		}
	}
	cerrarConexion();
	echo $twig->render('register.html',['ev'=>$_GET['ev'], 'usernameerror' => $usernameerror] );
?>