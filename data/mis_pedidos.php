<?php include("conexion.php"); 
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

		$id_usuario = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	 
	  if ($id_usuario != ''){
	  
			mis_pedidos($id_usuario);
	  }
	  else
	  {
		  echo 0;
	  }
     function mis_pedidos($id_usuario){ 
global $database_conexion, $conex, $fecha_tabla_mis_pedidos, $folio_tabla_mis_pedidos, $estatus_tabla_mis_pedidos ,$traking_tabla_mis_pedidos,$total_tabla_mis_pedidos ,$orden_cliente_tabla_mis_pedidos,$estatus_tipo_ordenado,$estatus_tipo_proceso ,$estatus_tipo_ruta,$estatus_tipo_entregado ,$titulo_modal_lista_articulos,$btn_cerrar_modal_lista_articulos,$msj_sin_pedidos_mis_pedidos,$cc_tabla_mis_pedidos,$recolector_tabla_mis_pedidos,$estatus_pedido_preparado;

$id_empresa = id_empresa($id_usuario);
$proceso = '0';
$pausada = '0p';
$pend_aut = '4';

$consulta = "SELECT p.estatus as estatus, p.orden_compra as orden_compra, p.id as id, p.folio as folio, p.total_pedido as total_pedido, p.fecha_pedido_oficial as fecha_pedido_oficial, cc.nombre_cc as nombre_cc, user.nombre as nombre_r, user.apellido as apellido_r, alm.almacen as almacen
			FROM pedidos p 
			LEFT JOIN centro_costos cc on cc.id_cc = p.id_cc
			LEFT JOIN usuarios user on user.id = p.id_recolector
			LEFT JOIN almacenes alm on alm.almacen_id = p.id_sucursal
			WHERE p.id_usuario = '$id_usuario' and p.id_empresa = '$id_empresa' and p.estatus <> '$proceso' and p.estatus <> '$pausada' 
			ORDER BY p.folio DESC";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados
$tipo_usuario = validar_usuario($_SESSION["logged_user"]);
// <th>'.$traking_tabla_mis_pedidos.'</th>
echo '<table id="mis_pedidos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>'.$fecha_tabla_mis_pedidos.'</th>
                    			<th>Location</th>
                    			<th>'.$folio_tabla_mis_pedidos.'</th>
                    			<th>'.$estatus_tabla_mis_pedidos.'</th>
                    			<th hidden>'.$cc_tabla_mis_pedidos.'</th>
                    			<th hidden>'.$recolector_tabla_mis_pedidos.'</th>';
								//precios*********************************
                    			if ($tipo_usuario==2){
								echo '<th>'.$total_tabla_mis_pedidos.'</th>';}
								else{
								echo '<th hidden>'.$total_tabla_mis_pedidos.'</th>';}
                    			echo '<th hidden>'.$orden_cliente_tabla_mis_pedidos.'</th>
                    			
                    		</tr>
                    	</thead><tbody>';
                    		$estatus = '';						
                    		$clase_td = '';	
							$nombre_cc = '';
							$almacen = '';
							$nombre_recolector = '';
                    		while($row2 = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
                    		{
                    		
                    		switch($row2['estatus'])
                    		{
                    			case 0:
                    			$estatus = "Abierta";
                    			break;
                    			case 1:
                    			$estatus = $estatus_tipo_ordenado;
                    			$clase_td = 'class="btn-primary"';	
                    			break;
                    			case 2:
                    			$estatus = $estatus_pedido_preparado;
                    			$clase_td = 'class="btn-warning"';	
                    			break;
                    			case 3:
                    			
                    			$estatus = "Partial Delivery";
                    			$clase_td = 'class="btn-info"';	
                    			break;
								case 4:
                    			
                    			$estatus = "Delivery";
                    			$clase_td = 'class="btn-success"';	
                    			break;
                    		}
                    		if ($row2['orden_compra'] != '')
                    		{
                    			$orden_compra = $row2['orden_compra'];
                    		}
                    		else	
                    		{
                    			/**/ $orden_compra = '<button class="btn btn-warning btn_addorden" id="ocbtnpedido_'.$row2['id'].'" >
                    							<i class="fa fa-file"></i>
                    							O.C.
                    							</button>'; 
								
                    		}
							if ($row2['nombre_cc'] == ""){
								$nombre_cc = '-';
							}else
							{
								$nombre_cc = $row2['nombre_cc'];
							}
							if ($row2['almacen'] == ""){
								$almacen = '-';
							}else
							{
								$almacen = $row2['almacen'];
							}
							if ($row2['nombre_r'] == ""){
								$nombre_recolector = 'Personalmente';
							}else
							{
								$nombre_recolector = $row2['nombre_r'].''.$row2['apellido_r'];
							}
							
                    		echo ' <tr id="trmispedidos_'.$row2['id'].'" align="center">
                    			<td > '.$row2['fecha_pedido_oficial'].'
                    									</td>
								<td onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$almacen.'</td>
								
                    			<td onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									'.$row2['folio'].'
                    									</td>
                    			<td onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');" '.$clase_td.' align="center">
                    									'.$estatus.'
                    									</td>
                    			<td hidden onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									'.$nombre_cc.'
                    									</td>
                    			<td hidden onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									'.$nombre_recolector.'
                    									</td>';
									if ($tipo_usuario==2){					
                    			echo '<td align="right" onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									$'.number_format($row2['total_pedido'],2).'
									</td>';}
									else{					
                    			echo '<td hidden align="right" onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									$'.number_format($row2['total_pedido'],2).'
									</td>';}
														
                    			echo '<td  hidden align="right" style="width:120px;">
                    									<input type="hidden" id="txt_folio_pedido_'.$row2['id'].'" value="'.$row2['folio'].'"/>
                    									'.$orden_compra.'
                    									</td>
                    							</tr>
                    							';
                    							
                    							
                    					//<td><button id="" onclick="enviar('.$row2['id'].');">Enviar</button></td>
                    		}				
                    		 echo ' </tbody></table>';
 
 
 echo ' <div class="modal fade" id="pedido_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            '.$titulo_modal_lista_articulos.'
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;>
                                      
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">'.$btn_cerrar_modal_lista_articulos.'</button>
                                    </div>
                                    
                                </div>
                            </div>
                    </div>
					
					
					
			<script>
			
	$(document).ready(function(){
				
                $(".btn_addorden").click(function(){
                               var btn_id = $(this).attr("id");
                         							   
							   var arr_id = btn_id.split("_");
							   var id_pedido = arr_id[1];
							   var folio = document.getElementById("txt_folio_pedido_"+id_pedido).value;
							
							   $("#txt_id_pedido").val(id_pedido);
							   
							  
							   jQuery("#modal_orden .modal-header").html("Folio Pedido: "+folio) ;
							
							  $("#modal_orden").modal("show");

								$("#modal_orden").on("shown.bs.modal", function() {
								$("#txt_orden_compra").focus();
								})	
								
                });
                $("#mis_pedidos").DataTable({
						"order": [[ 2, "desc" ]]
					});   
				
		});
	 
		</script>		
					
					
					';
 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">'.$msj_sin_pedidos_mis_pedidos.'</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                