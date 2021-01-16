<?php include("conexion.php");

if (isset($_POST['id_inventario'])){
	
	$id_inventario = $_POST['id_inventario'];
	RemisionarPedido($id_inventario);
	
}
function RemisionarPedido($id_inventario){
	global $conex, $con_micro;
//// Obtener datos para la remision 
	$consulta_pedido = "SELECT * 
						FROM inventarios WHERE id_inventario = '$id_inventario' ";
		$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
		$row_pedido = mysql_fetch_assoc($resultado_pedido);
		$total_rows = mysql_num_rows($resultado_pedido);

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("d.m.Y");
$hora_actual = date("H:i:s");
$fecha_hora = date("Y/m/d H:i:s");
	
if ($total_rows > 0){
//// inicia bucle con articulos de pedido  INSERTA ARTICULOS EN DOCTOS_VE_DET
	$consulta_articulos = "SELECT a.clave_microsip as clave, indet.diferecia as cantidad,  a.precio as precio_unitario, a.id_microsip as id_microsip  
							FROM inventarios_det indet 
							INNER JOIN articulos a on a.id = indet.id_articulo
							WHERE indet.id_pedido = '$id_pedido' ";
	$resultado_articulos = mysql_query($consulta_articulos, $conex) or die(mysql_error());
	$total_rows = mysql_num_rows($resultado_articulos);
	$lista_invdet = array();
	$suma_totales = 0;
	while($row_arti = mysql_fetch_array($resultado_articulos,MYSQL_BOTH)) 
	{
		$precio_total = $row_arti['precio_unitario'] * $row_arti['cantidad'];
		$lista_retiros[] = array("value" => $row_arti['clave'];, 
							   "clave" => $row_arti['clave'], 
							   "id_microsip" => $row_arti['id_microsip'], 
							   "cantidad" => $row_arti['cantidad'], 
							   "precio_unitario" => $row_arti['precio_unitario'], 
							   "precio_total" => $precio_total);
	
		$suma_totales += $precio_total;
	}
	$total_total = $suma_totales;
///////////   SE INSERTA EL DOCUMENTO REMISION EN MICROSIP    /////////
$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo

$almacen_id = $row_pedido['almacen_id']; // dura P3 = 11142	dura P4 = 11143	
$cliente_id = 4111; //id de DURA
$clave_cliente = "DURA";
$vendedor_id = 3628;  //// id de ALEJANDRO GARCIA 
$cond_pago_id = 4191; // 30 dias de credito para 0 dias = 209
$dir_cli_id = 4113; // direccion 
$dir_consig_id = 4113; // direccion
$moneda_id = 1; // MXN
$tipo_docto = "R";
$folio = ObtenerFolio();
$folio_cosecutivo = $folio;
$estatus = "P";
$total_impuestos = $total_total * .08;
$sistema_origen = "VE";
$tipo_dscto = "P";
$folio = Format9digit($folio);

$fecha = $fecha_actual; //"04.11.2019"; // GETDATE()
$hora =  $hora_actual; //"14:00:00";

$orden_compra = $row_pedido['orden_compra'];
$importe_neto = $total_total;

$insertar = "INSERT INTO DOCTOS_VE 
(DOCTO_VE_ID, ALMACEN_ID, CLIENTE_ID, CLAVE_CLIENTE, COND_PAGO_ID, DIR_CLI_ID, DIR_CONSIG_ID, MONEDA_ID, TIPO_DOCTO, FOLIO, FECHA, HORA, ESTATUS, ORDEN_COMPRA, IMPORTE_NETO, TOTAL_IMPUESTOS, SISTEMA_ORIGEN, TIPO_DSCTO) VALUES (:docto_id,:almacen_id,:cliente_id,:clave_cliente,:cond_pago_id,:dir_cli_id,:dir_consig_id,:moneda_id,:tipo_docto,:folio,:fecha,:hora,:estatus,:orden_compra,:importe_neto,:total_impuestos,:sistema_origen,:tipo_dscto)";

try {
	$query_insert = $con_micro->prepare($insertar);
	$query_insert->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
	$query_insert->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
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
	$query_insert->bindParam(':estatus', $estatus, PDO::PARAM_STR, 1);
	$query_insert->bindParam(':orden_compra', $orden_compra, PDO::PARAM_STR, 35);
	$query_insert->bindParam(':importe_neto', $importe_neto, PDO::PARAM_STR, 15);
	$query_insert->bindParam(':total_impuestos', $total_impuestos, PDO::PARAM_STR, 15);
	$query_insert->bindParam(':sistema_origen', $sistema_origen, PDO::PARAM_STR, 2);
	$query_insert->bindParam(':tipo_dscto', $tipo_dscto, PDO::PARAM_STR, 1);
	$query_insert->execute();
	
	$docto_ve_id = ObtenerId($folio); 
 
} 
catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }

if (!$query_insert)
{ 	
echo '<script> console.log("No se inserto el pedido"); </script>';
	exit;
}	
 else
{  //// AL TENER EXITO LA INSERCION DEL DOCUMENTO REMISION ENTONCES SE APLICA LAS SIGUIENTES INSERCIONES 
	echo '<script> console.log("Se inserto el pedido"); </script>';
	///// TAMBIEN SE TIENE QUE INSERTAR LOS DATOS ADICIONALES 
	
	$insertar_adicionales = "INSERT INTO LIBRES_REM_VE 
	(DOCTO_VE_ID,FECHAHORA,FOLIO,FECHA,ALMACENID,CLIENTEID,VENDEDORID,CLAVECLIENTE,ESTATUS) VALUES 
	(:DOCTO_VE_ID,:FECHAHORA,:FOLIO,:FECHA,:ALMACENID,:CLIENTEID,:VENDEDORID,:CLAVECLIENTE,:ESTATUS)";
	
	try {
		$query_insert_add = $con_micro->prepare($insertar_adicionales);
		$query_insert_add->bindParam(':DOCTO_VE_ID', $docto_ve_id, PDO::PARAM_INT);
		$query_insert_add->bindParam(':FECHAHORA', $fecha_hora, PDO::PARAM_STR, 30);
		$query_insert_add->bindParam(':FOLIO', $folio, PDO::PARAM_STR, 10);
		$query_insert_add->bindParam(':FECHA', $fecha_actual, PDO::PARAM_STR);
		$query_insert_add->bindParam(':ALMACENID', $almacen_id, PDO::PARAM_STR, 10);
		$query_insert_add->bindParam(':CLIENTEID', $cliente_id, PDO::PARAM_STR, 10);
		$query_insert_add->bindParam(':VENDEDORID', $vendedor_id, PDO::PARAM_STR, 10);
		$query_insert_add->bindParam(':CLAVECLIENTE', $clave_cliente, PDO::PARAM_STR, 20);
		$query_insert_add->bindParam(':ESTATUS', $estatus, PDO::PARAM_STR, 1);
		$query_insert_add->execute();
		
	} 
	catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
	
	if (!$query_insert_add)
	{ 	
	echo '<script> console.log("No se insertaron datos adicionales"); </script>';
		exit;
	}else{
		echo '<script> console.log("Se insertaron datos adicionales"); </script>';
		
	}
	
	
	$posicion = 1;
	foreach($lista_invdet as $row_articulos) 
	{
		
		
		$clave_articulo = $row_articulos['clave'];
		$articulo_id = $row_articulos['id_microsip'];
		$unidades = $row_articulos['cantidad'];
		$unidades_a_surtir = $row_articulos['cantidad'];
		$precio_unitario = $row_articulos['precio_unitario'];
		$precio_total_neto = $row_articulos['precio_total'];
		
			
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
		} 
		catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }	
		if (!$query_insert_det){
		
			echo '<script> console.log("No se inserto el articulo"); </script>';
			exit;
		}
		else
		{
				echo '<script> console.log("Se inserto el articulo"); </script>';
		}
		$posicion++;
	}
	//// termina bucle
	////// ///////     INSERTA EL SIGUIENTE NUMERO DE FOLIO DEL DOCUEMENTO EN ESTE CASO REMISION  ///////
	$folio_ventas_id = 43312; // folio remisiones
	$folio_cosecutivo++;
	$consecutivo = Format9digit($folio_cosecutivo);
	$update_folio = "UPDATE FOLIOS_VENTAS SET CONSECUTIVO = :consecutivo WHERE FOLIO_VENTAS_ID = '".$folio_ventas_id."' ";
 
	try {
		$query_update_folio = $con_micro->prepare($update_folio);
		$query_update_folio->bindParam(':consecutivo', $consecutivo, PDO::PARAM_STR, 9);
		
		$query_update_folio->execute();

	} 
	catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
	if (!$query_update_folio)
	{
			echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
		exit;
	}
	 else
	{
		
			echo '<script> console.log("Se actualizo el folio a: '.$consecutivo.'"); </script>';
	}
	
	 ///////////   APLICA EL DOCUMENTO REMISION PARA QUE SE DESCUENTE EL INVENTARIO  ///////// 
	$integracion = 'S';
	$costeo = 'C';
	$aplicar = "EXECUTE PROCEDURE APLICA_DOCTO_VE(:V_DOCTO_VE_ID)";
 
	try {
		$query_aplicar = $con_micro->prepare($aplicar);
		$query_aplicar->bindParam(':V_DOCTO_VE_ID', $docto_ve_id, PDO::PARAM_INT);
		
		$query_aplicar->execute();

	} 
	catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
	if (!$query_aplicar)
	{
		echo '<script> console.log("No se Aplico la remision"); </script>';
		exit;
	}
	 else
	{
		echo '<script> console.log("SE APLICO LA REMISION CORRECTAMENTE"); 
		      SincronizarInventario('.$id_pedido.')</script>';
	}
  
} /// si se inserta el pedido en microsip

} /// validacion de totalrows
}  //funcion
?>