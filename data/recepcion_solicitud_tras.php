<?php include("../data/conexion.php"); 


function cargar_recepcion($folio, $oc, $id_pedido)
{
	$folio_bus=$folio; //VIENE COMPLETO PARA EVITAR EL RAP >:V

	//con el folio proporcionado cargo la remision y la muestro en una tabla
		$tras_folio = rec_sin_tras ($folio , $id_pedido);
		
		if ($oc !=""){
		if ($tras_folio != "" )
		{
			$boton_recepcionar= '<h4 align"center">Solicitud de traspaso: '.$tras_folio.'</h4>';
		}
		else {
			$boton_recepcionar = '<div class="col-lg-12" align="center">
							 <button id="gen_tras_micro" onclick="solicitar_tras_de_rec('.$folio.', '.$id_pedido.');" class="btn btn-primary" >Generar solicitud de traspaso </button>
							 </div>';
		}
		}
		else {$boton_recepcionar="";}
		
		 //selecciono la base de datos
		global $con_micro;
		 //hago la consulta
		$aplicar = "SELECT
        DVD.CLAVE_ARTICULO AS CLAVE,
        ART.nombre AS NOMBRE,
        DVD.unidades as CANTIDAD,
        DVD.precio_unitario as PRECIO,
        DVD.precio_total_neto AS TOTAL
        FROM DOCTOS_CM_DET DVD
        INNER JOIN ARTICULOS ART ON ART.ARTICULO_ID = DVD.ARTICULO_ID
        INNER JOIN DOCTOS_CM DV  ON DV.DOCTO_CM_ID = DVD.DOCTO_CM_ID
        WHERE DV.FOLIO = '$folio_bus' AND DV.tipo_docto='R'";
		//empiezo la consulta
		$query_aplicar = $con_micro->prepare($aplicar);
		$query_aplicar->execute();
		$results = $query_aplicar->fetchAll(PDO::FETCH_ASSOC);
		//recorro el array y concateno la variables
		$rems="";
		$tabla=' 
		
				<h4 align"center">Folio: '.$folio.'</h4>
		
		<table id="remision_det" class="table table-striped table-bordered table-hover table-responsive">
                    	<thead>
                            <tr class="info">
                                
                                <th>Clave Microsip</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                                
                            </tr>
                        </thead><tbody>';
		foreach($results as $row)
		{	//*******************************************************************

         $tabla .=' <tr>
             <td>'.$row['CLAVE'].'</td>
             <td>'.$row['NOMBRE'].'</td>
             <td>'.$row['CANTIDAD'].'</td>
             <td align="right">$'.number_format($row['PRECIO'],2).' </td>
             <td align="right">$'.number_format($row['TOTAL'],2).'</td>
         </tr>';
      
			//*******************************************************************
		}
	
	$tabla .=' </table>
                <script>
                $(document).ready( function () {
                    $("#remision_det").DataTable();
                } );
                </script>';
	$tabla .=$boton_recepcionar;			
				echo $tabla;
	
	
}


function rec_sin_tras($folio, $id_pedido)
{
	global $database_conexion, $conex;
	$sql = "SELECT id_pedido_traspaso	
	FROM ligas_doctos 
	WHERE (id_pedido = '".$id_pedido."' AND recepcion_allpart = '".$folio."')";
	
	$resultado= mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($resultado);
	$total = mysql_num_rows($resultado);
	if ($total > 0)
		{
			$folio_tras = $row['id_pedido_traspaso'];	
		}
	else {$folio_tras="";}
	
	return $folio_tras;
}

$tipo = $_POST['tipo'];

if ($tipo==1)
{//carga la remision a recepcionar 
	$oc = $_POST['oc'];
	$folio = $_POST['folio'];
	$id_pedido = $_POST['id_pedido'];
	cargar_recepcion($folio, $oc, $id_pedido);
}






















   
   
?>
