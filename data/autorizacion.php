<?php include("conexion.php");

        $id_requi = $_POST['id_requi'];
		$tipo = $_POST['tipo'];
		
	
	  
	  
	  if ($tipo == 1){ //aprobar
		aprobar($id_requi);
	  }else if ($tipo == 0){ //denegar
		denegar($id_requi);
	  }
	  
	  
     function aprobar($id_requi){ // Funcion aprobar una solicitud de autorizacion de pedido que supero el limite de spend 
global $database_conexion, $conex;

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");
$estatus = 2;
$id_usuario_autorizo = $_SESSION['logged_user'];
	$update = "UPDATE requi_autorizacion  SET estatus='$estatus', fecha_autorizo='$fecha_actual', id_usuario_autorizo='$id_usuario_autorizo' WHERE id_requi='$id_requi'";

		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			$consulta = "SELECT * FROM requi_autorizacion WHERE id_requi = '$id_requi'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$lista_registradas = ''; 
		if ($total_rows > 0){
			
								$r_id_aplicado = $row['id_aplicado'];
								$r_tipo = $row['tipo'];
								$r_total_evaluado = $row['total_evaluado'];
								$r_id_limite = $row['id_limite'];
								$r_cantidad_limite = $row['total_limite'];
								$r_id_pedido = $row['id_pedido'];
								$r_id_usuario_req = $row['id_usuario_requiere'];
								$r_fecha_req =  $row['fecha_requirio'];
								$r_estatus = $row['estatus'];
								$r_id_usuario_autorizo = $row['id_usuario_autorizo'];
								$r_total_disponible = $row['total_disponible'];
			
			$insert_accion = "INSERT INTO historial_autorizacion	
													(id_aplicado,tipo,total_evaluado,id_limite, id_pedido,id_usuario_requiere,fecha_requirio,estatus,total_limite,fecha_autorizo,id_usuario_autorizo,total_disponible)
													VALUES 
													('$r_id_aplicado','$r_tipo','$r_total_evaluado','$r_id_limite','$r_id_pedido','$r_id_usuario_req','$r_fecha_req','$r_estatus','$r_cantidad_limite','$fecha_actual','$r_id_usuario_autorizo','$r_total_disponible')";
			if (mysql_query($insert_accion, $conex) or die(mysql_error())){
				echo "<script>
				mis_pedidos_para_aut();
				
				</script>";
				
			}
			
			validar_estatus($r_id_pedido);	
				
		}
			/* $timestamp = date("Y-m-d H:i:s");
			 */
			
			 
		}
		else 
		{
			echo 0;
		}

} 
function denegar($id_requi){ // Funcion denegar una solicitud de autorizacion de pedido que supero el limite de spend 
global $database_conexion, $conex;
$id_usuario_autorizo = $_SESSION["logged_user"];
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");
$estatus = 3;
	$update = "UPDATE requi_autorizacion  SET estatus='$estatus', fecha_autorizo='$fecha_actual', id_usuario_autorizo='$id_usuario_autorizo' WHERE id_requi='$id_requi'";

		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			$consulta = "SELECT * FROM requi_autorizacion WHERE id_requi = '$id_requi'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$lista_registradas = ''; 
		if ($total_rows > 0){
			
								$r_id_aplicado = $row['id_aplicado'];
								$r_tipo = $row['tipo'];
								$r_total_evaluado = $row['total_evaluado'];
								$r_id_limite = $row['id_limite'];
								$r_cantidad_limite = $row['total_limite'];
								$r_id_pedido = $row['id_pedido'];
								$r_id_usuario_req = $row['id_usuario_requiere'];
								$r_fecha_req =  $row['fecha_requirio'];
								$r_estatus = $row['estatus'];
								$r_id_usuario_autorizo = $row['id_usuario_autorizo'];
								$r_total_disponible = $row['total_disponible'];
			
			$insert_accion = "INSERT INTO historial_autorizacion	
													(id_aplicado,tipo,total_evaluado,id_limite, id_pedido,id_usuario_requiere,fecha_requirio,estatus,total_limite,fecha_autorizo,id_usuario_autorizo,total_disponible)
													VALUES 
													('$r_id_aplicado','$r_tipo','$r_total_evaluado','$r_id_limite','$r_id_pedido','$r_id_usuario_req','$r_fecha_req','$r_estatus','$r_cantidad_limite','$fecha_actual','$r_id_usuario_autorizo','$r_total_disponible')";
			if (mysql_query($insert_historial, $conex) or die(mysql_error())){
				echo '<script>
				mis_pedidos_para_aut();
				
				</script>';
			}
			
			validar_estatus($r_id_pedido);	
		}
			/* $timestamp = date("Y-m-d H:i:s");
			 */
				
			 
		}
		else 
		{
			echo 0;
		}

}

?>