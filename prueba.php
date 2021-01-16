<?php include("data/conexion.php");
$clave_nombre = 'INDUS';


$consulta = "SELECT 
		ARTI.ARTICULO_ID AS ARTID,
		ARTI.NOMBRE AS ARTICULO,
		ARTI.UNIDAD_VENTA AS UDM,
		PRECIOS_A.PRECIO AS PRECIO,
		CLAVES.CLAVE_ARTICULO AS CLAVE
FROM ARTICULOS ARTI
	FULL OUTER JOIN CLAVES_ARTICULOS CLAVES ON CLAVES.ARTICULO_ID = ARTI.ARTICULO_ID
	LEFT JOIN PRECIOS_ARTICULOS PRECIOS_A ON PRECIOS_A.ARTICULO_ID = ARTI.ARTICULO_ID AND PRECIOS_A.PRECIO_EMPRESA_ID = '42'
	
WHERE (CLAVES.ROL_CLAVE_ART_ID = '17')
AND (ARTI.ESTATUS = 'A')
AND (ARTI.ES_ALMACENABLE = 'S')
AND (ARTI.NOMBRE LIKE '%$clave_nombre%')
AND (ARTI.ARTICULO_ID IN(SELECT DOCS_INDET.ARTICULO_ID FROM DOCTOS_IN_DET DOCS_INDET WHERE (DOCS_INDET.ALMACEN_ID = '19')))";
 
$tabla = ""; 
$res = $con_micro->prepare($consulta);
$res->execute();
$res->setFetchMode(PDO::FETCH_OBJ);
if (!$res){
	 echo "<div style='color:#FF0000'>fallo en consulta!</div>";
	 exit;}	 
$count = 0;

// si esta agregado poner color verde, si no esta agregado colocar el boton agregar a toolcrib
$tabla .= '<table id="articulos" class="table table-striped table-bordered table-hover table-responsive display">
                    	<thead>
                    		<tr class="bg-info">
                    			<th>Clave</th>
                    		    <th>Articulo</th>
                    		    <th>Estatus</th>
								<th>Unid. Medida</th>
								<th>Existencia</th>
                    		    <th>Precio</th>
                    		    <th>Estandares</th>
                    		    <th>Agregar a Catalogo</th>
							</tr>
                    	</thead><tbody>';
while ($row=$res->fetch()){
			$count++;
			
			//$lista[$row->ARTID] = $row->CLAVE.' - '.$row->ARTICULO;
		$tabla .= '<tr><td>'.$row->CLAVE.'</td>
					<td>'.$row->ARTICULO.'</td>
					<td> Sin Agregar </td>
					<td>'.$row->UDM.'</td>
					<td align="right">'.ExistenciaMicrosip($row->ARTID).'</td>
					<td align="right">$'.number_format($row->PRECIO,2).'</td>
					<td></td>
					<td align="center"><input type="button" class="btn btn-success btn-lg" value="+"></td>
					
					</tr>';	
			
	}
$tabla .= ' </tbody></table>
<script>
	$(document).ready(function(){
                $("#articulos").DataTable({
						"order": [[ 1, "ASC" ]]
					});
				
		});
	 
		</script>	
';	

if ($count > 0){
	
echo $tabla;	
}
else
{
	echo "Sin Resultados";
}
?>