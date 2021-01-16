<?php include("conexion.php");

      $id = $_POST['id'];
      $cantidad = $_POST['cantidad'];
      $precio_total = $_POST['precio_total'];

	  
	  if ($id != ''){
			  
			actualizar($id,$cantidad,$precio_total);
	  }
     function actualizar($id,$cantidad,$precio_total){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;
//sleep(1);


$update = "UPDATE pedidos_det SET cantidad='$cantidad', precio_total='$precio_total' WHERE id='$id'";

		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			// actualizar total de pedido en pantalla
			$consulta_id_pedido = "SELECT * FROM pedidos_det WHERE id='$id' ";
			$resultado_id_pedido = mysql_query($consulta_id_pedido, $conex) or die(mysql_error());
			$row_id_pedido = mysql_fetch_assoc($resultado_id_pedido);
			$total_rows_id_pedido = mysql_num_rows($resultado_id_pedido);
			if ($total_rows_id_pedido > 0){
			
				$id_pedido = $row_id_pedido['id_pedido'];
				$consulta_total_pedido = "SELECT * FROM pedidos_det WHERE id_pedido = $id_pedido ";
				$resultado_total_pedido = mysql_query($consulta_total_pedido, $conex) or die(mysql_error());
				$total_rows2 = mysql_num_rows($resultado_total_pedido);
				if ($total_rows2 > 0){
					$total_pedido = 0;
					while($row = mysql_fetch_array($resultado_total_pedido,MYSQL_BOTH)) 
                    {
						$total_pedido += $row['precio_total'];
					}
					
					echo '<script>  $("#div_total_pedido").html("Total = $'.number_format($total_pedido,2,".","").'"); </script>';
				}
			}
		}
		else 
		{
			echo 0;
		}

}

?>