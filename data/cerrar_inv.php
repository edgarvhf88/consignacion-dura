
<?php include("conexion.php");
 
	$id_inventario = $_POST['id_inventario'];
	if (isset($_POST['id_inventario']))
	{
		cerrar_inv($id_inventario);
		//echo '<script> alert("x '.$id_inventario.'");</script>';
	}

function cerrar_inv($id_inventario)
{	global $conex;
	$id_usuario_cierre = $_SESSION["logged_user"];
	$fecha_hora_cierre = date("Y-m-d H:i:s");
	
	$sql = "SELECT alm.id_empresa as id_empresa 
				FROM inventarios inv 
				INNER JOIN almacenes alm ON alm.almacen_id = inv.almacen_id
				WHERE inv.id_inventario = '$id_inventario'
				";
	
	$consulta = mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($consulta);
	$total_rows = mysql_num_rows($consulta);
	
	if($total_rows > 0)
	{	
		$id_empresa = $row['id_empresa'];
		$folio = folio_consecutivo($id_empresa,"INV");
		//$folio = '10';
		$sql_cerrar = "UPDATE inventarios SET estatus='C', folio='$folio', fecha_hora_cierre = '$fecha_hora_cierre', id_usuario_cierre = '$id_usuario_cierre' WHERE id_inventario = '$id_inventario'";
		if(mysql_query($sql_cerrar, $conex) or die(mysql_error()))
		{
			$folio_consecutivo = $folio + 1;
			$update_folio = "UPDATE folios SET folio='$folio_consecutivo'  WHERE id_empresa='$id_empresa' and tipo_folio='INV'";
			if (mysql_query($update_folio, $conex) or die(mysql_error())){}
			echo '<script> console.log("Se ha cerrado el inventario con el Folio:'.$folio.'"); alert("Se ha cerrado el inventario con el Folio:'.$folio.'"); varificar_captura(0); </script> ';
		}
	}

}