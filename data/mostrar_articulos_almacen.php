<?php include("conexion.php"); 

		$id_empresa = '';
				  
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
      $almacen_id = $_POST['almacen_id'];
     	mostrar_articulos_almacen($id_empresa,$almacen_id);
	  }	
	 
     function mostrar_articulos_almacen($id_empresa,$almacen_id){ 
global  $conex;

if($id_empresa == 0){
$consulta_usuarios = "
					SELECT 
					a.id as id,
					a.id_empresa as id_empresa,
					a.clave_microsip as c_microsip,
					a.clave_empresa as c_empresa, 
					a.nombre as articulo, 
					a.descripcion as descip, 
					a.precio as precio, 
					a.unidad_medida as udm,  
					a.src_img as imagen, 
					e.nombre as empresa,
					exis.min as min,
					exis.max as max,
					exis.reorden as reorden,
					exis.existencia_actual as existencia
					  FROM articulos a 
					  INNER JOIN empresas e on a.id_empresa = e.id_empresa	
					  LEFT JOIN existencias exis on exis.id_articulo = a.id ";	
}else {
$consulta_usuarios = "
					SELECT 
					a.id as id,
					a.id_empresa as id_empresa,
					a.clave_microsip as c_microsip,
					a.id_microsip as id_microsip,
					a.clave_empresa as c_empresa, 
					a.nombre as articulo, 
					a.descripcion as descip, 
					a.precio as precio, 
					a.unidad_medida as udm, 
					a.src_img as imagen, 
					e.nombre as empresa,
					exis.min as min,
					exis.max as max,
					exis.reorden as reorden,
					exis.existencia_actual as existencia
					  FROM articulos a 
					  INNER JOIN empresas e on e.id_empresa = a.id_empresa
					  LEFT JOIN existencias exis on exis.id_articulo = a.id
					  WHERE a.id_empresa = '$id_empresa'  AND exis.almacen_id = '$almacen_id'";	
}

$resultado = mysql_query($consulta_usuarios, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados
echo '<table id="mostrar_articulos" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-info">
				<!-- <th>Select</th> 
				<th>#DURA</th> -->
				<th>#ALLPART</th>
				<th >Articulo</th>
				<th>Unidad Medida</th>
				<th>Precio</th>
				<th>Imagen</th>
				<th>Min</th>
				<th>Max</th>
				<th>Reorden</th>
				<th>En Consigna</th>
				<th>En Fisico</th>
				<th hidden>Conteo Inv.</th>
				<th>Sync</th>
			</tr>
		</thead><tbody id="tbody_articulos">';
		$articulo = '';						
		$descripcion = '';
		$clase = "";
		$desc_clase = "";
		$clase_func = "";
		$title_datos = "";
				
		while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
		{
									$id_empresa = $row2['id_empresa']; 	
									$articulo_id = $row2['id_microsip']; 	
									$id_art = $row2['id']; 	
									if ($row2['imagen'] != ""){
									$src_img = $row2['imagen'];	
									$ruta = "assets/images/productos/emp-".$id_empresa."/".$src_img;	
									}else{
									$src_img = "sin_imagen.jpg";
									$ruta = "assets/images/".$src_img;	}	
				                  	$articulo = str_replace(['"', "'"], '', $row2['articulo']);
				                  	$descripcion = str_replace(['"', "'"], '', $row2['descip']);
								
							if ($row2['min'] != ""){
							$min_val = $row2['min'];	
							}else{
							//$min_val = '<a href="#" id="art_min_'.$row2['id'].'" onclick=""> Asignar </a> ';
							//$min_val = '<input id="art_min_'.$row2['id'].'" type="text" value="0" size="5"/>';
							$min_val = '';
							}	
							if ($row2['max'] != ""){
							$max_val = $row2['max'];	
							}else{
							$max_val = 0;
							}	
							if ($row2['reorden'] != ""){
							$reorden_val = $row2['reorden'];	
							}else{
							$reorden_val = 0;
							}	
							if ($row2['existencia'] != ""){
							$existencia_val = $row2['existencia'];	
							}else{
							$existencia_val = 0;
							}	
							
							$check_box = '<div class="checkboxbtn lg col-lg-12" id="checkboxbtn_'.$row2['id'].'">
											<label>
												<input type="checkbox" id="checkboxart_'.$row2['id'].'" checked class="chk_art_select" style=""/> 
											</label>
										</div>';
							if (($reorden_val != 0) && ($max_val != 0) && ($min_val != 0))
							{			
								if ($existencia_val <= $reorden_val){
									if ($existencia_val <= $min_val){
										$clase = "danger"; /// URGENTE pedir	
										$desc_clase = "Urgente Surtir Material";
										$clase_func = "list_urgente";
										
									}else {
										$clase = "warning"; /// reordenar	
										$desc_clase = "Es Necesario Reordenar";
										$clase_func = "list_reorden";
									}
								}
								else if ($existencia_val > $max_val)
								{
									$clase = "info";// sobreInventariado
									$desc_clase = "Este Articulo se encuentra Sobre-Inventariado";
									$clase_func = "list_sobreinventario";
									$check_box = '';
								}
								else
								{
									$clase = "success";// Se encuentra en buen estatus dentro de los parametros
									$desc_clase = "Articulo Dentro de los Parametros";
									$clase_func = "list_bien";
								}
							}
							else
							{ /// si el min,max,reorden = 0 , no se han especificador los puntos de reorden
								$clase = "light text-danger";// sobreInventariado
									$desc_clase = "Nesesita actualizar los datos de Minimo, Maximo y Reorden para ayudar a mantener en stock";
									$clase_func = "list_sinminmaxreo";
									$check_box = '';
							}	
							$unidades_pedidas = unidades_pedidas($id_art,$id_empresa);
							$total_cantidad_cobrada = cantidades_cobradas($id_art,$almacen_id);
			
		$total_consumido_nopagado = suma_diferencias($id_art,$almacen_id,1) - $total_cantidad_cobrada; // 1 = INV CERRADO
		if ($total_consumido_nopagado > 0){
			$existencia_actual = $existencia_val - $total_consumido_nopagado;		
			}else
			{
			$existencia_actual = $existencia_val;	
			}
		
		
		$title_datos = "total cantidades cobradas=".$total_cantidad_cobrada." / diferencias=".suma_diferencias($id_art,$almacen_id,1)." / no pagado y consumido=".$total_consumido_nopagado;
		$arr = explode('_',ultimo_inv_art($id_art));
		if (count($arr) > 1){
			$ult_inv_act = $arr[0]; 
		$fecha_inventario = $arr[1]; 	
		}
		else
		{
			$ult_inv_act = ""; 
		$fecha_inventario = ""; 	
		}
			
								
		echo '<tr id="trarticulo_'.$row2['id'].'"  class="'.$clase.' '.$clase_func.'" title="'.$desc_clase.'">
			<!-- <td  id="td_check_'.$row2['id'].'" align="center" >
					'.$check_box.'
				</td> 
			<td  id="tdclaveempresa_'.$row2['id'].'">'.$row2['c_empresa'].'</td>-->
			<td id="tdclavemicrosip_'.$row2['id'].'">'.$row2['c_microsip'].'</td>
			<td id="tdarticulo_'.$row2['id'].'">'.$row2['articulo'].'</td>
			<td id="tdudm_'.$row2['id'].'">'.$row2['udm'].'</td>
			<td align="right" id="tdprecio_'.$row2['id'].'">'.$row2['precio'].'</td>
			<td>
			<p>
			<img src="'.$ruta.'" width="50" heigth="50" id="imagen_'.$row2['id'].'" class="imagenes" />
			</p>
			<input id="art_c_empresa_'.$row2['id'].'" type="hidden" value="'.$row2['c_empresa'].'"/>
			<input id="art_c_microsip_'.$row2['id'].'" type="hidden" value="'.$row2['c_microsip'].'"/>
			<input id="art_id_microsip_'.$row2['id'].'" type="hidden" value="'.$row2['id_microsip'].'"/>
			<input id="art_articulo_'.$row2['id'].'" type="hidden" value="'.$articulo.'"/>
			<input id="art_descip_'.$row2['id'].'" type="hidden" value="'.$descripcion.'"/>
			<input id="art_empresa_'.$row2['id'].'" type="hidden" value="'.$id_empresa.'"/>
			<input id="art_precio_'.$row2['id'].'" type="hidden" value="'.$row2['precio'].'"/>
			<input id="art_imagen_'.$row2['id'].'" type="hidden" value="'.$row2['imagen'].'"/>
			<input id="art_min_'.$row2['id'].'" type="hidden" value="'.$min_val.'"/>
			<input id="art_max_'.$row2['id'].'" type="hidden" value="'.$max_val.'"/>
			<input id="art_reorden_'.$row2['id'].'" type="hidden" value="'.$reorden_val.'"/>
			<input id="art_existencia_'.$row2['id'].'" type="hidden" value="'.$existencia_val.'"/>
			<input id="art_existenciaactual_'.$row2['id'].'" type="hidden" value="'.$existencia_actual.'"/>
			</td>
			<td id="tdmin_'.$row2['id'].'">'.$min_val.'</td>
			<td id="tdmax_'.$row2['id'].'">'.$max_val.'</td>
			<td id="tdreorden_'.$row2['id'].'">'.$reorden_val.'</td>
			<td id="tdexistencia_'.$row2['id'].'">'.$existencia_val.'</td>
			<td id="tdexistenciaactual_'.$row2['id'].'" title="Es la cantidad que hay fisicamente">'.$existencia_actual.'</td>
			<td id="tdunidped_'.$row2['id'].'" hidden >'.$unidades_pedidas.'</td>
			<td id="td_actualizar_'.$row2['id'].'"><input type="button" class="btn btn-info btn_actualiza" id="btnact_'.$row2['id_microsip'].'" value="Sincronizar" /></td>
			
			</tr>
				';
				                    							
				                    							
				                    					
				                    		
				                    							
		}				
		echo ' </tbody></table>';
 
 echo '<script> 
	$(document).ready(function(){
				var tabla_articulos = $("#mostrar_articulos").DataTable();
				
					$("#mostrar_articulos").dataTable().fnSettings().aoDrawCallback.push({
					"fn": function () {
						$(".btn_actualiza").click(function(){
						var tr_id = $(this).attr("id")
						var arr_id = tr_id.split("_");
						var id_articulo = arr_id[1];
					
						SincronizarInventarioArticulo(id_articulo);
						
							
						});
						},
					"order": [[ 1, "asc" ]]
					});
		
                $(".lista_articulos").on("dblclick", function(){
                            //var tr_id = $(this).attr("id")
							//var arr_id = tr_id.split("_");
							//var id_articulo = arr_id[1];
							//
							//var clave_empresa = document.getElementById("art_c_empresa_"+id_articulo).value;
							//var clave_microsip = document.getElementById("art_c_microsip_"+id_articulo).value;
							//var articulo = document.getElementById("art_articulo_"+id_articulo).value;
							//var descripcion = document.getElementById("art_descip_"+id_articulo).value;
							//var empresa = document.getElementById("art_empresa_"+id_articulo).value;
							//var precio = document.getElementById("art_precio_"+id_articulo).value;
							//var imagen = document.getElementById("art_imagen_"+id_articulo).value;
							//var minimo = document.getElementById("art_min_"+id_articulo).value;
							//var maximo = document.getElementById("art_max_"+id_articulo).value;
							//var reorden = document.getElementById("art_reorden_"+id_articulo).value;
							//var existencia = document.getElementById("art_existencia_"+id_articulo).value;
							//
							//$("#txt_id_articulo").val(id_articulo);
							//$("#txt_clave_empresa").val(clave_empresa);
							//$("#txt_clave_microsip").val(clave_microsip);	
							//$("#txt_nombre_articulo").val(articulo);
							//$("#txt_descripcion").val(descripcion);
							//$("#select_art_empresa").val(empresa);
							//$("#txt_precio").val(precio);
							//$("#txt_min").val(minimo);
							//$("#txt_max").val(maximo);
							//$("#txt_reorden").val(reorden);
							//$("#txt_existencia").val(existencia);
							
							//$("#txt_imagen").val(imagen);
							//	jQuery("#modal_articulo .modal-header").html("Modificar datos de Articulo") ;
							
							//$("#modal_articulo").modal("show");


							//lista_categorias();
							//lista_reg_categorias();
							   
                });


                 $(".imagenes").click(function(){
						 var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_articulo = arr_id[1];

						var imagen = document.getElementById("art_imagen_"+id_articulo).value;
						var empresa = document.getElementById("art_empresa_"+id_articulo).value;
						var precio = document.getElementById("art_precio_"+id_articulo).value;
						var clave_empresa = document.getElementById("art_c_empresa_"+id_articulo).value;
						var clave_microsip = document.getElementById("art_c_microsip_"+id_articulo).value;
						var articulo = document.getElementById("art_articulo_"+id_articulo).value;
						var descripcion = document.getElementById("art_descip_"+id_articulo).value;
						if (imagen != ""){
							 $("#imagen_max").attr("src","assets/images/productos/emp-"+empresa+"/max/"+imagen) ;
						}else{
							 $("#imagen_max").attr("src","assets/images/sin_imagen.jpg") ;
						}
						
						var minimo = document.getElementById("art_min_"+id_articulo).value;
						var maximo = document.getElementById("art_max_"+id_articulo).value;
						var reorden = document.getElementById("art_reorden_"+id_articulo).value;
						var existencia = document.getElementById("art_existencia_"+id_articulo).value;

					  $("#max_precio").html("Precio: "+precio);
					  $("#nombre_articulo_detalle").html("Nombre: "+articulo);
					  $("#clave_articulo_detalle").html("Clave: "+clave_empresa);
					  $("#descripcion_articulo_detalle").html(descripcion);
					  $("#existencia_articulo_detalle").html("Existencia: "+existencia);
					  $("#min_articulo_detalle").html("Min: "+minimo);
					  $("#max_articulo_detalle").html("Max: "+maximo);
					  $("#reorden_articulo_detalle").html("Reorden: "+reorden);

					$("#zoom").modal("show");

                 });
				 
				 $(".btn_actualiza").click(function(){
						var tr_id = $(this).attr("id")
						var arr_id = tr_id.split("_");
						var id_articulo = arr_id[1];
					
						SincronizarInventarioArticulo(id_articulo);
						//mostrar_articulos(11);
							
				 });
				 
				 $("#chk_reorden").change(function(){
					var valor = $(this).prop("checked");
					if (valor == true){
						$(".list_reorden").show();
					}
					else
					{
						$(".list_reorden").hide();
					}
                });
				$("#chk_urgentes").change(function(){
					var valor = $(this).prop("checked");
					if (valor == true){
						$(".list_urgente").show();
					}
					else
					{
						$(".list_urgente").hide();
					}
                });
				$("#chk_sobreinventario").change(function(){
					var valor = $(this).prop("checked");
					if (valor == true){
						$(".list_sobreinventario").show();
					}
					else
					{
						$(".list_sobreinventario").hide();
					}
                });
				$("#chk_bien").change(function(){
					var valor = $(this).prop("checked");
					if (valor == true){
						$(".list_bien").show();
					}
					else
					{
						$(".list_bien").hide();
					}
                });
			
				$(".btn_solicitar_compra").click(function(){
					//alert("ok");
					solicitar_compra();
                });
			
				 
				 
                 
				
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existen Articulos del almacen selccionado</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                