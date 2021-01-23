<?php include("../data/conexion.php"); 

		$id_usuario = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	 
	  if ($id_usuario != ''){
	  
			lista_pedidos($id_usuario);
	  }
	  else
	  {
		  echo 0;
	  }
	  
     function lista_pedidos($id_usuario){ ///Mostrara la lista de los pedidos realizados asignados al vendedor
global $database_conexion, $conex;

$minimo =1;

$consulta_relacion = "
SELECT p.fecha_pedido_oficial as fecha_pedido_oficial, p.id as id_pedido, p.folio as folio, p.total_pedido as total_pedido, p.estatus as estatus, p.id_usuario as id_requi, p.id_empresa as id_empresa, p.orden_compra as orden_compra, cc.nombre_cc as nombre_cc, user.nombre as nombre_r, user.apellido as apellido_r, alm.almacen as almacen
FROM relaciones r
LEFT JOIN pedidos p on r.id_requisitor = p.id_usuario
LEFT JOIN centro_costos cc on cc.id_cc = p.id_cc
LEFT JOIN usuarios user on user.id = p.id_recolector
LEFT JOIN almacenes alm on alm.almacen_id = p.id_sucursal

WHERE r.id_vendedor = '$id_usuario' and p.estatus <> '0' and p.estatus <> '0p'
ORDER BY p.id DESC";
$resultado_relacion = mysql_query($consulta_relacion, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_relacion);
$total_relaciones = mysql_num_rows($resultado_relacion);


if ($total_relaciones > 0){ // con resultados


echo '<table id="lista_pedidos" class="table table-striped table-bordered table-hover table-responsive display">
                    	<thead>
                    		<tr class="info">
                    										<th>Requisitor</th>
                    		                                <th>Empresa</th>
                    		                                <th>Almacen</th>
                    		                                <th>Folio</th>
                    		                                <th>Fecha</th>
                    		                                <th>Ord. de Compra</th>
                    		                                <th>Total</th>
                    		                                <th>Estatus</th>
															<th hidden>Recolector</th>
                    		                                <th>#Traspaso</th>
                    		</tr>
                    	</thead><tbody>';
                    		$estatus = '';
                    		$atributo = '';	
							$almacen = '';
                    		$validacion_btn_tracking = '';	
							$folios_traspasos = '';
                    		while($row2 = mysql_fetch_array($resultado_relacion,MYSQL_BOTH)) // html de articulos a mostrar
                    		{
								
							
								switch($row2['estatus'])
								{
									
								case 1:		// en estado "Ordenado" opcion de requerir pedido a almacen
									$atributo = 'disabled';
									$estatus = '<div class="dropdown">
									<button class="btn btn-danger btn-block dropdown-toggle btn_estatus" type="button" id="btnestatus_'.$row2['id_pedido'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btnestatus_'.$row2['id_pedido'].'">
										Ordenado
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
									<li class="btn btn-info btn-block btn_solicitud_traspaso" id="btn_solicitudtraspaso_'.$row2['id_pedido'].'" onclick="solicitar_traspaso('.$row2['id_pedido'].');">Solicitar Traspaso</li>
									<li class="btn btn-primary btn-block btn_pedido_nef" id="btn_realizarpedidonef_'.$row2['id_pedido'].'" onclick="requerir_pedido_nef('.$row2['id_pedido'].');" >Realizar Pedido NEF</li>
									</ul>
									</div>';
															
									break;
								case 2:			//// en estado "En proceso"
									$atributo = 'disabled';
									$estatus = '<div class="btn btn-info btn-md" >Sending Supplies </div>';
									break;
								case 3: // en estado "Surtido" esto cuando ya se complete la recepcion al almacen correspondiente
									$atributo = 'disabled';
									$estatus = '<div class="btn btn-success btn-md" >Delivered </div>';
									break;
								}
							
                    		
                    		if ($row2['nombre_cc'] != '')
                    		{
                    			$nombre_cc = $row2['nombre_cc'];
                    		}
                    		else	
                    		{
                    			$nombre_cc = '-';
                    		}
							if ($row2['almacen'] == ""){
								$almacen = '-';
							}else
							{
								$almacen = $row2['almacen'];
							}
                    		if ($row2['nombre_r'] != '')
                    		{
                    			$nombre_recolector = $row2['nombre_r'].' '.$row2['apellido_r'] ;
                    		}
                    		else	
                    		{
                    			$nombre_recolector = 'Personalmente';
                    		}
							
                    			
                    		 echo ' <tr >
                    			
                    		<td onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.Nombre($row2['id_requi']).'
							<input type="hidden" id="txt_folio_pedido_'.$row2['id_pedido'].'" value="'.$row2['folio'].'"/></td>
                    		<td onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.EMPRESA_NOMBRE($row2['id_empresa']).'</td>
                    		<td onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$almacen.'</td>
                    		<td onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$row2['folio'].'</td>
                    		<td onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$row2['fecha_pedido_oficial'].'</td>
                    		<td onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$row2['orden_compra'].'</td>
                    		<td align="right" onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">$'.number_format($row2['total_pedido'],2).'</td>
                    		<td align="right" id="td_estatus_'.$row2['id_pedido'].'">'.$estatus.'</td>
                    		<td align="center" style="width:120px;" hidden>
                    
							'.$nombre_recolector.'
                    		</td>
							<td align="center" style="width:120px;">	
								'.$folios_traspasos.'
							</td>	
                    		</tr>';
                    							
                    		
                    		
                    							
                    		}				
                    		 echo ' </tbody></table>';


 
 echo ' <div class="modal fade" id="pedido_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            Detalle pedido
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
			<div class="modal fade" id="modal_imagen_tras" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                      
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;>
                                        
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
		
		
        $("#lista_pedidos").DataTable(
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
                            <h3><a href="#">You not have request</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                