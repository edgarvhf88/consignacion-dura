
<?php include("conexion.php");

      
     
	  
	  if ((isset($_POST['id_requisitor'])) && (isset($_POST['id_vendedor']))){
			$id_requisitor = $_POST['id_requisitor'];
			$id_vendedor = $_POST['id_vendedor'];
				agregar($id_vendedor,$id_requisitor);
	  }
	  if (isset($_POST['id_relacion'])){
			$id_relacion = $_POST['id_relacion'];
				eliminar($id_relacion);
	  }
     function agregar($id_vendedor,$id_requisitor){ // Funcion para agregar una relacion nueva
global $database_conexion, $conex;

		$consulta = "SELECT * FROM relaciones WHERE id_requisitor =  '$id_requisitor' and id_vendedor =  '$id_vendedor' ";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);

		$cliente = Nombre($id_requisitor);
		$vendedor = Nombre($id_vendedor);
			if ($total_rows > 0)
			{ // si ya existe una relacion entre los ids proporcionados
				echo '<script>
				alert("'.$cliente.' ya esta se encuentra asignado a '.$vendedor.'");
				
				</script>';
			}
			else 
			{


			
					$insert_relacion = "INSERT INTO relaciones (id_requisitor,id_vendedor)
										VALUES ('$id_requisitor','$id_vendedor')";
				if (mysql_query($insert_relacion, $conex) or die(mysql_error()))
				{
					//$id_pedido =  mysql_insert_id();
					//echo 1;
					echo '<script>
				list_relaciones();
				
				</script>';
			
				}
			}
}


function eliminar($id_relacion){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;

$delete_relacion = "DELETE FROM relaciones WHERE id_relacion = $id_relacion ";
		if (mysql_query($delete_relacion, $conex) or die(mysql_error()))
		{
			echo '<script>
				list_relaciones();
				
				</script>';
			
		}
		else 
		{
			//echo 0;
		}

}
?>