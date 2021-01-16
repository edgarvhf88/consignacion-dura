<?php include("conexion.php");

$folio_siguiente = "";	
$valor = 36; // ID DE CONCEPTO TRASPASO SALIDA
$sql = "SELECT DI.SIG_FOLIO AS SIG_FOLIO	
FROM CONCEPTOS_IN DI
WHERE (DI.CONCEPTO_IN_ID = '".$valor."')";

$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['SIG_FOLIO']-1;
$folio_siguiente = Format9digit($folio_siguiente);
	echo "Folio: ".$folio_siguiente." ID: ".ObtenerIdTraspaso($folio_siguiente)."<br />";
		 
	//echo ObtenerIdTraspasoDet(2437,322565,25);
	print_r( VerifTraspasoStatus(8));
	// codigo para recalculo de saldos de los articulos 
	$articulo_id = 6230;
	/* $del_saldo_in = "DELETE FROM SALDOS_IN WHERE ARTICULO_ID ='".$articulo_id."'";
 				try {
					$query_del = $con_micro->prepare($del_saldo_in);
					$query_del->execute();
				} 
				catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
				if (!$query_del)
				{
					echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
					exit;
				}
				else
				{
					echo '<script> console.log("SE ELIMINO SALDOS_IN DE ARTICULO_ID"); </script>';
				}
	$update_exival = "EXECUTE PROCEDURE RECALC_SALDOS_ART_IN('".$articulo_id."')";
 				try {
					$query_exival = $con_micro->prepare($update_exival);
					$query_exival->execute();
				} 
				catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
				if (!$query_exival)
				{
					echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
					exit;
				}
				else
				{
					echo '<script> console.log("SE RECALCULO LA EXISTENCIA"); </script>';
				}	 */
				
				
?>