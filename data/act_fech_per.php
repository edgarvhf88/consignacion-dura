<?php include("conexion.php"); 
				  
		if (isset($_POST['fecha_ini'])) {
			$fecha_ini = $_POST['fecha_ini'];
			$fecha_fin = $_POST['fecha_fin'];
			$id_usuario = $_SESSION["logged_user"];
			act_fechas($fecha_ini,$fecha_fin,$id_usuario);
	  	}
	function act_fechas($fecha_ini,$fecha_fin,$id_usuario)
	{
		global $conex;
		$update = "UPDATE usuarios SET fecha_ini_reg='$fecha_ini', fecha_fin_reg='$fecha_fin' WHERE id = $id_usuario";
		if (mysql_query($update, $conex) or die(mysql_error()))
		{ 	
			echo '<script> console.log("Se actualizo fechas de periodo");
				</script>';  
		}
	}
		
					
?>