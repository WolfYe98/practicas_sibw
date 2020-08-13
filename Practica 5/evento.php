<?php
	require_once "vendor/autoload.php";
	include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	$sesionIniciada = false;
	$userinfo = array();
	iniciarConexion();
	session_start();
	if(isset($_GET['ev'])){
		$idEv = $_GET['ev'];
	}
	else{
		$idEv = -1;
	}
	if(isset($_SESSION['username'])){
		$sesionIniciada = true;
		$userinfo = getUserInformation($_SESSION['username']);
	}
	$evento = getEvento($idEv);
	$img = getImagenes($idEv);
	$matrizComentario = getComentarios($idEv);
	$palabrasP = getPalabrasProhibidas($idEv);
	echo $twig->render('evento.html',['evento' => $evento, 'img' => $img, 'comentarios' => $matrizComentario, 'idEvento' => $idEv,'palabrasPro' => $palabrasP, 'sesion' => $sesionIniciada,'userinfo' => $userinfo]);

	cerrarConexion();
?>