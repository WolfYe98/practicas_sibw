<?php #comm
	require_once "vendor/autoload.php";
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	include("bd.php");

	iniciarConexion();
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$us = $_POST['usuario'];
		$pw = $_POST['contrasenia'];
		$log = checkLogIn($us,$pw);
		$usernameerror = false;
		$nombreUsuario;
		$passworderror = false;
		$isLogged = count($log);
		//Anteriormente log ha tenido que recibir algo desde checkLogIn(), en caso contrario, el username introducido por el usuario no estaria en nuestra base de datos
		if($isLogged == 0){
			$usernameerror = true;
		}
		else{
			if(count($log) > 1){	//checkLogIn devuelve solamente un array con el username si esta bien el username y la contraseña, y sino devuelve el username y un pwerror que es un booleano.
				$passworderror = true;
				unset($log['pwerror']);
			}
			else{
				$nombreUsuario = $log['usuario'];
				session_start();
				$_SESSION['username'] = $nombreUsuario;
				if(isset($_GET['ev'])){
					if($_GET['ev'] == 0){
						header("Location: index.php?ev=".$_GET['ev']);
					}
					else if($_GET['ev'] != 0){
						header("Location: evento.php?ev=".$_GET['ev']);
					}
				}
				else{
					header("Location: index.php");
				}
				exit();
			}
		}
	}
	cerrarConexion();
	//En caso de que se equivoquen en user o en contraseña, html con twig lo capta y lo imprimirá en pantalla
	echo $twig->render('login.html', ['usernameerror' => $usernameerror,'passworderror' => $passworderror, 'ev'=>$_GET['ev']]);
?>