<?php  
	require_once "vendor/autoload.php";
	include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	$evento;
	$imagenes;
	$id;
	if(isset($_GET['ev'])){
		$id = $_GET['ev'];
	}
	iniciarConexion();
	if($_SERVER['REQUEST_METHOD'] ===  'POST'){
		if(isset($_POST['editarEvento'])){
			$evento = getEvento($_POST['editarEvento']);
			$imagenes = getImagenes($_POST['editarEvento']);
		}
		if(isset($_POST['titulo'])){
			setTituloEvento($id,$_POST['titulo']);
		}
		if(isset($_POST['fechaEvento'])){
			setFechaEvento($id,$_POST['fechaEvento']);
		}
		if(isset($_POST['descripcion1'])){
			setIntroduccion($id,$_POST['descripcion1']);
		}
		if(isset($_POST['descripcion2'])){
			setDesarrollo($id,$_POST['descripcion2']);
		}
		if(isset($_POST['publicar'])){
			setPublicarEvento($id,$_POST['publicar']);
		}
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
    	      addImagenEvento($id,$nombreIm);
	        }
    	    if (sizeof($errors) > 0) {
	          $nombreIm['errores'] = $errors;
        	}
		}
		if(isset($_POST['guardar'])){
			header("Location: evento.php?ev=".$id);
		}
	}
	echo $twig->render("modificarEvento.html",['evento' => $evento,'imagenes' => $imagenes,'id' => $id]);
	cerrarConexion();
?>