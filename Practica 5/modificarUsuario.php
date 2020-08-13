<?php  
	require_once "vendor/autoload.php";
	include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	
	iniciarConexion();

	session_start();
	
	$userInformation = getUserInformation($_SESSION['username']);
	echo $userInformation['username'];
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		if(isset($_FILES['imagen'])){ //No funciona la variable $_FILEs
	        $errors= array();
        	$file_name = $_FILES['imagen']['name'];
    	    $file_size = $_FILES['imagen']['size'];
	        $file_tmp = $_FILES['imagen']['tmp_name'];
        	$file_type = $_FILES['imagen']['type'];
    	    $file_ext = strtolower(end(explode('.',$_FILES['imagen']['name'])));
        
	        $extensions= array("jpeg","jpg","png");
        
        	if (in_array($file_ext,$extensions) === false){
    	      $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
	        }
        
        	if ($file_size > 2097152){
    	      $errors[] = 'Tamaño del fichero demasiado grande';
	        }
        
        	if (empty($errors)==true) {
    	      move_uploaded_file($file_tmp, "BDimagenes/" . $file_name);
          
	          $userInformation['img'] = "BDimagenes/" . $file_name;
        	}
        
    	    if (sizeof($errors) > 0) {
	          $userInformation['error'] = $errors;
        	}
    	}
    	if(isset($_POST['username']) || isset($_POST['fecha']) ){
    		modificarUser($_POST['username'],$_POST['fecha'],$userInformation['username'],$userInformation['img']);
    	}
    	header("Location: panelUsuario.php");
	}
?>