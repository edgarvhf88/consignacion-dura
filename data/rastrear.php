<?php include("../data/constructor.php"); 
 /* if ($_SESSION["logged_user"] <> ''){ header('Location: ../index.php'); } */ 
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

include("../displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$valor = '';
		  
	  if (isset($_POST['valor'])){
      $valor = $_POST['valor'];
      buscar($valor,$id_empresa_user_activo);
	  }
	  
     function buscar($valor,$id_empresa_user_activo){ 
global $database_conexion, $conex, $resultados_busqueda_consultor, $sin_resultados_busqueda_consultor, $folio_tabla_cosultor, $fecha_tabla_cosultor, $orden_tabla_cosultor , $estatus_tabla_cosultor, $tracking_tabla_cosultor, $clave_tabla_cosultor, $articulo_tabla_cosultor, $cantidad_tabla_cosultor, $imagen_tabla_cosultor, $estatus_tipo_ordenado, $estatus_tipo_proceso, $estatus_tipo_ruta, $estatus_tipo_entregado;

/// $valor puede ser #Pedido - Orden de compra - Articulo - tracking

$consulta = "SELECT a.nombre as nombre,  a.clave_empresa as clave_empresa, a.src_img as imagen, pd.cantidad as cantidad, p.folio as folio, p.fecha_pedido as fecha_pedido, p.orden_compra as orden_compra, p.estatus as estatus, p.tracking as tracking 
FROM pedidos_det pd
INNER JOIN articulos a on a.id = pd.id_articulo
INNER JOIN pedidos p on p.id = pd.id_pedido
WHERE a.nombre LIKE  '%$valor%' and a.id_empresa='$id_empresa_user_activo' and p.estatus <> '0' and  p.estatus <> '0p'
OR a.clave_empresa LIKE  '%$valor%' and p.id_empresa='$id_empresa_user_activo' and p.estatus <> '0' and  p.estatus <> '0p'
OR p.orden_compra LIKE  '%$valor%' and p.id_empresa='$id_empresa_user_activo' and p.estatus <> '0' and  p.estatus <> '0p'
OR p.folio LIKE  '%$valor%' and p.id_empresa='$id_empresa_user_activo' and p.estatus <> '0' and  p.estatus <> '0p'
OR p.tracking LIKE  '%$valor%' and p.id_empresa='$id_empresa_user_activo' and p.estatus <> '0' and  p.estatus <> '0p'
ORDER BY p.folio DESC";


$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados

/* echo '<header>
                    <h2><span class="icon-pages"></span>'.$resultados_busqueda_consultor.'</h2>                    
                </header>'; */
echo '<table class="table table-striped table-bordered table-hover table-responsive">
                    	<tr class="info">
                    		<th>'.$folio_tabla_cosultor.'</th>
                    		<th>'.$fecha_tabla_cosultor.'</th>
                    		<th>'.$orden_tabla_cosultor.'</th>
                    		<th>'.$estatus_tabla_cosultor.'</th>
                    		<th>'.$tracking_tabla_cosultor.'</th>
                    		<th>'.$clave_tabla_cosultor.'</th>
                    		<th>'.$articulo_tabla_cosultor.'</th>
                    		<th>'.$cantidad_tabla_cosultor.'</th>
                    		<th>'.$imagen_tabla_cosultor.'</th>
                    		
                    	</tr>';
$estatus = '';						
$clase_td = '';					
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{

switch($row['estatus'])
{
	case 0:
	$estatus = "Abierta";
	break;
	case 1:
	$estatus = $estatus_tipo_ordenado;
	$clase_td = 'class="btn-warning"';	
	break;
	case 2:
	$estatus = $estatus_tipo_proceso;
	$clase_td = 'class="btn-info"';	
	break;
	case 3:
	$estatus = $estatus_tipo_ruta;
	$clase_td = 'class="btn-primary"';	
	break;
	case 4:
	$estatus = "Surtido Parcial"; /* pendiente para requerimiento en futuro Atte. Ing. Edgar Herebia ;) */
	break;
	case 5:
	$estatus = $estatus_tipo_entregado;
	$clase_td = 'class="btn-success"';	
	break;
}

echo ' <tr align="center">
                    		
                    		<td  >
							'.$row['folio'].'
							</td>
							<td >
							'.$row['fecha_pedido'].'
							</td>
							<td >
							'.$row['orden_compra'].'
							</td>
                    		<td  '.$clase_td.' align="center">
							'.$estatus.'
							</td>
                    		<td >
							'.$row['tracking'].'
							</td>
                    		<td align="right" >
							'.$row['clave_empresa'].'
							</td>
							<td >
							'.$row['nombre'].'
							</td>
							<td >
							'.$row['cantidad'].'
							</td>
							<td >
							<img src="assets/images/productos/emp-'.$id_empresa_user_activo.'/'.$row['imagen'].'" width="50" heigth="50">
							</td>
                    		
														
                    	</tr>
					';					
}				
 echo ' </table>';				
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">'.$sin_resultados_busqueda_consultor.'</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>