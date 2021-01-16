<?php include("conexion.php");

        $id_pedido = $_POST['id_pedido'];
		$tipo = $_POST['tipo'];
		$id_user = $_POST['id_user'];
	    $estatus = "";
		$btn_estatus = "";
	  switch($tipo){
		  
		  case "pedidopreparado":
		  $estatus = "2";
		  break;
		  
		  case "surtir":
		  $estatus = "3";
		  break;
		
	  }
	  
	  
	  if ($id_pedido != ''){
			  
			cambiar_estatus($id_pedido,$estatus,$id_user);
	  }
	  
	  
     function cambiar_estatus($id_pedido,$estatus,$id_user){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;
$id_usuario_activo = $_SESSION["logged_user"];
$accion_despues = '';
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");

switch($estatus){
	
	case 2:
	$update = "UPDATE pedidos SET estatus='$estatus', fh_atendio='$fecha_actual', id_user_atendio='$id_user' WHERE id='$id_pedido'";
	
	break;
	case 3:
	$update = "UPDATE pedidos SET estatus='$estatus', fh_entregado='$fecha_actual', id_user_entregado='$id_user' WHERE id='$id_pedido'";
	$accion_despues = '<script>
					//enviar_correo('.$id_pedido.','.$id_usuario_activo.',4);
					Insert_remision('.$id_pedido.')
					</script>';
	break;
	
}


		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			/* $timestamp = date("Y-m-d H:i:s");
			$insert_accion = "INSERT INTO acciones (timestamp,tipo,id_reg)
					VALUES ('$timestamp','1','$id_pedido')";
			if (mysql_query($insert_accion, $conex) or die(mysql_error())){} */
			
			echo $accion_despues;	
			 
		}
		else 
		{
			echo 0;
		}

}

?>