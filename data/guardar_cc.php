<?php include("conexion.php");


	  if ((isset($_POST['empresa_id'])) && (isset($_POST['centro_costos'])) && (!isset($_POST['id_cc']))){
				$centro_costos= $_POST['centro_costos'];
				$empresa_id= $_POST['empresa_id'];
				
	
		
				guardar($centro_costos,$empresa_id);
	  } 
	  if ((isset($_POST['empresa_id'])) && (isset($_POST['centro_costos'])) && (isset($_POST['id_cc']))){
				$centro_costos= $_POST['centro_costos'];
				$empresa_id= $_POST['empresa_id'];
				$id_cc = $_POST['id_cc'];
		
				actualizar($centro_costos,$empresa_id,$id_cc);
	  }
	
     function guardar($centro_costos,$empresa_id){ // Funcion para agregar una relacion nueva
global $database_conexion, $conex;

		$consulta = "SELECT * FROM centro_costos WHERE id_empresa = '$empresa_id' and nombre_cc = '$centro_costos' ";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);

		if ($total_rows > 0)
		{ // si ya existe un centro de costos
		if ($row['nombre_cc'] == $centro_costos){
			echo '<script>
			alert("Ya existe un centro de costos con el nombre: '.$row['nombre_cc'].'");
			
			</script>';
		}
		
			
		}
		else 
		{
			///// registra el nuevo centro de costos
			$insert_cc = "INSERT INTO centro_costos (id_empresa,nombre_cc)
								VALUES ('$empresa_id','$centro_costos')";
			if (mysql_query($insert_cc, $conex) or die(mysql_error()))
			{
				//$id_cc =  mysql_insert_id();
				//echo 1;
				echo '<script>
			mostrar_cc();
			
			</script>';
		
			}
		}
}
     function actualizar($centro_costos,$empresa_id,$id_cc){ // Funcion actualizar el centro de costos
global $database_conexion, $conex;

			
		$update = "UPDATE centro_costos SET id_empresa='$empresa_id', nombre_cc='$centro_costos' WHERE id_cc='$id_cc'";

					if (mysql_query($update, $conex) or die(mysql_error()))
					{
						echo '<script>
				
						mostrar_cc();
				
						</script>';
						
					}
		
}

?>