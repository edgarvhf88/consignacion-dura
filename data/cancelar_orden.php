
<?php include("conexion.php");
 
	$orden_id = $_POST['orden_id'];
	if (isset($_POST['orden_id']))
	{
		cancel_oc($orden_id);
		//echo '<script> alert("x '.$orden_id.'");</script>';
	}

function cancel_oc($orden_id)
{	global $conex;
	$id_usuario_cancel = $_SESSION["logged_user"];
	$fecha_hora_cancel = date("Y-m-d H:i:s");
	
	$sql = "SELECT * 
				FROM ordenes 
				WHERE id_oc = '$orden_id' ";
	
	$consulta = mysql_query($sql, $conex) or die(mysql_error());
	//$row = mysql_fetch_assoc($consulta);
	$total_rows = mysql_num_rows($consulta);
	
	if($total_rows > 0)
	{	
		
		$sql_cancelar = "UPDATE ordenes SET cancelado='S', fecha_hora_cancelacion = '$fecha_hora_cancel', id_usuario_cancelacion = '$id_usuario_cancel' WHERE id_oc = '$orden_id'";
		if(mysql_query($sql_cancelar, $conex) or die(mysql_error()))
		{
			echo '<script> console.log("Se ha cancelado la OC"); lista_ordenes_capturadas(); $("#div_datos_ordenes").hide();
			$("#div_detalle_orden").html("");
			$("#btn_add_partida").hide();
			$("#btn_adjuntar_file").hide();
			$("#div_add_art_rem").hide();
			$("#btn_guardar_oc").hide();
			$("#btn_guardar_oc_abierta").hide();
			$("#btn_cancelar_oc").hide();</script> ';
		}
	}

}