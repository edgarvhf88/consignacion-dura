<?php include("conexion.php");

      
     
	  
	  if ((isset($_POST['id_articulo'])) && (isset($_POST['id_categoria'])) && (isset($_POST['id_reg_categoria']))){
			
			$id_articulo = $_POST['id_articulo'];
			$id_categoria = $_POST['id_categoria'];
			$id_reg_categoria = $_POST['id_reg_categoria'];
				agregar_eliminar($id_categoria,$id_articulo,$id_reg_categoria);
	  
	  
	  }
	/*   if (isset($_POST['id_relacion'])){
			$id_relacion = $_POST['id_relacion'];
				eliminar($id_relacion);
	  } */
     function agregar_eliminar($id_categoria,$id_articulo,$id_reg_categoria){ // Funcion para agregar una relacion nueva
global $database_conexion, $conex;

		$consulta = "SELECT * FROM registros_categorias WHERE id_categoria = '$id_categoria' and id_articulo = '$id_articulo'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);

	
			if ($total_rows > 0)
			{ // si ya existe un registro con el id_categoria y el id_articulo entonces lo borrara
			
		$delete_rel_categoria = "DELETE FROM registros_categorias WHERE id_reg_categoria = '$id_reg_categoria'";
		if (mysql_query($delete_rel_categoria, $conex) or die(mysql_error()))
		{
			echo '<script>
				lista_reg_categorias();
				
				</script>';
				echo $id_reg_categoria;
			
		}
			}
			else // de lo contrario si no existe entonces lo inserta
			{
				
			

			if(($id_categoria == 0 ) && ($id_reg_categoria != "")){
			$delete_rel_categoria = "DELETE FROM registros_categorias WHERE id_reg_categoria = '$id_reg_categoria'";
		if (mysql_query($delete_rel_categoria, $conex) or die(mysql_error()))
		{
			echo '<script>
				lista_reg_categorias();
				
				</script>';
				echo $id_reg_categoria;
			
		}	
				
			}
				
				$insert_rel_categoria = "INSERT INTO registros_categorias (id_articulo,id_categoria)
										VALUES ('$id_articulo','$id_categoria')";
				if (mysql_query($insert_rel_categoria, $conex) or die(mysql_error()))
				{
					
					echo '<script>
				lista_reg_categorias();
				</script>';
			
				}
			}
}



?>