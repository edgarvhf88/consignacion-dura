<?php include("conexion.php");

      $id_det = $_POST['id_det'];

	  
	  if ($id_det != ''){
			  
			eliminar($id_det);
	  }
     function eliminar($id_det){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;

$delete_articulo = "DELETE FROM pedidos_det WHERE id = $id_det ";
		if (mysql_query($delete_articulo, $conex) or die(mysql_error()))
		{
			echo 1;
		}
		else 
		{
			echo 0;
		}

}

?>