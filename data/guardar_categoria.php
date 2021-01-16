<?php include("conexion.php");


	  if ((isset($_POST['empresa_id'])) && (isset($_POST['categoria'])) && (!isset($_POST['id_categoria']))){
				$categoria= $_POST['categoria'];
				$empresa_id= $_POST['empresa_id'];
				
	
		
				guardar($categoria,$empresa_id);
	  } 
	  if ((isset($_POST['empresa_id'])) && (isset($_POST['categoria'])) && (isset($_POST['id_categoria']))){
				$categoria= $_POST['categoria'];
				$empresa_id= $_POST['empresa_id'];
				$id_categoria = $_POST['id_categoria'];
		
				actualizar($categoria,$empresa_id,$id_categoria);
	  }
	
     function guardar($categoria,$empresa_id){ // Funcion para agregar una relacion nueva
global $database_conexion, $conex;

		$consulta = "SELECT * FROM categorias WHERE id_empresa = '$empresa_id' and categoria = '$categoria' ";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);

		if ($total_rows > 0)
		{ // si ya existe un articulo con las claves
		if ($row['categoria'] == $categoria){
			echo '<script>
			alert("Ya existe la categoria '.$row['categoria'].'");
			
			</script>';
		}
		
			
		}
		else 
		{

			$insert_categoria = "INSERT INTO categorias (id_empresa,categoria)
								VALUES ('$empresa_id','$categoria')";
			if (mysql_query($insert_categoria, $conex) or die(mysql_error()))
			{
				//$id_pedido =  mysql_insert_id();
				//echo 1;
				echo '<script>
			lista_categorias();
			
			</script>';
		
			}
		}
}
     function actualizar($categoria,$empresa_id,$id_categoria){ // Funcion para agregar una relacion nueva
global $database_conexion, $conex;

			
		$update = "UPDATE categorias SET id_empresa='$empresa_id', categoria='$categoria' WHERE id_categoria='$id_categoria'";

					if (mysql_query($update, $conex) or die(mysql_error()))
					{
						echo '<script>
				
						lista_categorias();
				
						</script>';
						
					}
		
}

?>