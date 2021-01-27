<?php include("../data/conexion.php"); 

		$id_usuario = '';
		$almacen_id = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	  if (isset($_POST['almacen_id'])){
      $almacen_id = $_POST['almacen_id'];
      }
	 
	  if ($id_usuario != ''){
	  
			lista_pedidos_traspaso();
	  }
	   if ($almacen_id != ''){
	  
			lista_pedidos_traspasar($almacen_id);
	  }
	  
     function lista_pedidos_traspaso(){ ///Mostrara la lista de los traspasos con el estatus y datos de folios
global $database_conexion, $conex;

$pedidos_traspaso = "
SELECT p.fecha_pedido_oficial as fecha_pedido_oficial, p.id_pedido as id_pedido, p.fecha_entrega as fecha_entrega, p.folio as folio, p.requisitor as requisitor, p.total_pedido as total_pedido, p.estatus as estatus, p.id_usuario as id_requi, p.folio_traspaso as folio_traspaso, user.nombre as nombre_r, user.apellido as apellido_r, alm.almacen as almacen, pp.folio as folio_cliente
FROM pedido_traspaso p 
LEFT JOIN almacenes alm on alm.almacen_id = p.almacen_id
LEFT JOIN pedidos pp on pp.id = p.id_pedido_cliente
LEFT JOIN usuarios user on user.id = p.id_usuario

WHERE p.estatus <> '0' and p.estatus <> '0p'
ORDER BY p.id_pedido DESC";
$resultado_pednef= mysql_query($pedidos_traspaso, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_pednef);
$total_pednef = mysql_num_rows($resultado_pednef);


if ($total_pednef > 0){ // con resultados

echo '<table id="lista_pedidos_traspaso" class="table table-striped table-bordered table-hover table-responsive display">
                    	<thead>
                    		<tr class="info">
								<th>Folio</th>
                    			<th hidden>Vendedor</th>
                    		    <th>#Requi</th>
                    		    <th>Requisitor</th>
                    		    <th>Almacen</th>
                    		    
                    		    <th>Fecha Requerido</th>
                    		    <th>Fecha Entrega</th>
                    		   
                    		    <th>Folio Traspaso</th>
                    		    <th>Total </th>
                    		    <th>Estatus </th>
                    		</tr>
                    	</thead><tbody>';
					$estatus = '';
					$atributo = '';	
					$vendedor = '';
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
				   $folio_cliente = $row2['folio_cliente'];
				   $total_pedido = $row2['total_pedido'];
				   
				   
				   
                   switch($row2['estatus'])
                   {
                   	
                   	case 1:		// en estado  solicitado en procesamiento
                  
                   	$estatus = '<div class="btn btn-primary btn-sm btn-block" >Solicitado </div>';
                   							
                   	break;
                   	case 2:			//// en estado aplicado
                   
                   	$estatus = '<div class="btn btn-warning btn-sm btn-block" >Traspaso realizado </div>';
                   	break;
                   	case 3: // en estado "Surtido" esto cuando ya se complete la recepcion al almacen correspondiente
                   	
                   	$estatus = '<div class="btn btn-success btn-sm btn-block" >Recibido por Almacen </div>';
                   	break;
                   }
                   
				if ($row2['almacen'] == ""){
					$almacen = '-';
				}else
				{
					$almacen = $row2['almacen'];
				}
				if ($row2['requisitor'] == ""){
					$requisitor = '-';
				}else
				{
					$requisitor = $row2['requisitor'];
				}
                if ($row2['nombre_r'] != '')
                {
                	$vendedor = $row2['nombre_r'].' '.$row2['apellido_r'] ;
                }
                else	
                {
                	$vendedor = '';
                } 
				
                   	
                    echo ' <tr >
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$folio.'</td>           	
        <td hidden>'.$vendedor.'
		<input type="hidden" id="txt_folio_pedidonef_'.$id_pedido.'" value="'.$folio.'"/></td>
		<td> '.$folio_cliente.'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$requisitor.'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$almacen.'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$row2['fecha_pedido_oficial'].'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$row2['fecha_entrega'].'</td>
    
       
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$row2['folio_traspaso'].'</td>
        <td align="right" onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">$'.number_format($total_pedido,2).'</td>
        <td align="right" id="td_estatus_'.$id_pedido.'">'.$estatus.'</td>	
                   </tr>';
                   
                   }
			
				   
        echo ' </tbody></table>';
 
 echo ' <div class="modal fade" id="traspaso_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                           Detalle Solicitud de traspaso
										  <input id="txt_id_ped_tras" value="" type="hidden" />
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;">
                                        <p class="h4">Modal para pedido.</p>
                                        <div class="table-responsive">
                                          
                                        </div>
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer" id="modal_footer_traspaso">
                                        
                                      
										<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                    
                                </div>
                            </div>
                    </div>
	<script>
	$(document).ready(function()
	{
        $("#lista_pedidos_traspaso").DataTable(
		{
				"order": [[ 3, "desc" ]]
		});
		
	});
	 
	</script>';
 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No tiene registrada ninguna solicitud de traspaso</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}

function lista_pedidos_traspasar($almacen_id){ ///Mostrara la lista de los traspasos con el estatus y datos de folios
global $database_conexion, $conex;

$pedidos_traspaso = "
SELECT p.fecha_pedido_oficial as fecha_pedido_oficial, p.id_pedido as id_pedido, p.fecha_entrega as fecha_entrega, p.folio as folio, p.requisitor as requisitor, p.total_pedido as total_pedido, p.estatus as estatus, p.id_usuario as id_requi, p.folio_traspaso as folio_traspaso, user.nombre as nombre_r, user.apellido as apellido_r, alm.almacen as almacen, pp.orden_compra as orden_compra
FROM pedido_traspaso p 
LEFT JOIN almacenes alm on alm.almacen_id = p.almacen_id
LEFT JOIN pedidos pp on pp.id = p.id_pedido_cliente
LEFT JOIN usuarios user on user.id = p.id_usuario

WHERE p.estatus <> '0' and p.estatus <> '0p'
ORDER BY p.id_pedido DESC";
$resultado_pednef= mysql_query($pedidos_traspaso, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_pednef);
$total_pednef = mysql_num_rows($resultado_pednef);


if ($total_pednef > 0){ // con resultados

echo '<table id="lista_pedidos_traspaso" class="table table-striped table-bordered table-hover table-responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Vendedor</th>
                    		    <th>Requisitor</th>
                    		    <th>Almacen</th>
                    		    <th>Folio</th>
                    		    <th>Fecha Requerido</th>
                    		    <th>Fecha Entrega</th>
                    		    <th>OC Dura</th>
                    		    <th>Folio Traspaso</th>
                    		    <th>Total </th>
                    		    <th>Estatus </th>
                    		</tr>
                    	</thead><tbody>';
					$estatus = '';
					$atributo = '';	
					$vendedor = '';
					$almacen = '';
					$folio_traspaso = '';
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
                   	
                   	case 1:		// en estado  solicitado en procesamiento
                  
                   	$estatus = 'Solicitado';
                   							
                   	break;
                   	case 2:			//// en estado aplicado
                   
                   	$estatus = '<div class="btn btn-primary btn-lg" >Traspaso Realizado </div>';
                   	break;
                   	case 3: // en estado "Surtido" esto cuando ya se complete la recepcion al almacen correspondiente
                   	
                   	$estatus = '<div class="btn btn-success btn-lg" >Recibido por Almacen </div>';
                   	break;
                   }
                   
				if ($row2['folio_traspaso'] == ""){
					$folio_traspaso = '<input type="button" class="btn btn-primary" onclick="prep_apli_traspaso('.$id_pedido.','.$folio.');" value="Realizar Traspaso"/>';
				}else
				{
					$folio_traspaso = $row2['folio_traspaso'];
				}
				if ($row2['almacen'] == ""){
					$almacen = '-';
				}else
				{
					$almacen = $row2['almacen'];
				}
				if ($row2['requisitor'] == ""){
					$requisitor = '-';
				}else
				{
					$requisitor = $row2['requisitor'];
				}
                if ($row2['nombre_r'] != '')
                {
                	$vendedor = $row2['nombre_r'].' '.$row2['apellido_r'] ;
                }
                else	
                {
                	$vendedor = '';
                } 
				
                   	
                    echo ' <tr >
                   	
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$vendedor.'
		<input type="hidden" id="txt_folio_pedidonef_'.$id_pedido.'" value="'.$folio.'"/></td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$requisitor.'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$almacen.'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$folio.'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$row2['fecha_pedido_oficial'].'</td>
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$row2['fecha_entrega'].'</td>
    
        <td onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">'.$row2['orden_compra'].'</td>
        <td >'.$folio_traspaso.'</td>
        <td align="right" onclick="detalle_pedido_tras('.$id_pedido.','.$folio.','.$total_pedido.');">$'.number_format($total_pedido,2).'</td>
        <td align="right" id="td_estatus_'.$id_pedido.'">'.$estatus.'</td>	
                   </tr>';
                   
                   }				
        echo ' </tbody></table>';
 
 echo ' <div class="modal fade" id="traspaso_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                           Detalle Solicitud de traspaso
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;>
                                        <p class="h4">Modal para pedido.</p>
                                        <div class="table-responsive">
                                          
                                        </div>
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Cerrar</button>
                                    </div>
                                    
                                </div>
                            </div>
                    </div>
	<script>
	$(document).ready(function()
	{
        $("#lista_pedidos_traspaso").DataTable(
		{
				"order": [[ 3, "desc" ]]
		});
		
	});
	 
	</script>';
 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No tiene registrada ninguna solicitud de traspaso</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}


?>





                    	
                    	
                