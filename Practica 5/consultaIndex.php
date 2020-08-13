<?php  
	$mysqli;
	$mysqli = new mysqli("mysql","mingye","ming","Practica3");
	if($mysqli->connect_errno){
		echo ("fallo al conectar" . $mysqli->connect_error);
	}

	$tabla ="";
	session_start();
	if(isset($_SESSION['tipo'])){
		if($_SESSION['tipo'] > 3){
			$stmt = mysqli_prepare($mysqli,"SELECT id FROM eventos");
		}
		else{
			$stmt = mysqli_prepare($mysqli,"SELECT eventos.id FROM eventos INNER JOIN publicados ON eventos.id=publicados.id");
		}
	}
	else{
		$stmt = mysqli_prepare($mysqli,"SELECT eventos.id FROM eventos INNER JOIN publicados ON eventos.id=publicados.id");
	}
	
	if(isset($_POST['valores'])){
		$valor = $mysqli->real_escape_string($_POST['valores']);
		$stmt = mysqli_prepare($mysqli,"SELECT id FROM eventos WHERE 
			id LIKE '%".$valor."%'
			OR nombre LIKE '%".$valor."%' 
			OR descrip1 LIKE '%".$valor."%' 
			OR descrip2 LIKE '%".$valor."%'
				");
		if(isset($_SESSION['tipo'])){
			if($_SESSION['tipo'] > 3){
				$stmt = mysqli_prepare($mysqli,"SELECT id FROM eventos WHERE id LIKE '%".$valor."%'OR nombre LIKE '%".$valor."%' OR descrip1 LIKE '%".$valor."%' OR
					descrip2 LIKE '%".$valor."%'
				");
			}
			else{
				$stmt = mysqli_prepare($mysqli,"SELECT eventos.id FROM eventos INNER JOIN publicados ON eventos.id=publicados.id WHERE eventos.id LIKE '%".$valor."%' OR eventos.nombre LIKE '%".$valor."%' OR eventos.descrip1 LIKE '%".$valor."%' OR
					eventos.descrip2 LIKE '%".$valor."%'
				");
			}
		}
		else{
			$stmt = mysqli_prepare($mysqli,"SELECT eventos.id FROM 
				(eventos inner JOIN publicados ON eventos.id=publicados.id)
				WHERE eventos.id=publicados.id AND (eventos.id LIKE '%".$valor."%' 
				OR eventos.nombre LIKE '%".$valor."%' 
				OR eventos.descrip1 LIKE '%".$valor."%' 
				OR eventos.descrip2 LIKE '%".$valor."%')
			");
		}
			
	}
	$stmt->execute();
	$res = $stmt->get_result();
	if($res->num_rows>0){
		while(($row = $res->fetch_assoc()) != null){
			$stmtImg = mysqli_prepare($mysqli,"SELECT * FROM imagenIndex where id=?");
			$stmtImg->bind_param("i",$row['id']);
			$stmtImg->execute();
			$resImg = $stmtImg->get_result();
			if($resImg->num_rows>0){
				$rowImg = $resImg->fetch_assoc();
				if($rowImg != null){
					$id=$rowImg['id'];
					$nombre = $rowImg['nombre'];
					$tabla .= '<div><abbr title="Ir a evento: '.$id.'"><a href="evento.php?ev='.$id.'" ><img src="'.$nombre.'" class="imagenEvento" alt="Imagen"></a></abbr><h6></h6></div>';
				}
			}
			$stmtImg->close();
		}
	}
	else{
		$tabla = "No se han encontrado coincidencias";
	}
	echo $tabla;
?>