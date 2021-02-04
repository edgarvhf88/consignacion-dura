<?php include("conexion.php");

      $id_pedido = $_POST['id_pedido'];

	  
	  if ($id_pedido != ''){
			  
			cancelar($id_pedido);
	  }
function cancelar($id_pedido)
{ // elimina partidas de orden deompra en captura
	global $database_conexion, $conex;
			$sql_pos = "SELECT * FROM pedido_nef 
						WHERE id_pedido='$id_pedido'";
			$res_pos = mysql_query($sql_pos, $conex) or die(mysql_error());
			$row_pos = mysql_fetch_assoc($res_pos);
			$total_respos = mysql_num_rows($res_pos);
			if ($total_respos > 0) // 
			{
				
				$delete_partida = "DELETE FROM pedido_nef_det WHERE id_pedido = $id_pedido ";
				if (mysql_query($delete_partida, $conex) or die(mysql_error()))
				{	
					$delete_ped = "DELETE FROM pedido_nef WHERE id_pedido = $id_pedido ";
					if (mysql_query($delete_ped, $conex) or die(mysql_error())){}
					
					echo '<script> 
						//$("#modal_cargando").modal("hide");
							//pedido_nuevo();
							
							$("#txt_requisitor_pedido").val("");
							lista_pedidos();
						</script>';
				}
			}
		

}

?>