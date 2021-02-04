<?php
include("conexion.php"); 

$folio = "000217734";

function ObtenerIdPedi($folio){ 
global $con_micro_nef;
$tipo_docto = "P"; // PEDIDO	
$sql = "SELECT A.DOCTO_VE_ID AS DOCTO_VE_ID	
FROM DOCTOS_VE A
WHERE (A.FOLIO = '".$folio."') AND (A.TIPO_DOCTO = '".$tipo_docto."')";
$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$consulta){
	 exit;}	
$docto_ve_id = $row_result['DOCTO_VE_ID'];
return $docto_ve_id;
}

// echo ObtenerIdPedi($folio);
 echo CantRecibir(335,4);


?>