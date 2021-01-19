<?php include("../data/conexion.php"); 

		$id_usuario = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	 
	  if ($id_usuario != ''){
	  
			lista_pedidos_nef();
	  }
	  
     function lista_pedidos_nef(){ ///Mostrara la lista de los pedidos_nef con el estatus y datos de folios
global $database_conexion, $conex;



$pedidos_nef = "
SELECT p.fecha_pedido_oficial as fecha_pedido_oficial, p.id_pedido as id_pedido, p.folio as folio, p.folio_pedido_microsip as folio_pedmicro , p.total_pedido as total_pedido, p.estatus as estatus, p.id_usuario as id_requi, user.nombre as nombre_r, user.apellido as apellido_r, alm.almacen as almacen
FROM pedido_nef p 
LEFT JOIN almacenes alm on alm.almacen_id = p.almacen_id
LEFT JOIN usuarios user on user.id = p.id_usuario

WHERE p.estatus <> '0' and p.estatus <> '0p'
ORDER BY p.id_pedido DESC";
$resultado_pednef= mysql_query($pedidos_nef, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_pednef);
$total_pednef = mysql_num_rows($resultado_pednef);


if ($total_pednef > 0){ // con resultados

echo '<table id="lista_pedidos_nef" class="table table-striped table-bordered table-hover table-responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Requisitor</th>
                    		    <th>Almacen</th>
                    		    <th>Folio</th>
                    		    <th>Fecha Requerido</th>
                    		    <th>Pedido Nef</th>
                    		    <th>Remision Nef</th>
                    		    <th>OC allpart</th>
                    		    <th>Recepcion allpart</th>
                    		    <th>Taspaso allpart</th>
                    		    <th>Total </th>
                    		    <th>Estatus </th>
                    		</tr>
                    	</thead><tbody>';
					$estatus = '';
					$atributo = '';	
					$almacen = '';
					$id_pedido = '';
					$folio = '';
					$total_pedido = 0;
					$validacion_btn_tracking = '';					
					$pedido_nef = '';					
                   while($row2 = mysql_fetch_array($resultado_pednef,MYSQL_BOTH)) // html de articulos a mostrar
                   {
                   $id_pedido = $row2['id_pedido'];
				   $folio = $row2['folio'];
				   $total_pedido = $row2['total_pedido'];
				   
                   switch($row2['estatus'])
                   {
                   	
                   	case 1:		// en estado "Ordenado" opcion de requerir pedido a almacen
                  
                   	$estatus = 'Ordenado';
                   							
                   	break;
                   	case 2:			//// en estado "En proceso"
                   
                   	$estatus = '<div class="btn btn-primary btn-lg" >En Proceso </div>';
                   	break;
                   	case 3: // en estado "Surtido" esto cuando ya se complete la recepcion al almacen correspondiente
                   	
                   	$estatus = '<div class="btn btn-success btn-lg" >Surtido </div>';
                   	break;
                   }
                   
				if ($row2['almacen'] == ""){
					$almacen = '-';
				}else
				{
					$almacen = $row2['almacen'];
				}
                if ($row2['nombre_r'] != '')
                {
                	$requisitor = $row2['nombre_r'].' '.$row2['apellido_r'] ;
                }
                else	
                {
                	$requisitor = '';
                } 
				   
				if ($row2['folio_pedmicro'] != '')
                {
                	$pedido_nef = $row2['folio_pedmicro'];
					
                }
                else	
                {
                	$pedido_nef = '<button class="btn btn-danger btn-block " type="button" id="btnestatus_'.$id_pedido.'" onclick="insertar_pedido_nef('.$id_pedido.');"> Generar Pedido Microsip </button>';
                }
                   	
                    echo ' <tr >
                   	
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');">'.$requisitor.'
		<input type="hidden" id="txt_folio_pedidonef_'.$id_pedido.'" value="'.$folio.'"/></td>
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');">'.$almacen.'</td>
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');">'.$folio.'</td>
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');">'.$row2['fecha_pedido_oficial'].'</td>
        <td onclick="">'.$pedido_nef.'</td>
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');"></td>
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');"></td>
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');"></td>
        <td onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');"></td>
        <td align="right" onclick="detalle_pedido_nef('.$id_pedido.','.$folio.','.$total_pedido.');">$'.number_format($total_pedido,2).'</td>
        <td align="right" id="td_estatus_'.$id_pedido.'">'.$estatus.'</td>	
                   </tr>';
                   
                   }				
                    echo ' </tbody></table>';


 
 echo ' 				
	<script>
	$(document).ready(function()
	{
		$("#modal_cargando").modal("hide");
		
        $("#lista_pedidos_nef").DataTable(
		{
				"order": [[ 2, "desc" ]]
		});
		
	});
	 
	</script>';
 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No tiene ningun pedido</a></h3>
                           
                        </div>
                    </div>
				</div>
		<script>
			$(document).ready(function()
			{
				$("#modal_cargando").modal("hide");
	
			});
	 
		</script>';		
		


}

}




?>





                    	
                    	
                