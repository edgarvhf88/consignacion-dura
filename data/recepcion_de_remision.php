<?php include("../data/conexion.php"); 


$tipo = $_POST['tipo'];
if ($tipo==1)
{
$folio = $_POST['folio'];
cargar_remision($folio);	
}

function cargar_remision($folio)
{
	//con el folio proporcionado cargo la remision y la muestro en una tabla
	$folio_bus=Format9digit($folio);
		
		 //selecciono la base de datos
		global $con_micro_nef;
		 //hago la consulta
		$aplicar = "SELECT
        DVD.CLAVE_ARTICULO AS CLAVE,
        ART.nombre AS NOMBRE,
        DVD.unidades as CANTIDAD,
        DVD.precio_unitario as PRECIO,
        DVD.precio_total_neto AS TOTAL
        FROM DOCTOS_VE_DET DVD
        INNER JOIN ARTICULOS ART ON ART.ARTICULO_ID = DVD.ARTICULO_ID
        INNER JOIN DOCTOS_VE DV  ON DV.DOCTO_VE_ID = DVD.DOCTO_VE_ID
        WHERE DV.FOLIO = '$folio_bus' AND DV.tipo_docto='R'";
		//empiezo la consulta
		$query_aplicar = $con_micro_nef->prepare($aplicar);
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
				
				echo $tabla;
	
	
}


function recepcionar($folio)
{
		$folio_bus=Format9digit($folio);
		
		 //selecciono la base de datos
		global $con_micro_nef;
		 //hago la consulta
		$aplicar = "SELECT
		
        DVD.CLAVE_ARTICULO AS CLAVE,
        ART.nombre AS NOMBRE,
        DVD.unidades as CANTIDAD,
        DVD.precio_unitario as PRECIO,
        DVD.precio_total_neto AS TOTAL
		
        FROM DOCTOS_VE_DET DVD
        INNER JOIN ARTICULOS ART ON ART.ARTICULO_ID = DVD.ARTICULO_ID
        INNER JOIN DOCTOS_VE DV  ON DV.DOCTO_VE_ID = DVD.DOCTO_VE_ID
        WHERE DV.FOLIO = '$folio_bus' AND DV.tipo_docto='R'";
		//empiezo la consulta
		$query_aplicar = $con_micro_nef->prepare($aplicar);
		$query_aplicar->execute();
		$results = $query_aplicar->fetchAll(PDO::FETCH_ASSOC);
		//recorro el array y concateno la variables
		
	
		foreach($results as $row)
		{	//*******************************************************************
			//aqui hago la insercion 
			
			
			
			//*******************************************************************
		}
	
	
	
	
	
}





   
   
?>
