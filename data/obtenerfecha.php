<?php include("conexion.php"); 
				  
		if (isset($_POST['tipofecha'])) {
			$tipofecha = $_POST['tipofecha'];
			$id_usuario = $_SESSION["logged_user"];
			fechas($tipofecha,$id_usuario);
	  	}
	function fechas($tipofecha,$id_usuario)
	{
		global $conex;
		$consulta = "SELECT * FROM usuarios WHERE id=$id_usuario";
		$result = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$tot_rows = mysql_num_rows($result);
		$fecha_ini = "";
		$fecha_fin = "";
		if ($tot_rows > 0)
		{
			if ($tipofecha == "ini"){
				$fecha_ini = $row['fecha_ini_reg'];
			}else if ($tipofecha == "fin"){
				$fecha_fin = $row['fecha_fin_reg'];
			}
			
			
		}
		if($fecha_ini != ""){
			echo '<script> $("#datepicker_ini").val("'.$fecha_ini .'"); </script>';
		}
		else
		{
			//echo 0;
		}
		if($fecha_fin != ""){
			echo '<script> $("#datepicker_fin").val("'.$fecha_fin .'"); </script>';
		}
		else
		{
			//echo 0;
		}
		
		
	}