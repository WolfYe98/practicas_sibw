<?php
  require_once "vendor/autoload.php";
  include("bd.php");
  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  $sesionIniciada = false;
  $imagenes;
  $userinfo;
  session_start();
  iniciarConexion();
  if(isset($_SESSION['username'])){
  	$sesionIniciada = true;
  	$userinfo = getUserInformation($_SESSION['username']);
    if($userinfo['tipo'] >= 3){
      $imagenes = getIndexImages();
      $_SESSION['tipo'] = $userinfo['tipo'];
    }
    else{
      $imagenes = getIndexImagesPublicados();
    }
  }
  else{
    $imagenes = getIndexImagesPublicados();
  }
  echo $twig->render('index.html', ['sesionIniciada' => $sesionIniciada,'imagenes' => $imagenes,'usuario' => $userinfo]);
?>
