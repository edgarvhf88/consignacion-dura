<?php include("conexion.php");

		$id_pedido = $_POST['id_pedido'];
		$estatus = $_POST['estatus'];
if ($id_pedido != ''){
			
		act_estatus_pedido($id_pedido,$estatus);	
	  }

 function act_estatus_pedido($id_pedido, $estatus){ 
global $database_conexion, $conex;

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");
	
	$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
	$folio = folio_consecutivo($id_empresa_user_activo,"PED");
if ($estatus == 2){
	$estatus_p = '1';
	
}else if ($estatus == 3){
	$estatus_p = '0p';
}	



$update_pedido = "UPDATE pedidos SET folio='$folio', estatus='$estatus_p',  fecha_pedido_oficial='$fecha_actual' WHERE id='$id_pedido'";

		if (mysql_query($update_pedido, $conex) or die(mysql_error()))
		{	
			$folio_consecutivo = $folio + 1;
			$update_folio = "UPDATE folios SET folio='$folio_consecutivo'  WHERE id_empresa='$id_empresa_user_activo' and tipo_folio='PED'";
			if (mysql_query($update_folio, $conex) or die(mysql_error())){}
				if ($estatus_p == '1'){
					$delete_reg = "DELETE FROM requi_autorizacion WHERE id_pedido = $id_pedido ";
					if (mysql_query($delete_reg, $conex) or die(mysql_error())){}
				}
			
			echo '<script>
				//	$("#span_folio_pedido").html('.$folio.');
				//	enviar('.$id_pedido.');
				//	$("#modal_cargando").modal("hide");
				//	$("#modal_pedido").modal("show");
				//	mis_pedidos();
					alert("Pedido autorizado");
					</script>';
			
		}
		else 
		{
			echo 0;
		}
		

}

?>
