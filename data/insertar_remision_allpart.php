<?php include("conexion.php"); 
// generara un remision de una orden de compra de cliente de dura en microsip(allaprt)

function ObtenerFolioRem()
{ 
global $con_micro;
$folio = "";	
//$valor = 88878; 
$valor = 359; // folio de pedido
$sql = "SELECT A.CONSECUTIVO 	
FROM FOLIOS_VENTAS A
WHERE (A.FOLIO_VENTAS_ID = '".$valor."')";

$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['CONSECUTIVO'];
return $folio_siguiente;
}

function ObtenerIdRem($folio){ 
global $con_micro;
$tipo_docto = "R"; // PEDIDO	
$sql = "SELECT A.DOCTO_VE_ID AS DOCTO_VE_ID	
FROM DOCTOS_VE A
WHERE (A.FOLIO = '".$folio."') AND (A.TIPO_DOCTO = '".$tipo_docto."')";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$consulta){
	 exit;}	
$docto_ve_id = $row_result['DOCTO_VE_ID'];
return $docto_ve_id;
}

if ((isset($_POST['id'])) && ($_POST['id'] != "")){
	// datos de la orden
	$id_orden = $_POST['id'];
		//$fecha_actual = date("Y-m-d H:i:s");
		$fecha_actual = date("d.m.Y");
		$hora_actual = date("H:i:s");
		$fecha_hora = date("Y/m/d H:i:s");
		
	$docto_ve_id = "";	
	$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo
	$almacen_id =""; // del de dura
	$sucursal_id = Sucursal_Allpart(); // SE ASIGNA ABAJO
	$cliente_id = 4111; //cliente_id de AllPart matamoros
	$clave_cliente = "DURA";
	$vendedor_id = 3628;  //// VENDEDOR ALEJANDRO GARCIA
	$cond_pago_id = 1680; // 30 dias de credito para 0 dias = 209
	$dir_cli_id = 4113; // direccion 
	$dir_consig_id = 4113; // direccion 
	$moneda_id = 1; // MXN
	$tipo_docto = "R"; // PEDIDO
	$folio = ObtenerFolioRem(); // FOLIO CONSECUTIVO desde tabla
	$folio_cosecutivo = $folio;// FOLIO CONSECUTIVO para sumarle uno y actualizar la tabla de los folios
	$folio_RemAllpart = $folio;// FOLIO Pedido NEF para registrar en el sistema y relacionarlo
	$folio = Format9digit($folio);
	$fecha = $fecha_actual; //"28.03.2019"; // GETDATE()
	$hora = $hora_actual; // "14:05:00";
	$estatus = "P"; // PENDIENTE  /// C = CANCELADO // S = SURTIDO
	$orden_compra = ""; // ORDEN DE COMPRA ADJUNTADA - se asigna abajo
	$importe_neto = 0; // TOTAL EN PEDIDO - se asigna abajo
	$total_impuestos = "8"; // IMPUESTO MANEJADO
	$sistema_origen = "VE"; // SISTEMA_ORIGEN
	$tipo_dscto = "P"; // porcentual
	
	
	//// VERIFICAR SI EXISTEN ARTICULOS EN LA LISTA QUE NO TIENEN ID_MICROSIP -> =""
	$sql_valida_idmicrosip = "SELECT *
					FROM ordenes_det od
					INNER JOIN articulos a ON a.id = od.articulo_id
					WHERE od.id_oc = '$id_orden' AND a.id_microsip='' ";			
	$res_val = mysql_query($sql_valida_idmicrosip, $conex) or die(mysql_error());
	$rowa_val = mysql_num_rows($res_val);
	
	if ($rowa_val > 0)
	{ // si encuentra partidas del pedido sin id_microsip - entonces no permitira insertar
		
		echo '<script> $("#modal_cargando").modal("hide"); alert("Uno o varios de los articulos no estan relacionados con la lista de articulos AllPart"); </script>';
		exit;
	}
	/////////////--------------///////////////------////////////-----/////////////////////////		
	
	$consulta_pedido = "SELECT od.almacen_id as almacen_id, alm.almacen as almacen, od.total as total, od.folio as folio
						FROM ordenes od
						INNER JOIN almacenes alm ON alm.almacen_id = od.almacen_id
						WHERE od.id_oc = '$id_orden'";
	$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
	$row_p = mysql_fetch_assoc($resultado_pedido);
	$total_rows_p = mysql_num_rows($resultado_pedido);
	
	if ($total_rows_p > 0){
		// solo si encuentra el pedido entonces procede a insertarlo
		//$almacen_id = $row_p['almacen_id']; // el almacen es el de nef almacen general, id = 19
		
		$orden_compra = $row_p['folio'];
		$almacen_id = $row_p['almacen_id'];
		$importe_neto = str_replace(",","",$row_p['total']);
		$total_impuestos = $importe_neto * 0.08; 
		
		$insertar = "INSERT INTO DOCTOS_VE 
		(DOCTO_VE_ID, ALMACEN_ID, SUCURSAL_ID, CLIENTE_ID, CLAVE_CLIENTE, COND_PAGO_ID, DIR_CLI_ID, DIR_CONSIG_ID, MONEDA_ID, TIPO_DOCTO, FOLIO, FECHA, HORA, ESTATUS, ORDEN_COMPRA, IMPORTE_NETO, TOTAL_IMPUESTOS, SISTEMA_ORIGEN, TIPO_DSCTO, VENDEDOR_ID) VALUES (:docto_id,:almacen_id,:sucursal_id,:cliente_id,:clave_cliente,:cond_pago_id,:dir_cli_id,:dir_consig_id,:moneda_id,:tipo_docto,:folio,:fecha,:hora,:estatus,:orden_compra,:importe_neto,:total_impuestos,:sistema_origen,:tipo_dscto,:vendedor_id)";
		
		//echo "folio registrado de pedido = ".$folio."<br/>";
		
		try {
		$query_insert = $con_micro->prepare($insertar);
		$query_insert->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
		$query_insert->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
		$query_insert->bindParam(':sucursal_id', $sucursal_id, PDO::PARAM_INT);
		$query_insert->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
		$query_insert->bindParam(':clave_cliente', $clave_cliente, PDO::PARAM_STR, 20);
		$query_insert->bindParam(':cond_pago_id', $cond_pago_id, PDO::PARAM_INT);
		$query_insert->bindParam(':dir_cli_id', $dir_cli_id, PDO::PARAM_INT);
		$query_insert->bindParam(':dir_consig_id', $dir_consig_id, PDO::PARAM_INT);
		$query_insert->bindParam(':moneda_id', $moneda_id, PDO::PARAM_INT);
		$query_insert->bindParam(':tipo_docto', $tipo_docto, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':folio', $folio, PDO::PARAM_STR, 9);
		$query_insert->bindParam(':fecha', $fecha, PDO::PARAM_STR);
		$query_insert->bindParam(':hora', $hora, PDO::PARAM_STR);
		$query_insert->bindParam(':hora', $hora, PDO::PARAM_STR);
		$query_insert->bindParam(':estatus', $estatus, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':orden_compra', $orden_compra, PDO::PARAM_STR, 35);
		$query_insert->bindParam(':importe_neto', $importe_neto, PDO::PARAM_STR, 15);
		$query_insert->bindParam(':total_impuestos', $total_impuestos, PDO::PARAM_STR, 15);
		$query_insert->bindParam(':sistema_origen', $sistema_origen, PDO::PARAM_STR, 2);
		$query_insert->bindParam(':tipo_dscto', $tipo_dscto, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':vendedor_id', $vendedor_id, PDO::PARAM_INT);
		$query_insert->execute();
		
		} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
		}
		
		if (!$query_insert){
			echo '<script> console.log("No se pudo insertar la remision "); </script>';
			exit;
		}	
		else 
		{
			$docto_ve_id = ObtenerIdRem($folio);
			echo '<script> console.log("Remision nueva insertada '.$docto_ve_id .'"); </script>'; 
			
			
	
			//articulo
			/// inserta partidas de de la orden
			$consulta_det = "
			SELECT  pd.cantidad as cantidad, 
			pd.precio_unitario as precio_unitario, 
			pd.precio_total as precio_total, 
			pd.udm as unidad_medida, 
			art.id_microsip as id_microsip, 
			art.clave_microsip as clave_microsip
			
			FROM ordenes_det pd
			inner join articulos art on art.id = pd.articulo_id
			WHERE pd.id_oc = '$id_orden'";			
			$resultado = mysql_query($consulta_det, $conex) or die(mysql_error());
			$total_rows = mysql_num_rows($resultado);
			
			$unidades_a_surtir = "0";
			$clave_articulo = "";
			$articulo_id = "";
			$unidades = "";
			$precio_unitario = "";
			$precio_total_neto = "";
			$posicion = 0;
			if ($total_rows > 0)
			{ // con resultados
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
				{ 
					$articulo_id = $row['id_microsip'];
					$clave_articulo = $row['clave_microsip'];
					$unidades = $row['cantidad'];
					$precio_unitario = str_replace(",","",$row['precio_unitario']);
					$precio_total_neto = str_replace(",","",$row['precio_total']);
					$posicion++;
					/// insertara las partidas del de la remision en allpart
					$insertar_det = "INSERT INTO DOCTOS_VE_DET (DOCTO_VE_DET_ID, DOCTO_VE_ID, CLAVE_ARTICULO, ARTICULO_ID, UNIDADES, UNIDADES_A_SURTIR, PRECIO_UNITARIO, PRECIO_TOTAL_NETO, POSICION) VALUES (:docto_id,:docto_ve_id,:clave_articulo,:articulo_id,:unidades,:unidades_a_surtir,:precio_unitario,:precio_total_neto, :posicion)";
					
					try {
					$query_insert_det = $con_micro->prepare($insertar_det);
					$query_insert_det->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':docto_ve_id', $docto_ve_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':clave_articulo', $clave_articulo, PDO::PARAM_STR, 20);
					$query_insert_det->bindParam(':articulo_id', $articulo_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':unidades', $unidades, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':unidades_a_surtir', $unidades_a_surtir, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':precio_total_neto', $precio_total_neto, PDO::PARAM_STR, 15);
					$query_insert_det->bindParam(':posicion', $posicion, PDO::PARAM_INT);
					
					$query_insert_det->execute();
					
					
					} catch (PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					die();
					}	
					if (!$query_insert_det){
					
					//echo "No se pudo insertar pedido det";
					echo '<script> console.log("No se pudo insertar remision det"); </script>';
					exit;
					}
					else
					{
						//echo "<br/> pedido detalle insertado";
						echo '<script> console.log("Remsion detalle insertado"); </script>';
					} 
					
				} // end while
			} // con resultados de busqueda de pedido detalle
			
			 ///////////   APLICA EL DOCUMENTO DE DOCTOS_VE PARA QUE SE DESCUENTE EL INVENTARIO  ///////// 
		
			$aplicar = "EXECUTE PROCEDURE APLICA_DOCTO_VE(:V_DOCTO_VE_ID)";
		
			try {
				$query_aplicar = $con_micro->prepare($aplicar);
				$query_aplicar->bindParam(':V_DOCTO_VE_ID', $docto_ve_id, PDO::PARAM_INT);
				
				$query_aplicar->execute();
		
			} 
			catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
			if (!$query_aplicar)
			{
				echo '<script> console.log("No se Aplico la REMISION"); </script>';
				exit;
			}
			else
			{
				
				echo '<script>  $("#modal_cargando").modal("hide"); console.log("SE APLICO LA REMISION CORRECTAMENTE '.$docto_ve_id.' FOLIO: '.$folio.'"); 
					</script>'; 
			}
			
				/// actualiza folio siguiente en tabla de folios
				$folio_ventas_id = 359; 
				$folio_cosecutivo++;
				$consecutivo = Format9digit($folio_cosecutivo);
				$update_folio = "UPDATE FOLIOS_VENTAS SET CONSECUTIVO = :consecutivo WHERE FOLIO_VENTAS_ID = '".$folio_ventas_id."' ";
		
				try {
				$query_update_folio = $con_micro->prepare($update_folio);
				$query_update_folio->bindParam(':consecutivo', $consecutivo, PDO::PARAM_STR, 9);
				$query_update_folio->execute();
				} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
				}
				if (!$query_update_folio){
			
				//echo "No se actualizar folio consecutivo";
				exit;
				}
				else
				{
					//echo "<br/> se actualizo el folio a: ".$consecutivo; 
				}
				//***/**/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/**/*/*/*/
				//// actualiza folio de pedion en tabla de pedidos en sistema consigna
				$folio_remision_guardar=str_replace(",","",number_format($folio,0));
				$update_ped_folio = "UPDATE ordenes SET folio_remision='$folio_remision_guardar', estatus='2' WHERE id_oc='$id_orden'";
				if (mysql_query($update_ped_folio, $conex) or die(mysql_error()))
				{
				echo '<script> 
				$("#orden_detalle").modal("hide");
						setTimeout(function(){
							lista_ordenes_cxc();
						},1000,"JavaScript");   </script>';
				
				}
		}/// insert success
	}

}else {
	// ni no existe el dato id_pedido o es igual a nada entonces no realizara la insercion.
	
}


?>