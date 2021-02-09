<?php include("../data/conexion.php"); 

$id =$_POST['id'];
cargar_remision($id);

function cargar_remision($id_orden)
{
	
	
	//con el folio proporcionado cargo la remision y la muestro en una tabla
		$rem_folio = sin_remision($id_orden); 
		$folio_oc = folio_oc($id_orden);
		if ($rem_folio != "" )
		{
			$boton_recepcionar= '<h4 align"center">Remision: '.$rem_folio.'</h4>';
		}
		else {
			$estatus =estatus($id_orden);
			if($estatus==1){
			$boton_recepcionar = '<div class="col-lg-12" align="center">
							 <button id="gen_tras_micro" onclick="remision_allpart('.$id_orden.');" class="btn btn-primary" >Generar Remision en AllPart </button>
			</div>';}
			else {$boton_recepcionar= '<h4 align"center">En proceso de captura.</h4>';}
		}
		
		
		 //selecciono la base de datos
		global $conex;
		 //hago la consulta
		$consulta = "SELECT 
		art.clave_microsip as clave_microsip,
		art.clave_empresa as clave_empresa,
		art.unidad_medida as unidad_medida,
		art.nombre as nombre,
		
		oc_det.id_oc_det as id_oc_det,
		oc_det.posicion as posicion,
		oc_det.cantidad as cantidad,
		oc_det.udm as udm,
		oc_det.numero_parte as numero_parte,
		oc_det.descripcion as descripcion,
		oc_det.precio_unitario as precio_unitario,
		oc_det.precio_total as precio_total
		
					
		FROM ordenes_det oc_det 
		INNER JOIN articulos art ON art.id = oc_det.articulo_id
		
		WHERE oc_det.id_oc = '$id_orden' ";
		
		
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		
		
		//recorro el array y concateno la variables
		$rems="";
		$tabla=' 
		
				<h4 align"center">Folio orden de cliente: '.$folio_oc.'</h4>
		
		<table id="ordene_det" class="table table-striped table-bordered table-hover table-responsive">
                    	<thead>
                            <tr class="info">
                                
                                <th>Clave Microsip</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                                
                            </tr>
                        </thead><tbody>';
		while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
		{
			if ($row['precio_total'] > 0)
		{
			$total = str_replace(",","",$row['precio_total']);
			$total = number_format($total,2);
		}
		else
		{
			$total = "";
		}
			
         $tabla .=' <tr>
             <td>'.$row['clave_microsip'].'</td>
             <td>'.$row['nombre'].'</td>
             <td>'.$row['cantidad'].'</td>
             <td align="right">$'.number_format($row['precio_unitario'],2).' </td>
             <td align="right">$'.$total.'</td>
         </tr>';
      
			//*******************************************************************
		}
	
	$tabla .=' </table>
                <script>
                $(document).ready( function () {
                    $("#orden_det").DataTable();
                } );
                </script>';
	$tabla .=$boton_recepcionar;			
				echo $tabla;
	
	
}

function sin_remision($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT folio_remision FROM ordenes WHERE id_oc = '$id'";
		
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$folio_remision="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$folio_remision = $row['folio_remision'];	
				
			}
			
			 return $folio_remision;
	
}

function folio_oc($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT folio FROM ordenes WHERE id_oc = '$id'";
		
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$folio="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$folio = $row['folio'];	
				
			}
			
			 return $folio;
	
}




function estatus($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT estatus FROM ordenes WHERE id_oc = '$id'";
		
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$estatus="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$estatus = $row['estatus'];	
				
			}
			
			 return $estatus;
	
}
?>