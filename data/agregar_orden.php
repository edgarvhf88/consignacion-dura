<?php include("conexion.php");

      $id_pedido = $_POST['id_pedido'];
      $orden = $_POST['orden'];

	  
	  if ($orden != ''){
			  
			actualizar($id_pedido,$orden);
	  }
     function actualizar($id_pedido,$orden){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;
//sleep(1);

$estatus = '1';
$update = "UPDATE pedidos SET orden_compra='$orden' WHERE id='$id_pedido'";

		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			echo '<script>
	
			mis_pedidos();
	
	</script>';
			
		}
		else 
		{
			echo 0;
		}

}

?>