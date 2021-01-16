<?php include("conexion.php");
 
      $id_inventario = $_POST['id_inventario'];
      $tipo = $_POST['tipo'];
	if (isset($_POST['id_inventario']))
	{
		//echo '<script> console.log("accionando cancelar '.$id_inventario.'");</script>';
		cancelar_inv($id_inventario, $tipo);
	}

function cancelar_inv($id_inventario, $tipo)
{	global $conex;

	//$sql = "SELECT * FROM inventarios_det WHERE id_inventario = '$id_inventario'";
	$sql = "SELECT * FROM inventarios WHERE id_inventario = '$id_inventario'";
	
	$consulta = mysql_query($sql, $conex) or die(mysql_error());
	//$row = mysql_fetch_assoc($consulta);
	$total_rows = mysql_num_rows($consulta);
	$lista_det_del = array();
	//$texto = "";
	if($total_rows > 0)
	{	
		$id_usuario_cancelacion = $_SESSION['logged_user'];
		$fecha_hora_cancelacion = date("Y-m-d H:i:s");
		$sql_cancelar = "UPDATE inventarios SET cancelado='S', fecha_hora_cancelacion = '$fecha_hora_cancelacion', id_usuario_cancelacion = '$id_usuario_cancelacion' WHERE id_inventario = '$id_inventario'";
	
		if(mysql_query($sql_cancelar, $conex) or die(mysql_error()))
		{
			if ($tipo == 0){
				echo '<script> console.log("Se ha cancelado Inventario (Abierto)"); varificar_captura(0);</script>';
			}else if ($tipo == 1) {
				echo '<script> console.log("Se ha cancelado Inventario Con Folio (Cerrado)"); $("#modal_listadet").modal("hide"); lista_inventarios_reg(11);</script>';
			}
			
		}
		
		/* while($rows = mysql_fetch_array($consulta,MYSQL_BOTH))
		{
			$lista_det_del[$rows['id_inventario_det']] = $rows['id_articulo'];
			//echo '<script> console.log("lista_det_del '.$rows['id_inventario_det'].'"); </script>';
		} 
	
		 if (count($lista_det_del) > 0)
		{
			foreach($lista_det_del as $id_inventario_det => $articulo)
			{
				 $delete_conteo = "DELETE FROM 
								inventarios_det_conteos 
								WHERE id_inventario_det = '$id_inventario_det'"; 
				if (mysql_query($delete_conteo, $conex) or die(mysql_error()))
				{
					echo '<script> console.log("conteo eliminado"); </script>';
				}
				//echo '<script> console.log("conteo eliminado '.$id_inventario_det.'"); </script>';
			}
			
		} 
		 $delete_inv_det = "DELETE FROM inventarios_det WHERE id_inventario = '$id_inventario'";
		if (mysql_query($delete_inv_det, $conex) or die(mysql_error())){
			echo '<script> console.log("inv_det eliminado"); </script>';
		} */
		//echo '<script> console.log("inv_det eliminado"); </script>';
	}
	// elimina captura inventario
	/* $delete_inv = "DELETE FROM	inventarios WHERE id_inventario = '$id_inventario'";
	if (mysql_query($delete_inv, $conex) or die(mysql_error())){
		echo '<script> console.log("inventario eliminado"); varificar_captura(0); </script>';
	} */
}