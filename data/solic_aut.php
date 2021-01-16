<?php include("conexion.php");

      $id_pedido = $_POST['id_pedido'];
      $id_user_aut = $_POST['id_usuario_aut']; // select con id de usuario al que se le pide la autorizacion
      $id_cc = $_POST['id_cc']; 
      $id_recolector = $_POST['id_recolector']; 
      $orden_cliente = $_POST['orden_cliente']; 
		
	  
	  if ($id_pedido != '' && $id_user_aut != '' ){
			  
			solic_aut($id_pedido,$id_user_aut,$id_cc,$id_recolector,$orden_cliente);
	  } else {
		  echo '<script> 
					$(document).ready(function(){	
					    alert("Debe seleccionar un usuario para que autorize el pedido");
						$("#modal_cargando").modal("hide");
					});
			</script>';
	  }
     function solic_aut($id_pedido,$id_user_aut,$id_cc,$id_recolector,$orden_cliente){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;
$id_usuario_activo = $_SESSION["logged_user"];
//$delete_pedido = "DELETE FROM pedidos WHERE id = $id_pedido ";

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");

$total_pedido = "";
$consulta_lista = "SELECT SUM(precio_total) as Total FROM pedidos_det WHERE id_pedido = '$id_pedido'";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_lista);
$total_rows2 = mysql_num_rows($resultado_lista);

if ($total_rows2 > 0){
	$total_pedido = $row['Total'];
}

/// validar estatus de requi, si ya se encuentra autorizada que el estatus no se cambie, se debe quedar autorizada.
$estatus = 1;
$estatus_aprobado = 2;
$estatus_pedido = 4;
$update_pedido = "UPDATE pedidos SET estatus = '$estatus_pedido', total_pedido='$total_pedido', id_cc='$id_cc', id_recolector='$id_recolector', orden_compra='$orden_cliente', fecha_pedido_oficial='$fecha_actual' WHERE id='$id_pedido'";	
$update_requi_auto = "UPDATE requi_autorizacion SET estatus='$estatus', id_usuario_autorizo='$id_user_aut', fecha_requirio='$fecha_actual'  WHERE id_pedido='$id_pedido' and estatus <> '$estatus_aprobado'";
	if (mysql_query($update_pedido, $conex) or die(mysql_error()))
		{
			if (mysql_query($update_requi_auto, $conex) or die(mysql_error()))
				{
			//echo 1;
				}
		}
	echo '<script> 
					$(document).ready(function(){	
						$("#modal_cargando").modal("hide");
						mis_pedidos_pend_aut();
						enviar_correo('.$id_pedido.','.$id_usuario_activo.',1);
					});
			</script>';
}

?>