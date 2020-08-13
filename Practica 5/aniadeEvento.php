<?php
	require_once "vendor/autoload.php";
	include("bd.php");
  	$loader = new \Twig\Loader\FilesystemLoader('templates');
  	$twig = new \Twig\Environment($loader);
  	$aniade;
  	$nombreIm="";
  	$nombreImIndex="";
  	$erroresIndex;
  	$errores;
  	iniciarConexion();
  	if($_SERVER['REQUEST_METHOD'] === 'POST'){
  		if(isset($_POST['tituloEvento']) && isset($_FILES['imagenIndex'])){
  			if(isset($_FILES['imagen'])){
				$errors= array();
        		$file_name = $_FILES['imagen']['name'];
    		    $file_size = $_FILES['imagen']['size'];
		        $file_tmp = $_FILES['imagen']['tmp_name'];
	        	$file_type = $_FILES['imagen']['type'];
    	    	$file_ext = strtolower(end(explode('.',$_FILES['imagen']['name'])));
		        $nombreIm;
        		$extensions= array("jpeg","jpg","png");
	        
    	    	if (in_array($file_ext,$extensions) === false){
	    	      $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
    	    	}
        
	    	    if ($file_size > 2097152){
	        	  $errors[] = 'Tamaño del fichero demasiado grande';
        		}
    	    
	    	    if (empty($errors)==true) {
	        	  move_uploaded_file($file_tmp, "BDimagenes/" . $file_name);
        	  
    		      $nombreIm = "BDimagenes/" . $file_name;
	        	}
    		    if (sizeof($errors) > 0) {
		          $errores =$errors;
	        	}
			}
			if(isset($_FILES['imagenIndex'])){
				$errors= array();
        		$file_name = $_FILES['imagenIndex']['name'];
    		    $file_size = $_FILES['imagenIndex']['size'];
		        $file_tmp = $_FILES['imagenIndex']['tmp_name'];
	        	$file_type = $_FILES['imagenIndex']['type'];
    	    	$file_ext = strtolower(end(explode('.',$_FILES['imagenIndex']['name'])));
		        $nombreIm;
        		$extensions= array("jpeg","jpg","png");
	        
    	    	if (in_array($file_ext,$extensions) === false){
	    	      $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
    	    	}
        
	    	    if ($file_size > 2097152){
	        	  $errors[] = 'Tamaño del fichero demasiado grande';
        		}
    	    
	    	    if (empty($errors)==true) {
	        	  move_uploaded_file($file_tmp, "BDimagenes/" . $file_name);
        	  
    		      $nombreImIndex = "BDimagenes/" . $file_name;
	        	}
    		    if (sizeof($errors) > 0) {
    		    	$erroresIndex = $errors;
	        	}
			}
		}
	  $aniade = addEvento($_POST['tituloEvento'],$_POST['lugar'],$_POST['fechaEvento'],$_POST['descripcion1'],$_POST['descripcion2'],$nombreIm,$nombreImIndex,$_POST['publicar']);

  	if(!isset($aniade['error'])){
  		header("Location: index.php");
	 	}
	}

	echo $twig->render("aniadeEvento.html",['errores'=>$aniade]);
?>