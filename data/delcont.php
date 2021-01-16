<?php include("conexion.php");
 
	$id_conteo = $_POST['id_conteo'];
	if (isset($_POST['id_conteo']))
	{
		delconteo($id_conteo);
	}

function delconteo($id_conteo)
{	global $conex;
 $id_usuario_activo = $_SESSION["logged_user"];
	$sql = "SELECT idet.id_usuario_creador as id_usuario_creador, idet.id_inventario_det as id_inventario_det, idet.id_articulo as id_articulo, idet.existencia_actual as existencia_actual
			FROM inventarios_det_conteos idc
			INNER JOIN inventarios_det idet ON idet.id_inventario_det = idc.id_inventario_det
			WHERE idc.id_conteo = '$id_conteo'";
	
	$consulta = mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($consulta);
	$total_rows = mysql_num_rows($consulta);
	
	if($total_rows > 0)
	{
		$id_creador = $row['id_usuario_creador'];
		$id_articulo = $row['id_articulo'];
		$id_inventario_det = $row['id_inventario_det'];
		$existencia_actual = $row['existencia_actual'];
		
		if ($id_creador == $id_usuario_activo)
		{
			$delete_conteo = "DELETE FROM 
								inventarios_det_conteos 
								WHERE id_conteo = '$id_conteo'"; 
			if (mysql_query($delete_conteo, $conex) or die(mysql_error()))
			{
				$cantidad_contada_total = suma_conteos($id_inventario_det);
			
				$diferencia_total = $existencia_actual - $cantidad_contada_total;
				
				if ($cantidad_contada_total == 0)
				{
					$delete_inv_det = "DELETE FROM inventarios_det WHERE id_inventario_det = '$id_inventario_det'";
					if (mysql_query($delete_inv_det, $conex) or die(mysql_error())){
						echo '<script> console.log("inv_det eliminado"); </script>';
					}
					$cantidad_contada_total = '-';
				}
				else
				{	
					// update inventarios_det actualizar  cantidad_contada
							$update = "UPDATE inventarios_det 
							SET cantidad_contada='$cantidad_contada_total',
							diferencia='$diferencia_total'
							WHERE id_inventario_det='$id_inventario_det'";
		
							if (mysql_query($update, $conex) or die(mysql_error()))
							{
								//echo '<script> $("#ventana1").modal("show"); </script>';
								echo '<script> console.log("se actualizo inventario_det "); </script>';
							}
				}
				echo '<script> console.log("conteo eliminado"); validar_articulo_inventario('.$id_articulo.'); $("#tdunidadescontadas_'.$id_articulo.'").html("'.$cantidad_contada_total.'");</script>';
			}
		} 
		else 
		{
			echo '<script> alert("Solo el usuario que capturo el conteo puede eliminarlo '.$id_creador.'  = '.$id_usuario_activo.'"); </script>';
		}			
		
	
	}
	
}