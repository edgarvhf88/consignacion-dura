<?php include("conexion.php");

      $id_pedido = $_POST['id_pedido'];

	  
	  if ($id_pedido != ''){
			  
			eliminar($id_pedido);
	  }
     function eliminar($id_pedido){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;

$delete_pedido = "DELETE FROM pedidos WHERE id = $id_pedido ";
$delete_articulos = "DELETE FROM pedidos_det WHERE id_pedido = $id_pedido ";
		if (mysql_query($delete_pedido, $conex) or die(mysql_error()))
		{
			if (mysql_query($delete_articulos, $conex) or die(mysql_error()))
		{
			//echo 1;
		}
		}

}

?>