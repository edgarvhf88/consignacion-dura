<?php include("conexion.php");

///////////   SE INSERTA EL DOCUMENTO REMISION EN MICROSIP    /////////
$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo
$almacen_id = 390226; // almacen starkey
$cliente_id = 908; //id de STARKEY
$clave_cliente = "STAR0";
$cond_pago_id = 219; // 30 dias de credito para 0 dias = 209
$dir_cli_id = 909; // direccion de publico en general
$dir_consig_id = 909; // direccion de publico en general
$moneda_id = 1; // MXN
$tipo_docto = "R";
$folio = ObtenerFolio();
$folio_cosecutivo = $folio;
$fecha = "31.10.2019"; // GETDATE()
$hora = "14:05:00";
$estatus = "P";
$orden_compra = "123456";
$importe_neto = "100";
$total_impuestos = "8";
$sistema_origen = "VE";
$tipo_dscto = "P";
$folio = Format9digit($folio);

$insertar = "INSERT INTO DOCTOS_VE 
(DOCTO_VE_ID, ALMACEN_ID, CLIENTE_ID, CLAVE_CLIENTE, COND_PAGO_ID, DIR_CLI_ID, DIR_CONSIG_ID, MONEDA_ID, TIPO_DOCTO, FOLIO, FECHA, HORA, ESTATUS, ORDEN_COMPRA, IMPORTE_NETO, TOTAL_IMPUESTOS, SISTEMA_ORIGEN, TIPO_DSCTO) VALUES (:docto_id,:almacen_id,:cliente_id,:clave_cliente,:cond_pago_id,:dir_cli_id,:dir_consig_id,:moneda_id,:tipo_docto,:folio,:fecha,:hora,:estatus,:orden_compra,:importe_neto,:total_impuestos,:sistema_origen,:tipo_dscto)";

echo "folio registrado de pedido = ".$folio."<br/>";

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
	$query_insert->bindParam(':hora', $hora, PDO::PARAM_STR);
	$query_insert->bindParam(':estatus', $estatus, PDO::PARAM_STR, 1);
	$query_insert->bindParam(':orden_compra', $orden_compra, PDO::PARAM_STR, 35);
	$query_insert->bindParam(':importe_neto', $importe_neto, PDO::PARAM_STR, 15);
	$query_insert->bindParam(':total_impuestos', $total_impuestos, PDO::PARAM_STR, 15);
	$query_insert->bindParam(':sistema_origen', $sistema_origen, PDO::PARAM_STR, 2);
	$query_insert->bindParam(':tipo_dscto', $tipo_dscto, PDO::PARAM_STR, 1);
	$query_insert->execute();
	
	$docto_ve_id = ObtenerId($folio); 
 
} catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }

if (!$query_insert)
{ 	
	echo "No se pudo insertar pedido";
	exit;
}	
 else
{  //// AL TENER EXITO LA INSERCION DEL DOCUMENTO REMISION ENTONCES SE APLICA LAS SIGUIENTES INSERCIONES 
	echo " pedido nuevo insertado <br/>"; 
	//// inicia bucle con articulos de pedido  INSERTA ARTICULOS EN DOCTOS_VE_DET 
	$clave_articulo = "BICAN20L";
	$articulo_id = "283781";
	$unidades = "10";
	$unidades_a_surtir = "10";
	$precio_unitario = "47.4";
	$precio_total_neto = "47.4";
	$posicion = "1";
		
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
	 
		echo "No se pudo insertar pedido det";
		exit;
	}
	 else
	{
		echo "<br/> pedido detalle insertado";
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
		echo "No se actualizar folio consecutivo";
		exit;
	}
	 else
	{
		echo "<br/> se actualizo el folio a: ".$consecutivo; 
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
		echo "No se aplico la remision";
		exit;
	}
	 else
	{
		echo "<br/> se aplico la remision "; 
	}
  
}
?>