<?php include("conexion.php");

        $id_pedido = $_POST['id_pedido'];
		$id_user = $_POST['id_user'];
	    $estatus = "";
		$btn_estatus = "";
	  
	  if ($id_pedido != ''){
			  
			pasar_pedido($id_pedido,$estatus,$id_user);
	  }
	  
	  function cant_proces_tras($id_pedido_cliente){
		  global $database_conexion, $conex;
		//$lista_arts_pendientes = array(); // catidad solicitada y surtida del pedido cliente
		$lista_arts_tras_pend = array(); // cantidad en proceso de traspaso
		
		/* $sql_pedido_lista = "SELECT * FROM pedidos_det  WHERE id_pedido='$id_pedido_cliente'";
		$consulta = mysql_query($sql_pedido_lista, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($consulta);
		$total_consulta = mysql_num_rows($consulta);
		if ($total_consulta > 0){
			while($arts_ped = mysql_fetch_array($consulta,MYSQL_BOTH)) 
            {
				if($arts_ped['cantidad'] <> $arts_ped['surtido']){
					// si no esta surtido entonces
								
					$lista_arts_pendientes[$arts_ped['id_articulo']] = $arts_ped['cantidad']."@".$arts_ped['surtido'];
					
				}
			}
		} */
		$sql = "SELECT  pd.id_articulo as id_articulo, pd.cantidad as cantidad, pd.surtido as surtido 
		FROM pedido_traspaso pt
		INNER JOIN pedido_traspaso_det pd ON pd.id_pedido = pt.id_pedido
		WHERE pt.id_pedido_cliente = '$id_pedido_cliente' AND pt.estatus = '1'"; 
		$cons = mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($cons);
		$total_cons = mysql_num_rows($cons);

		if ($total_cons > 0){
			while($row = mysql_fetch_array($cons,MYSQL_BOTH)) 
            {/// validar si esta en estatus 1 solo se requirio el traspaso 
				//generar lista consolidada de articulos traspasados y en proceso de traspaso.
				//$valor = $lista_arts_tras_pend[$row['id_articulo']]; //validar valor en lista
				if($row['cantidad'] <> $row['surtido']){
				$lista_arts_tras_pend[$row['id_articulo']] = $row['cantidad'];
				}
			}
		}
		return $lista_arts_tras_pend;
		/* foreach($lista_arts_pendientes as $id_arti => $cantidades){
			
			$array = explode("@",$cantidades);
			$cant_solicitada = $array[0];
			$cant_surtida = $array[1];
			$cant_pendiente = $cant_solicitada - $cant_surtida;
			
			$cantidad_proceso_traspaso = $lista_arts_tras_pend[$id_arti];
			
			$cantidad_pend_requi_tras = $cant_pendiente - $cantidad_proceso_traspaso;
		} */
		
		
	  }
	  
function Traspasar_lista_det($id_pedido,$id_pedido_traspaso){
global  $conex;
		
	$consulta = "SELECT pd.id_articulo as id_articulo, pd.clave_microsip as clave_microsip, pd.articulo as articulo, pd.cantidad as cantidad, pd.surtido as surtido, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, a.id_microsip as id_microsip	
				FROM pedidos_det pd
				INNER JOIN articulos a ON a.id = pd.id_articulo
				WHERE pd.id_pedido = '$id_pedido'";			
	$resultado = mysql_query($consulta, $conex) or die(mysql_error());
	$total_rows = mysql_num_rows($resultado);
	$cant_arts_proce_tras = cant_proces_tras($id_pedido);
	$id_articulo = '';
	$id_microsip = '';
	$clave_microsip='';
	$articulo = ''; 
	$cantidad = '';
	$surtido = '';
	$precio_unitario = '';
	$precio_total = '';
	$unidad_medida = '';
	
	if ($total_rows > 0)
	{ // con resultados
		while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
		{
			$cant_pend_solicitar ="";
			
			$id_articulo = $row['id_articulo'];
			$id_microsip = $row['id_microsip'];
			$clave_microsip=$row['clave_microsip'];
			$articulo = $row['articulo'];
			$cantidad = $row['cantidad'];
			$surtido = $row['surtido'];
			$precio_unitario = $row['precio_unitario'];
			$precio_total = $row['precio_total'];
			$unidad_medida = $row['unidad_medida'];
			
		if ($row['cantidad'] <> $row['surtido'])
		{
			$cant_pend_solicitar = $cantidad - $surtido;
			if (isset($cant_arts_proce_tras[$id_articulo])){
			$cant_proces_tras = $cant_arts_proce_tras[$id_articulo];
			$cant_pend_solicitar = $cant_pend_solicitar - $cant_proces_tras;
			}
			
			if (($cant_pend_solicitar != "") && ($cant_pend_solicitar > 0))
			{
			
			/// insertara las partidas del pedido del cliente al pedido NEF
			$insert_pedido_det = "INSERT INTO pedido_traspaso_det (id_articulo,id_microsip,clave_microsip,articulo,cantidad,precio_unitario,precio_total,unidad_medida,id_pedido)  VALUES ('$id_articulo','$id_microsip','$clave_microsip','$articulo','$cant_pend_solicitar','$precio_unitario','$precio_total','$unidad_medida','$id_pedido_traspaso')";
			if (mysql_query($insert_pedido_det, $conex) or die(mysql_error())){}
				
			}
		}
		}
	}
	
}	  
	  
	  
function pasar_pedido($id_pedido,$estatus,$id_user){ // 
global $database_conexion, $conex;
$id_usuario_activo = $_SESSION["logged_user"];
$accion_despues = '';
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");
	$cliente_pedido = "SELECT * FROM pedidos WHERE id='$id_pedido' ";
	$resultado_pedidocliente = mysql_query($cliente_pedido, $conex) or die(mysql_error());
	$row_ped_cliente = mysql_fetch_assoc($resultado_pedidocliente);
	$total_pedcliente = mysql_num_rows($resultado_pedidocliente);
	if ($total_pedcliente > 0){
		$usuario_req = NOMBRE($row_ped_cliente['id_usuario']);
		$almacen_id = $row_ped_cliente['id_sucursal'];
	}
	if (isset($almacen_id))
	{
		$consulta_pedido = "SELECT * FROM pedido_traspaso WHERE id_usuario= '$id_user' and estatus='0' ";
		$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
		$row_ped_traspaso = mysql_fetch_assoc($resultado_pedido);
		$total_rped = mysql_num_rows($resultado_pedido);
		if ($total_rped > 0){ // en el caso de que exista un pedido en proceso de captura
			$id_pedido_traspaso = $row_ped_traspaso['id_pedido']; // se obtiene id pedido 
			
			$consulta_pedidodet = "SELECT * FROM pedido_traspaso_det WHERE id_pedido = '$id_pedido_traspaso'";
			$resultado_pedidodet = mysql_query($consulta_pedidodet, $conex) or die(mysql_error());
			$total_rpeddet = mysql_num_rows($resultado_pedidodet);
			if ($total_rpeddet > 0){
				// actualmente tiene un pedido en proceso de captura, debe guardarlo o cancelarlo para poder procesar otro pedido.
				echo '<script> alert("Actualmente tiene una solicitud de traspaso en proceso de captura, debe guardarla o cancelarla para poder procesar otra solicitud"); </script>';
			}else {
				// update
				
				$update = "UPDATE pedido_traspaso SET requisitor='$usuario_req', almacen_id='$almacen_id', id_pedido_cliente='$id_pedido' WHERE id_pedido='$id_pedido_traspaso'";
				if (mysql_query($update, $conex) or die(mysql_error()))
				{
					Traspasar_lista_det($id_pedido,$id_pedido_traspaso);
					echo '<script> traspaso_nuevo(); </script>';
				}
			}
		}
		else 
		{ // si no existe registro de pedido en proceso lo insertamos
			$insert_pedido = "INSERT INTO pedido_traspaso (id_usuario,estatus,almacen_id,requisitor,id_pedido_cliente)
								VALUES ('$id_user','0','$almacen_id','$usuario_req','$id_pedido')";
			if (mysql_query($insert_pedido, $conex) or die(mysql_error()))
			{
				$id_pedido_traspaso =  mysql_insert_id();
				Traspasar_lista_det($id_pedido,$id_pedido_traspaso);
				echo '<script> traspaso_nuevo(); </script>';
			}
		
		}
	}
}

?>