<?php include("conexion.php");

      $id_pedido = $_POST['id_pedido'];
      $tracking = $_POST['tracking'];

	  
	  if ($tracking != ''){
			  
			actualizar($id_pedido,$tracking);
	  }
     function actualizar($id_pedido,$tracking){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;

$update = "UPDATE pedidos SET tracking='$tracking' WHERE id='$id_pedido'";

		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			echo '<script>
				
				enviar("'.$id_pedido.'");
				
				lista_pedidos()
	
				</script>';
			
		}
		else 
		{
			echo 0;
		}

}

?>