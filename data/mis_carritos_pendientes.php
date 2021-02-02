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
		$almacen_id = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	  if (isset($_POST['almacen_id'])){
      $almacen_id = $_POST['almacen_id'];
      }
	 
	  if ($id_usuario != ''){
	  
			mis_pedidos($id_usuario,$almacen_id);
	  }
	  else
	  {
		  echo 0;
	  }
     function mis_pedidos($id_usuario,$almacen_id){ 
global $database_conexion, $conex, $fecha_tabla_mis_pedidos, $folio_tabla_mis_pedidos, $estatus_tabla_mis_pedidos,$total_tabla_mis_pedidos, $estatus_tipo_pendiente,$estatus_tipo_ordenado,$estatus_tipo_proceso ,$estatus_tipo_ruta,$estatus_tipo_entregado ,$titulo_modal_lista_articulos,$btn_cerrar_modal_lista_articulos,$msj_sin_carritos_pendientes, $retomar_carrito ;

$minimo =1;

$consulta = "SELECT * FROM pedidos WHERE id_usuario = $id_usuario and id_sucursal = '$almacen_id' and estatus = '0p' ORDER BY id DESC";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados
$tipo_usuario = validar_usuario($_SESSION["logged_user"]);

echo '<table id="mis_carritos_pendientes" class="display table table-striped table-bordered table-hover table-responsive">
                    	<thead>
                    		<tr class="info">
                    			<th>'.$fecha_tabla_mis_pedidos.'</th>
                    			<th>'.$folio_tabla_mis_pedidos.'</th>
                    			<th>'.$estatus_tabla_mis_pedidos.'</th>';
                    			if ($tipo_usuario==2){
                    			echo '<th>'.$total_tabla_mis_pedidos.'</th>';
								}
								else {'<th hidden >'.$total_tabla_mis_pedidos.'</th>';}
                    			echo '<th></th>
                    		</tr>
                    	</thead><tbody>';
                    		$estatus = '';						
                    		$clase_td = '';						
                    		while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
                    		{
                    		
                    		switch($row2['estatus'])
                    		{
                    			case 0:
                    			$estatus = $estatus_tipo_pendiente;
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
                    			
                    			<td onclick="detalle_pedido('.$row2['id'].',0,'.$row2['total_pedido'].');" >
                    									'.$row2['fecha_pedido'].'
                    									</td>
                    									<td onclick="detalle_pedido('.$row2['id'].',0,'.$row2['total_pedido'].');">
                    									-
                    									</td>
                    			<td onclick="detalle_pedido('.$row2['id'].',0,'.$row2['total_pedido'].');" '.$clase_td.' align="center">
                    									'.$estatus.'
                    									</td>';
                    			if ($tipo_usuario==2){
                    			echo '<td align="right" onclick="detalle_pedido('.$row2['id'].',0,'.$row2['total_pedido'].');">
                    									$'.number_format($row2['total_pedido'],2).'
								</td>';}
								else{
                    			echo '<td hidden align="right" onclick="detalle_pedido('.$row2['id'].',0,'.$row2['total_pedido'].');">
                    									$'.number_format($row2['total_pedido'],2).'
								</td>';}
														
                    			echo '<td align="right" style="width:120px;">
                    									<input type="hidden" id="txt_folio_pedido_'.$row2['id'].'" value="-"/>
                    										<button class="btn btn-success btn_resume_cart" id="btnresumepedido_'.$row2['id'].'" >
                    										'.$retomar_carrito.'		
                    										</button>
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
				
                $(".btn_resume_cart").click(function(){
                               var btn_id = $(this).attr("id");
                         							   
							   var arr_id = btn_id.split("_");
							   var id_pedido = arr_id[1];
							   var folio = document.getElementById("txt_folio_pedido_"+id_pedido).value;
							
							   $("#txt_id_pedido").val(id_pedido);
							   
									jQuery.ajax({ 
										type: "POST",
										url: "data/verifica_carrito_actual.php",
										data: {id_pedido:id_pedido},
										success: function(resultados)
										
										{
												var id_pedido_abierto = $.trim(resultados);
											if (resultados == 0){
												//alert("No hay un carrito en curso"+id_pedido_abierto);
												// simplemente cambia el estatus del carrito de 0p a 0 para continuarlo
												retomar_carrito(id_pedido);
											}
											else if (resultados > 0)
											{
												
												var confirmacion = confirm("Existe un carrito en curso desea guardarlo?");
												if (confirmacion == true)
												{
													//alert("se guardara el pedido"+id_pedido_abierto);
													guardar_carrito(id_pedido_abierto);
													retomar_carrito(id_pedido);
												}
												else
												{
													//alert("No se guardara el pedido");
													eliminar_carrito(id_pedido_abierto);
													retomar_carrito(id_pedido);
												}
												
											}			
										
										}
									});
								
                });

         $("#mis_carritos_pendientes").DataTable();        
				
		});
	 
		</script>		
					
					
					';

 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">'.$msj_sin_carritos_pendientes.'</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                