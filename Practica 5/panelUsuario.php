<?php  
	require_once "vendor/autoload.php";
	include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	
	iniciarConexion();
	session_start();
	$userInformation = getUserInformation($_SESSION['username']);
	$allusers;
	if($userInformation['img'] == null){
		$userInformation['img'] = "BDimagenes/default.png";
	}
	$comentarios = getAllComentarios();
	if(count($comentarios) <= 0){
		$comentarios = null;
	}
	if($userInformation['tipo'] == '4'){
		$allusers = getAllUsers();
	}
	
	cerrarConexion();
	echo $twig->render('panel.html',['userinfo' => $userInformation,'comentarios' => $comentarios,'usuarios'=>$allusers]);
?>