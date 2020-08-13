<?php 
	session_start();
	session_destroy();
	if(isset($_GET['ev'])){
		if($_GET['ev'] == 0){
			header("Location: index.php");
		}
		else{
			header("Location: evento.php?ev=".$_GET['ev']);
		}
	}
	else{
		header("Location: index.php");
	}
	exit();
?>