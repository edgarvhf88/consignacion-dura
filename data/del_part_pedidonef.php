<?php include("conexion.php");

      $id_det = $_POST['id_det'];

	  
	  if ($id_det != ''){
			  
			eliminar($id_det);
	  }
function eliminar($id_det)
{ // elimina partidas de orden deompra en captura
	global $database_conexion, $conex;
			$sql_pos = "SELECT * FROM pedido_nef_det 
						WHERE id='$id_det'";
			$res_pos = mysql_query($sql_pos, $conex) or die(mysql_error());
			$row_pos = mysql_fetch_assoc($res_pos);
			$total_respos = mysql_num_rows($res_pos);
			if ($total_respos > 0) // 
			{
				//$orden_id = $row_pos['id_oc'];
				$delete_partida = "DELETE FROM pedido_nef_det WHERE id = $id_det ";
				if (mysql_query($delete_partida, $conex) or die(mysql_error()))
				{	
					//$posicion = Posicion($orden_id); //esta funcion actualiza la secuencia de las posiciones y devuelve la posicion siguiente, es este caso no se utiliza.
					echo '<script> 
							cargar_lista_pedido_nef();
							$("#modal_cargando").modal("hide");
						</script>';
				}
			}
		

}

?>