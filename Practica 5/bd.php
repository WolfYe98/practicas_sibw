<?php
	$mysqli;

	function iniciarConexion(){
		global $mysqli;
		$mysqli = new mysqli("mysql","mingye","ming","Practica3");
		if($mysqli->connect_errno){
			echo ("fallo al conectar" . $mysqli->connect_error);
		}
	}

	function getEvento($idEv){
		global $mysqli;

		$id = (int)$idEv;
		$res = $mysqli->query("SELECT * FROM eventos WHERE id=$id");
  		
  		$infoEvento = array('nombre' => 'XXX', 'lugar' => 'YYY', 'fecha' => 'YYYY-MM-DD', 'descrip1' =>'Descripcion evento 1', 'descrip2' => 'descripcion evento 2');

	  	if($res->num_rows > 0){
  			$row = $res->fetch_assoc();

  			$infoEvento = array('nombre' => $row['nombre'], 'lugar'=>$row['lugar'], 'fecha' => $row['fecha'], 'descrip1' => $row['descrip1'], 'descrip2' => $row['descrip2']);
	  	}
  		return $infoEvento;
	}
	
	function getPalabrasProhibidas($idEv){
		global $mysqli;

		$id = (int)$idEv;
		$stmt = mysqli_prepare($mysqli,"SELECT * FROM palabrasProhibidas WHERE idev=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();

		$res = $stmt->get_result();
		$palabrasP = array();
		if($res->num_rows > 0){
			while(($row = $res->fetch_assoc()) != null){
				array_push($palabrasP,$row['palabra']);
			}
		}
		return $palabrasP;
	}
	
	function getImagenes($idEv){
		global $mysqli;

		$id = (int)$idEv;
		$res = $mysqli->query("SELECT * FROM imagenes WHERE idev=$id");
  		
		$img = array();

	  	if($res->num_rows > 0){
  			while(($row = $res->fetch_assoc()) != null){
  				$imgx = $row['nombreIm'];
  				array_push($img, $imgx);
  			}
	  	}
  		return $img;
	}

	function getComentarios($idEv){
		global $mysqli;

		$id = $id = (int)$idEv;
		$res = $mysqli->query("SELECT * FROM comentarios WHERE id=$id");

		$comentario = array('autor' => 'PEPITO', 'fecha' => 'YYYY-MM-DD', 'hora' => 'HH:MM:SS','coment' => 'XXXXXXXX');
		
		$matrizComentario = array();

		if($res->num_rows > 0){
			
			while(($row = $res->fetch_assoc()) != NULL){
				$comentario = array('autor' => $row['autor'], 'fecha' => $row['fecha'], 'hora' => $row['hora'], 'comentario' => $row['comentario']);
				array_push($matrizComentario, $comentario);
			}
		}

		return $matrizComentario;
	}
	function getAllComentarios(){
		global $mysqli;

		$id = $id = (int)$idEv;
		$res = $mysqli->query("SELECT * FROM comentarios");

		$comentario = array('autor' => 'PEPITO', 'fecha' => 'YYYY-MM-DD', 'hora' => 'HH:MM:SS','coment' => 'XXXXXXXX');
		
		$matrizComentario = array();

		if($res->num_rows > 0){
			
			while(($row = $res->fetch_assoc()) != NULL){
				$comentario = array('autor' => $row['autor'], 'fecha' => $row['fecha'], 'hora' => $row['hora'], 'comentario' => $row['comentario'],'numComentario' =>$row['numComentario']);
				array_push($matrizComentario, $comentario);
			}
		}

		return $matrizComentario;
	}
	function addComment($idEv, $nombre, $email, $comentario){
		global $mysqli;

		$id = (int)$idEv;
		$nom = $nombre;
		$ema = $email;
		$com = $comentario;

		$hoy = getdate();
		$fecha = $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];
		$hora = $hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
		$aux = 1;
		$allComents = getAllComentarios();
		$tamArray = count($allComents);
		$stop = false;
		for($i = 0; $i < $tamArray && (!$stop); ++$i){
			if($allComents[$i]['numComentario'] == $aux){
				++$aux;
			}
			else{
				$stop = true;
			}
		}
		$i = $aux;

		$stmt = null;

		$enviar = true;
		if(empty($nom)){
			$enviar = false;
		}
		if(empty($ema)){
			$enviar = false;
		}
		if(empty($com)){
			$enviar = false;
		}
		if($enviar){
			$stmt = mysqli_prepare($mysqli,"INSERT INTO comentarios(id, autor, fecha, hora, comentario, numComentario) VALUES(?,?,?,?,?,?)");
			
			$stmt->bind_param("issssi",$id,$nom,$fecha,$hora,$com,$i);

			$stmt->execute();

			$stmt->close();
		}

		return $enviar;
	}

	function comentarioRepetido($idEv){
		global $mysqli;
		$com = $_POST['areatexto'];
		$nom = $_POST['nombre'];

		$stmt = mysqli_prepare($mysqli,"SELECT * FROM comentarios WHERE autor=? AND comentario=?");
		
		$stmt->bind_param("ss",$nom,$com);
		
		$stmt->execute();

		$res = $stmt->get_result();
		if($res->fetch_assoc() != null){
			$stmt->close();
			return true;
		}
		$stmt->close();
		return false;
	}
	function checkLogiN($us, $pw){
		global $mysqli;
		$hash = null;
		$row;
		$userInformation = array();
		$stmt = mysqli_prepare($mysqli,"SELECT * FROM usuarios WHERE username=?");
		$stmt->bind_param("s",$us);
		
		$stmt->execute();
		$res = $stmt->get_result();
		if($res->num_rows == 1){
			$row = $res->fetch_assoc();
			$hash = $row['password'];
			if(password_verify($pw, $hash)){
				$userInformation = array('usuario' => $row['username']);
			}
			else{
				$userInformation = array('usuario'=>$row['username'],'pwerror' => true);
			}
		}
		$stmt->close();
		return $userInformation;
	}
	function checkUserNameRepetido($us){
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"SELECT username FROM usuarios WHERE username=?");
		$stmt->bind_param("s",$us);
		$stmt->execute();
		$res = $stmt->get_result();
		if($res->num_rows > 0){
			return true;
		}
		$stmt->close();
		return false;
	}
	function addUsuario($us, $pw, $nacimiento){
		global $mysqli;
		$tipo = 1;
		$hash = password_hash($pw, PASSWORD_DEFAULT);
		$stmt = mysqli_prepare($mysqli,"INSERT INTO usuarios(username,password,nacimiento,tipo) VALUES(?,?,?,?)");
		if(!$stmt){
			return false;
		}
		$stmt->bind_param("sssi",$us,$hash,$nacimiento,$tipo);
		$stmt->execute();
		$stmt->close();
		return true;
	}
	function cerrarConexion(){
		global $mysqli;
		$mysqli->close();
	}


	function modificarUser($newusername, $newuserdate, $oldusername,$img){
		global $mysqli;
		if(($newusername != null) && ($newuserdate != null)){
			$stmt = mysqli_prepare($mysqli,"UPDATE usuarios SET username=?, nacimiento=?, img=? WHERE username=?");
			$stmt->bind_param("ssss",$newusername,$newuserdate,$img,$oldusername);
			$stmt->execute();
			$stmt->close();
		}	
		else if(($newusername != null) && ($newuserdate == null)){
			$stmt = mysqli_prepare($mysqli,"UPDATE usuarios SET username=?,img=? WHERE username=?");
			$stmt->bind_param("sss",$newusername,$img,$oldusername);
			$stmt->execute();
			$stmt->close();
		}
		else if(($newusername == null) && ($newuserdate != null)){
			$stmt = mysqli_prepare($mysqli,"UPDATE usuarios SET nacimiento=?, img=? WHERE username=?");
			$stmt->bind_param("sss",$newuserdate,$img,$oldusername);
			$stmt->execute();
			$stmt->close();
		}
	}

	function getUserInformation($username){
		global $mysqli;
		$arrayUser = array();
		$stmt = mysqli_prepare($mysqli,"SELECT * FROM usuarios WHERE username=?");
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$res= $stmt->get_result();
		if($res->num_rows == 1){
			$row = $res->fetch_assoc();
			if($row != null){
				$arrayUser = array('username' => $row['username'], 'nacimiento' => $row['nacimiento'],'tipo' => $row['tipo'],'img' => $row['img'],'error'=>null);
			}
		}
		$stmt->close();
		return $arrayUser;
	}


	function eliminarComentario($au){
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"DELETE FROM comentarios WHERE numComentario=?");
		$stmt->bind_param("i",$au);
		$stmt->execute();
		$stmt->close();
	}
	function editarComentario($newComent, $oldComent){
		global $mysqli;
		$n = $newComent;
		$o = $oldComent;
		$pos = strpos($n, "Mensaje editado por el moderador");
		if($pos === false){
			$n = $n." <i>(Mensaje editado por el moderador)</i>";
		}
		$stmt = mysqli_prepare($mysqli,"UPDATE comentarios SET comentario=? WHERE comentario=?");
		$stmt->bind_param("ss",$n,$o);
		$stmt->execute();
		$stmt->close();
	}



	function getAllUsers(){
		global $mysqli;
		$usuarios = array();
		$user = array('username' => 'mmm', 'tipo' => 0);
		$stmt = mysqli_prepare($mysqli,"SELECT * FROM usuarios");
		$stmt->execute();
		$res = $stmt->get_result();
		if($res->num_rows>0){
			while(($row = $res->fetch_assoc()) != null){
				$user = array('username'=>$row['username'],'tipo' => $row['tipo']);
				array_push($usuarios, $user);
			}
		}
		$stmt->close();
		return $usuarios;
	}
	function setTipoUser($nombre, $tipo){
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"UPDATE usuarios SET tipo=? WHERE username=?");
		$stmt->bind_param("is",$tipo,$nombre);
		$stmt->execute();
		$stmt->close();
	}


	function eliminarEvento($idEv){
		global $mysqli;
		$id = (int)$idEv;
		$stmt = mysqli_prepare($mysqli,"DELETE FROM eventos WHERE id=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->close();
		$stmt = mysqli_prepare($mysqli,"DELETE FROM imagenes WHERE idev=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->close();
		$stmt = mysqli_prepare($mysqli,"DELETE FROM comentarios WHERE id=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->close();
		$stmt = mysqli_prepare($mysqli,"DELETE FROM imagenIndex WHERE id=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->close();
	}





	function setTituloEvento($id, $ti){
		global $mysqli;
		$idev = (int)$id;
		
		$stmt = mysqli_prepare($mysqli,"UPDATE eventos SET nombre=? WHERE id=?");
		$stmt->bind_param("si",$ti,$idev);
		$stmt->execute();
		$stmt->close();
	}
	function setFechaEvento($id, $fe){
		$idev = (int)$id;
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"UPDATE eventos SET fecha=? WHERE id=?");
		$stmt->bind_param("si",$fe,$idev);
		$stmt->execute();
		$stmt->close();
	}
	function setIntroduccion($id, $fe){
		$idev = (int)$id;
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"UPDATE eventos SET descrip1=? WHERE id=?");
		$stmt->bind_param("si",$fe,$idev);
		$stmt->execute();
		$stmt->close();
	}
	function setDesarrollo($id, $fe){
		$idev = (int)$id;
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"UPDATE eventos SET descrip2=? WHERE id=?");
		$stmt->bind_param("si",$fe,$idev);
		$stmt->execute();
		$stmt->close();
	}
	function addImagenEvento($id, $im){
		$idev = (int)$id;
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"INSERT INTO imagenes(idev,nombreIm) VALUES(?,?)");
		$stmt->bind_param("is",$idev,$im);
		$stmt->execute();
		$stmt->close();
	}
	function setPublicarEvento($id, $pu){
		$idev = (int)$id;
		global $mysqli;
		$stmt = mysqli_prepare($mysqli,"UPDATE eventos SET publicado=? WHERE id=?");
		$stmt->bind_param("ii",$pu,$idev);
		$stmt->execute();
		$stmt->close();
		if((int) $pu == 1){
			$stmt = mysqli_prepare($mysqli,"INSERT INTO publicados VALUES(?)");
			$stmt->bind_param("i",$idev);
			$stmt->execute();
			$stmt->close();
		}
		else{
			$stmt = mysqli_prepare($mysqli,"DELETE FROM publicados WHERE id=?");
			$stmt->bind_param("i",$idev);
			$stmt->execute();
			$stmt->close();
		}
	}

	function getIndexImages(){
		global $mysqli;
		$imagenes = array();
		$stmt = mysqli_prepare($mysqli,"SELECT * FROM imagenIndex");
		$stmt->execute();
		$res = $stmt->get_result();
		if($res->num_rows > 0){
			while(($row = $res->fetch_assoc()) != null){
				$aux = array('id' => $row['id'],'img' => $row['nombre']);
				array_push($imagenes,$aux);
			}
		}
		return $imagenes;
	}
	function getIndexImagesPublicados(){
		global $mysqli;
		$imagenes = array();
		$stmtPublicados =mysqli_prepare($mysqli,"SELECT * FROM publicados");
		$stmtPublicados->execute();
		$resultado = $stmtPublicados->get_result();
		if($resultado->num_rows>0){
			while(($row = $resultado->fetch_assoc()) != null){
				$stmt = mysqli_prepare($mysqli,"SELECT * FROM imagenIndex WHERE id=?");
				$stmt->bind_param("i",$row['id']);
				$stmt->execute();
				$res = $stmt->get_result();
				if($res->num_rows > 0){
					if(($row = $res->fetch_assoc()) != null){
						$aux = array('id' => $row['id'],'img' => $row['nombre']);
						array_push($imagenes,$aux);
					}
				}
				$stmt->close();
			}
		}
		$stmtPublicados->close();
		
		return $imagenes;
	}


	function addEvento($tit,$lug,$fech,$des1,$des2,$im,$imIndex,$pub){
		global $mysqli;
		$aniadido = array();
		$errores = array('error'=>true);
		$resNumber = $mysqli->query("SELECT * FROM eventos");
		$id = 1;
		$stop = false;
		if($resNumber->num_rows > 0){
			$lastID;
			$aux = 1;
			while(($row=$resNumber->fetch_assoc()) != null && (!$stop)){
				if($row['id'] == $aux){
					++$aux;
				}
				else{
					$stop = true;
				}
			}
			$id = $aux;
		}
		if(strlen($tit) > 3){
			if(strlen($imIndex) > 2){
				if(strlen($fech) > 2){
					if(strlen($lug) > 2){
						$stmt=mysqli_prepare($mysqli,"INSERT INTO eventos VALUES(?,?,?,?,?,?,?)");
						$stmt->bind_param("isssssi",$id,$tit,$lug,$fech,$des1,$des2,$pub);
						$stmt->execute();
						$stmt->close();
						$stmt=mysqli_prepare($mysqli,"INSERT INTO imagenIndex VALUES(?,?)");
						$stmt->bind_param("is",$id,$imIndex);
						$stmt->execute();
						$stmt->close();
						if(strlen($im) > 3){
							$stmt=mysqli_prepare($mysqli,"INSERT INTO imagenes VALUES(?,?)");
							$stmt->bind_param("is",$id,$im);
							$stmt->execute();
							$stmt->close();
						}
						else{
							$img = "NULL";
							$stmt=mysqli_prepare($mysqli,"INSERT INTO imagenes VALUES(?,?)");
							$stmt->bind_param("is",$id,$img);
							$stmt->execute();
							$stmt->close();
						}
						$aniadido = array('aniadido' => true);
					}
				}
			}
		}
		if(strlen($tit) < 3){
			$aux = array('errorTitulo' => true);
			array_push($errores, $aux);
		}
		if(strlen($lug) < 2){
			$aux = array('errorLugar' => true);
			array_push($errores, $aux);
		}
		if(strlen($fech) < 2){
			$aux = array('errorFecha' => true);
			array_push($errores, $aux);
		}
		if(strlen($imIndex) < 2){
			$aux = array('errorImgIndex' => true);
				array_push($errores, $aux);
		}
		if(count($aniadido) != 0){
			return $aniadido;
		}

		if((int)$pub == 1){
			$stmt = mysqli_prepare($mysqli,"INSERT INTO publicados value(?)");
			$stmt->bind_param("i",$id);
			$stmt->execute();
			$stmt->close();
		}
		return $errores;
	}


?>